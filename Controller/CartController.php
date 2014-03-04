<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints;
use Kek\ShopBundle\Event\QuantityFormEvent;

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
                $class = $this->container->getParameter('kek_shop.quantity_form_type_class.class');
                $type = new $class($item);
                $forms[$item->getId()] = $this->createForm($type, $item);
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

        $count = $order ? $order->getItems()->count() : null;

        return new Response($count);
    }

    public function removeAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        $order->removeItemById($this->getRequest()->attributes->get('item'));

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('kek_shop_cart_show'));
    }
}
