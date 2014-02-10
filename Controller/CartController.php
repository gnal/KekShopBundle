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

        // build a form for each item
        $forms = [];
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

        return [
            'order' => $order,
            'forms' => $forms,
        ];
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
