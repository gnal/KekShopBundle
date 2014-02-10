<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;

class CartController extends Controller
{
    /**
     * @Route("/cart")
     * @Template()
     */
    public function showAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        return ['order' => $order];
    }

    /**
     * @Route("/cart/update")
     */
    public function updateAction()
    {
        $orderClass = $this->container->getParameter('kek_shop.order.class');
        $orderItemClass = $this->container->getParameter('kek_shop.order_item.class');
        $productClass = $this->container->getParameter('kek_shop.product.class');
        $cookie = false;

        $order = $this->get('kek_shop.order_provider')->getCurrentOrder(false);

        if (!$order) {
            $order = new $orderClass;

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

        // if item already exists we just update the quantity or else we create it
        if ($item = $order->hasItemForProduct($product)) {
            $item->setQuantity($item->getQuantity() + $this->getRequest()->request->get('quantity', 1));
        } else {
            $item = new $orderItemClass;
            $item->setQuantity($this->getRequest()->request->get('quantity', 1));
            $item->setProduct($product);
            $order->getItems()->add($item);
        }

        $om = $this->getDoctrine()->getManager();
        $om->persist($order);
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

        $om = $this->getDoctrine()->getManager();
        $om->persist($order);
        $om->flush();

        return $this->redirect($this->generateUrl('kek_shop_cart_show'));
    }
}
