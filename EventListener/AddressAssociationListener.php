<?php

namespace Kek\ShopBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("doctrine.event_listener", attributes = {"event" = Events::loadClassMetadata})
 */
class AddressAssociationListener
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
            'fieldName'    => 'user',
            'targetEntity' => $this->userClass,
            'inversedBy' => 'addresses',
            'joinColumn' => [
                'onDelete' => 'CASCADE',
            ],
        ]);
    }
}
