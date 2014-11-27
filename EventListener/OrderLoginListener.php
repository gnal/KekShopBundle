<?php

namespace Kek\ShopBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\SecurityEvents;
use FOS\UserBundle\FOSUserEvents;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * @DI\Service
 */
class OrderLoginListener
{
    private $user;
    private $om;
    private $class;

    /**
     * @DI\InjectParams({
     *     "om" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "class" = @DI\Inject("%kek_shop.order.class%")
     * })
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->class = $class;
    }

    /**
     * @DI\Observe(SecurityEvents::INTERACTIVE_LOGIN)
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->user = $event->getAuthenticationToken()->getUser();
        $event->getDispatcher()->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->giveOrderToUser($event, $this->user);
    }

    /**
     * @DI\Observe(FOSUserEvents::REGISTRATION_COMPLETED)
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $this->giveOrderToUser($event, $event->getUser());
    }

    private function giveOrderToUser($event, $user)
    {
        $request = $event->getRequest();
        $repository = $this->om->getRepository($this->class);

        // no need to do this if user has no cookie
        if ($request->cookies->has('msci')) {
            $order = $repository->findCurrentById($request->cookies->get('msci'));
            if ($order) {
                // if the user already had a order but made a new one while not logged, we delete his old one
                $old = $repository->findCurrentByUser($user);
                if ($old) {
                    $this->om->remove($old);
                }

                $order->setUser($user);

                $this->om->persist($order);
                $this->om->flush();
            }
            $event->getResponse()->headers->clearCookie('msci');
        }
    }
}
