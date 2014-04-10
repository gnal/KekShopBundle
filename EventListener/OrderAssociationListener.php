<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class OrderAssociationListener
{
    private $orderClass;
    private $orderItemClass;
    private $orderAddressClass;
    private $userClass;

    /**
     * @DI\InjectParams({
     *     "orderClass" = @DI\Inject("%kek_shop.order.class%"),
     *     "orderItemClass" = @DI\Inject("%kek_shop.order_item.class%"),
     *     "orderAddressClass" = @DI\Inject("%kek_shop.order_address.class%"),
     *     "userClass" = @DI\Inject("%fos_user.model.user.class%")
     * })
     */
    public function __construct($orderClass, $orderItemClass, $orderAddressClass, $userClass)
    {
        $this->orderClass = $orderClass;
        $this->orderItemClass = $orderItemClass;
        $this->orderAddressClass = $orderAddressClass;
        $this->userClass = $userClass;
    }

    public function loadClassMetadata(EventArgs $event)
    {
        $meta = $event->getClassMetadata();

        if ($meta->name !== $this->orderClass) {
            return;
        }

        $this->mapItems($meta);
        $this->mapUser($meta);
        $this->mapAddresses($meta);
    }

    private function mapItems($meta)
    {
        if ($meta->hasAssociation('items')) {
            return;
        }

        $meta->mapManyToMany([
            'fieldName'    => 'items',
            'targetEntity' => $this->orderItemClass,
            'joinTable' => [
                // 'joinColumns' => [
                //     'JoinColumn' => [
                //         'onDelete' => 'CASCADE',
                //         'referencedColumnName' => 'id',
                //     ],
                // ],
                'inverseJoinColumns' => [
                    'JoinColumn' => [
                        'unique' => true,
                        'referencedColumnName' => 'id',
                    ],
                ],
            ],
            'cascade' => ['persist', 'remove'],
        ]);
    }

    private function mapAddresses($meta)
    {
        if ($meta->hasAssociation('addresses')) {
            return;
        }

        $meta->mapOneToMany([
            'fieldName'    => 'addresses',
            'targetEntity' => $this->orderAddressClass,
            'mappedBy' => 'order',
            'cascade' => ['persist', 'remove'],
        ]);
    }

    private function MapUser($meta)
    {
        if ($meta->hasAssociation('user')) {
            return;
        }

        $meta->mapManyToOne([
            'fieldName'    => 'user',
            'targetEntity' => $this->userClass,
            // 'inversedBy' => 'orders',
            'joinColumn' => [
                'onDelete' => 'SET NULL',
            ],
        ]);
    }
}
