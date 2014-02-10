<?php

namespace Kek\ShopBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;

class CookieListener
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->setCookie(new Cookie('msci', $this->order->getId()));
    }
}
