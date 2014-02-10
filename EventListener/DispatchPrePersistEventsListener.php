<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

use Kek\ShopBundle\Event\OrderItemEvent;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::prePersist})
 */
class DispatchPrePersistEventsListener
{
    private $dispatcher;
    private $orderItemClass;

    /**
     * @DI\InjectParams({
     *     "dispatcher" = @DI\Inject("event_dispatcher"),
     *     "orderItemClass" = @DI\Inject("%kek_shop.order_item.class%")
     * })
     */
    public function __construct($dispatcher, $orderItemClass)
    {
        $this->dispatcher = $dispatcher;
        $this->orderItemClass = $orderItemClass;
    }

    public function prePersist(EventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
// die(var_dump(get_class_methods($event)));
    }
}
