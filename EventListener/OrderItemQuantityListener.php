<?php

namespace Kek\ShopBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;

use Kek\ShopBundle\Event\OrderItemEvent;

/**
 * @DI\Service
 */
class OrderItemQuantityListener
{
    private $om;
    private $min;
    private $max;

    /**
     * @DI\InjectParams({
     *     "om" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "min" = @DI\Inject("%kek_shop.quantity_min%"),
     *     "max" = @DI\Inject("%kek_shop.quantity_max%")
     * })
     */
    public function __construct(ObjectManager $om, $min, $max)
    {
        $this->om = $om;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @DI\Observe(OrderItemEvent::ORDER_ITEM_CREATED)
     */
    public function onOrderItemCreated(OrderItemEvent $event)
    {
        $orderItem = $event->getOrderItem();

        $this->fixQuantity($orderItem);

        // $this->om->persist($orderItem);
    }

    /**
     * @DI\Observe(OrderItemEvent::ORDER_ITEM_UPDATED)
     */
    public function onOrderItemUpdated(OrderItemEvent $event)
    {
        $orderItem = $event->getOrderItem();

        $this->fixQuantity($orderItem);

        // $this->om->persist($orderItem);
    }

    private function fixQuantity($orderItem)
    {
        $quantity = $orderItem->getQuantity();
die('dadada');
        if ($quantity > $this->max) {
            $quantity = $this->max;
        }

        if ($quantity < $this->min) {
            $quantity = $this->min;
        }

        $orderItem->setQuantity($quantity);
    }
}
