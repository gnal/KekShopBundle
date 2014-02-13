<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints;

class CartController extends Controller
{
    /**
     * @Template()
     */
    public function showAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();
        $forms = [];

        if ($order) {
            // build a form for each item
            foreach ($order->getItems() as $item) {
                $builder = $this->createFormBuilder($item);
                $builder
                    ->add('quantity', 'text', [
                        'constraints' => [new Constraints\Range([
                            'min' => $this->container->getParameter('kek_shop.quantity_min'),
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
        }

        $repository = $this->getDoctrine()->getRepository('KekShopBundle:Tax');
        $taxes = $repository->findBy([
            'published' => true,
        ]);

        return [
            'order' => $order,
            'forms' => $forms,
            'taxes' => $taxes,
            'calculator' => $this->get('kek_shop.calculator'),
        ];
    }

    public function countAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        return new Response($order->getItems()->count());
    }

    public function removeAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        $order->removeItemById($this->getRequest()->attributes->get('item'));

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('kek_shop_cart_show'));
    }
}
