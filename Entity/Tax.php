<?php

namespace Kek\ShopBundle\Entity;

use Kek\ShopBundle\Model\Tax as BaseTax;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Tax extends BaseTax
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
