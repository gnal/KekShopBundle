<?php

namespace Kek\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kek\ShopBundle\Model\AddressTypeTranslation as BaseAddressTypeTranslation;

/**
 * @ORM\Entity
 */
class AddressTypeTranslation extends BaseAddressTypeTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
