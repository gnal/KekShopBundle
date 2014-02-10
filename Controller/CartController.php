<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\Validator\Constraints;

class CartController extends Controller
{
    /**
     * @Route("/cart")
     * @Template()
     */
    public function showAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();
        $forms = [];

        // build a form for each row
        foreach ($order->getItems() as $item) {
            $builder = $this->createFormBuilder($item);
            $builder
                ->add('quantity', 'text', [
                    'constraints' => [new Constraints\Range([
                        'min' => 1,
                        'max' => $this->container->getParameter('kek_shop.quantity_max'),
                    ])],
                ])
            ;
            $forms[$item->getId()] = $builder->getForm();
        }

        if ($this->getRequest()->isMethod('POST')) {
            $id = $this->getRequest()->query->get('item');
            $forms[$id]->bind($this->getRequest());
            if ($forms[$id]->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect($this->generateUrl('kek_shop_cart_show'));
            }
        }

        foreach ($forms as &$form) {
            $form = $form->createView();
        }

        return [
            'order' => $order,
            'forms' => $forms,
        ];
    }

    /**
     * @Route("/cart/add")
     */
    public function updateAction()
    {
        $orderClass = $this->container->getParameter('kek_shop.order.class');
        $orderItemClass = $this->container->getParameter('kek_shop.order_item.class');
        $productClass = $this->container->getParameter('kek_shop.product.class');
        $cookie = false;
        $om = $this->getDoctrine()->getManager();

        $order = $this->get('kek_shop.order_provider')->getCurrentOrder(false);

        if (!$order) {
            $order = new $orderClass;
            $om->persist($order);

            if ($this->getUser()) {
                $order->setUser($this->getUser());
            } else {
                $cookie = true;
            }
        }

        $product = $this->getDoctrine()->getRepository($productClass)->findOneBy(
            [
                'id' => $this->getRequest()->request->get('product'),
                'published' => true,
            ]
        );

        if (!$product) {
            throw $this->createNotFoundException();
        }

        $quantity = $this->getRequest()->request->get('quantity', 1);

        // if item already exists we just update the quantity or else we create it
        if ($item = $order->hasItemForProduct($product)) {
            if ($this->getRequest()->request->get('add')) {
                $quantity += $item->getQuantity();
            }
            $item->setQuantity($quantity);
        } else {
            $item = new $orderItemClass;
            $item->setQuantity($quantity);
            $item->setProduct($product);
            $order->getItems()->add($item);
        }

        $om->flush();

        $response = $this->redirect($this->generateUrl('kek_shop_cart_show'));

        if ($cookie) {
            $response->headers->setCookie(new Cookie('msci', $order->getId()));
        }

        return $response;
    }

    /**
     * @Route("/cart/{item}/remove")
     */
    public function removeAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        $order->removeItemById($this->getRequest()->attributes->get('item'));

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('kek_shop_cart_show'));
    }
}
