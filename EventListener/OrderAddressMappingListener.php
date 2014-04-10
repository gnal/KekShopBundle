<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class OrderAddressMappingListener
{
    private $addressClass;
    private $orderClass;

    /**
     * @DI\InjectParams({
     *     "addressClass" = @DI\Inject("%kek_shop.order_address.class%"),
     *     "orderClass" = @DI\Inject("%kek_shop.order.class%")
     * })
     */
    public function __construct($addressClass, $orderClass)
    {
        $this->addressClass = $addressClass;
        $this->orderClass = $orderClass;
    }

    public function loadClassMetadata(EventArgs $event)
    {
        $meta = $event->getClassMetadata();

        if ($meta->name !== $this->addressClass) {
            return;
        }

        $this->mapUser($meta);
    }

    private function mapUser($meta)
    {
        if ($meta->hasAssociation('user')) {
            return;
        }

        $meta->mapManyToOne([
            'fieldName'    => 'order',
            'targetEntity' => $this->orderClass,
            'inversedBy' => 'addresses',
            'joinColumns' => [
                [
                    'onDelete' => 'CASCADE',
                ],
            ],
        ]);
    }
}
