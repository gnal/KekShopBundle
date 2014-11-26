<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class UserAssociationListener
{
    private $addressClass;
    private $userClass;

    /**
     * @DI\InjectParams({
     *     "addressClass" = @DI\Inject("%kek_shop.address.class%"),
     *     "userClass" = @DI\Inject("%fos_user.model.user.class%")
     * })
     */
    public function __construct($addressClass, $userClass)
    {
        $this->addressClass = $addressClass;
        $this->userClass = $userClass;
    }

    public function loadClassMetadata(EventArgs $event)
    {
        $meta = $event->getClassMetadata();

        if ($meta->name !== $this->userClass) {
            return;
        }

        $this->mapAddresses($meta);
    }

    private function mapAddresses($meta)
    {
        if ($meta->hasAssociation('addresses')) {
            return;
        }

        $meta->mapOneToMany([
            'fieldName'    => 'addresses',
            'targetEntity' => $this->addressClass,
            'mappedBy' => 'user',
            'cascade' => ['persist', 'remove'],
            'orphanRemoval' => true,
        ]);
    }
}
