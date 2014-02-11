<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpKernel\KernelEvents;
use Kek\ShopBundle\EventListener\OrderCookieListener;

class ProductController extends Controller
{
    // you should override this with your own implementation
    /**
     * @Template()
     */
    public function indexAction()
    {
        $class = $this->container->getParameter('kek_shop.product.class');
        $repository = $this->getDoctrine()->getRepository($class);
        $products = $repository->findAll();

        $class = $this->container->getParameter('kek_shop.product_category.class');
        $repository = $this->getDoctrine()->getRepository($class);
        $categories = $repository->findAll();

        // build a form for each product
        $forms = [];
        foreach ($products as $product) {
            $builder = $this->createFormBuilder();
            $builder
                ->add('quantity', 'text', [
                    'attr' => [
                        'class' => 'form-control text-center',
                    ],
                    'data' => $this->container->getParameter('kek_shop.quantity_min'),
                    'constraints' => [new Constraints\Range([
                        'min' => $this->container->getParameter('kek_shop.quantity_min'),
                        'max' => $this->container->getParameter('kek_shop.quantity_max'),
                    ])],
                ])
            ;
            $forms[$product->getId()] = $builder->getForm();
        }

        if ($this->getRequest()->isMethod('POST')) {
            $id = $this->getRequest()->query->get('product');
            $forms[$id]->bind($this->getRequest());
            if ($forms[$id]->isValid()) {
                $this->addItem($id, $forms[$id]->getData()['quantity']);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('kek_shop_cart_show'));
            }
        }

        foreach ($forms as &$form) {
            $form = $form->createView();
        }

        return [
            'forms' => $forms,
            'products' => $products,
            'categories' => $categories,
        ];
    }

    protected function addItem($productId, $quantity)
    {
        $orderClass = $this->container->getParameter('kek_shop.order.class');
        $orderItemClass = $this->container->getParameter('kek_shop.order_item.class');
        $productClass = $this->container->getParameter('kek_shop.product.class');
        $cookie = false;

        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();
        if (!$order) {
            $order = new $orderClass;
            $this->getDoctrine()->getManager()->persist($order);

            if ($this->getUser()) {
                $order->setUser($this->getUser());
            } else {
                $cookie = true;
            }
        }

        $product = $this->getDoctrine()->getRepository($productClass)->findOneBy(
            [
                'id' => $productId,
                'published' => true,
            ]
        );
        if (!$product) {
            throw $this->createNotFoundException();
        }

        // if item already exists we just update the quantity or else we create it
        if ($item = $order->hasItemForProduct($product)) {
            $quantity += $item->getQuantity();
            $item->setQuantity($quantity);
        } else {
            $item = new $orderItemClass;
            $item->setQuantity($quantity);
            $item->setProduct($product);
            $order->getItems()->add($item);
        }

        if ($cookie) {
            $this->get('event_dispatcher')->addListener(KernelEvents::RESPONSE, [new OrderCookieListener($order), 'onKernelResponse']);
        }
    }
}
