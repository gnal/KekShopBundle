<?php

namespace Kek\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kek\ShopBundle\Model\AddressType as BaseAddressType;

/**
 * @ORM\Entity
 */
class AddressType extends BaseAddressType
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
