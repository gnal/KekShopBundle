<?php

namespace Kek\ShopBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Kek\ShopBundle\Model\OrderItem;
use Doctrine\Common\Persistence\ObjectManager;

class OrderItemEvent extends Event
{
    const ORDER_ITEM_CREATED = 'kek_shop.order_item.created';
    const ORDER_ITEM_UPDATED = 'kek_shop.order_item.updated';

    private $om;
    private $orderItem;

    public function __construct(OrderItem $orderItem, ObjectManager $om)
    {
        $this->orderItem = $orderItem;
        $this->om = $om;
    }

    public function getOrderItem()
    {
        return $this->orderItem;
    }

    public function getManager()
    {
        return $this->om;
    }
}
