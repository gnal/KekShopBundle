<?php

namespace Kek\ShopBundle\Entity;

use Kek\ShopBundle\Model\TaxTranslation as BaseTaxTranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaxTranslation extends BaseTaxTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
