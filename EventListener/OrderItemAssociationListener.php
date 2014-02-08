<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class OrderItemAssociationListener
{
    private $productClass;
    private $orderItemClass;

    /**
     * @DI\InjectParams({
     *     "productClass" = @DI\Inject("%kek_shop.product.class%"),
     *     "orderItemClass" = @DI\Inject("%kek_shop.order_item.class%")
     * })
     */
    public function __construct($productClass, $orderItemClass)
    {
        $this->productClass = $productClass;
        $this->orderItemClass = $orderItemClass;
    }

    public function loadClassMetadata(EventArgs $event)
    {
        $meta = $event->getClassMetadata();

        if ($meta->name !== $this->orderItemClass) {
            return;
        }

        $this->mapProduct($meta);
    }

    private function MapProduct($meta)
    {
        if (!$meta->hasAssociation('product')) {
            return;
        }

        $meta->mapManyToOne([
            'fieldName'    => 'product',
            'targetEntity' => $this->productClass,
            'joinColumns' => [
                [
                    'onDelete' => 'SET NULL',
                ],
            ],
        ]);
    }
}
