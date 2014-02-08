<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class ProductAssociationListener
{
    private $productClass;

    /**
     * @DI\InjectParams({
     *     "productClass" = @DI\Inject("%kek_shop.product.class%")
     * })
     */
    public function __construct($productClass)
    {
        $this->productClass = $productClass;
    }

    public function loadClassMetadata(EventArgs $event)
    {
        $meta = $event->getClassMetadata();

        if ($meta->name !== $this->productClass) {
            return;
        }

        // if (!$meta->hasAssociation('category')) {
        //     $meta->mapManyToOne([
        //         'fieldName'    => 'category',
        //         'targetEntity' => 'Msi\StoreBundle\Entity\ProductCategory',
        //         'inversedBy' => 'products',
        //         'joinColumns' => [
        //             [
        //                 'onDelete' => 'SET NULL',
        //             ],
        //         ],
        //     ]);
        // }

        // if (!$meta->hasAssociation('images')) {
        //     $meta->mapOneToMany([
        //         'fieldName'    => 'images',
        //         'targetEntity' => 'Msi\StoreBundle\Entity\ProductImage',
        //         'mappedBy' => 'product',
        //         'orderBy' => ['position' => 'ASC'],
        //     ]);
        // }
    }
}
