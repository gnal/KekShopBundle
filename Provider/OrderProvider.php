<?php

namespace Kek\ShopBundle\Provider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("kek_shop.order_provider")
 */
class OrderProvider
{
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container"),
     * })
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getCurrentOrder($throwNotFound = true)
    {
        $orderClass = $this->container->getParameter('kek_shop.order.class');
        $order = $this->container->get('doctrine')->getRepository($orderClass)->findCurrent($this->getUser(), $this->container->get('request'));

        if ($throwNotFound && !$order) {
            throw new NotFoundHttpException();
        }

        return $order;
    }

    private function getUser()
    {
        if (null === $token = $this->container->get('security.context')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }
}
