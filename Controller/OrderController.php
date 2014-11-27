<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints;

class OrderController extends Controller
{
    /**
     * @Template()
     */
    public function historyAction()
    {
        if (!$this->getUser()) {
            throw new AccessDeniedException;
        }

        $class = $this->container->getParameter('kek_shop.order.class');
        $repository = $this->getDoctrine()->getRepository($class);
        $orders = $repository->findBy(
            [
                'user' => $this->getUser(),
            ],
            [
                'createdAt' => 'DESC',
            ]
        );

        return [
            'orders' => $orders,
        ];
    }
}
