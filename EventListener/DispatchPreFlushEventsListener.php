<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

use Kek\ShopBundle\Event\OrderItemEvent;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::preFlush})
 */
class DispatchPreFlushEventsListener
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

    public function preFlush(EventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        // foreach ($uow->getScheduledEntityInsertions() as $entity) {
        //     die('create '.get_class($entity));
        //     die(var_dump(get_class($entity)));
        //     if ($entity instanceof $this->orderItemClass) {
        //         $event = new OrderItemEvent($entity, $em);
        //         $this->dispatcher->dispatch(OrderItemEvent::ORDER_ITEM_CREATED, $event);
        //     }
        // }

        // foreach ($uow->getScheduledEntityUpdates() as $entity) {
        //     die('update');
        //     die(var_dump(get_class($entity)));
        //     if ($entity instanceof $this->orderItemClass) {
        //         $event = new OrderItemEvent($entity, $em);
        //         $this->dispatcher->dispatch(OrderItemEvent::ORDER_ITEM_UPDATED, $event);
        //     }
        // }
    }
}
