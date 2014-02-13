<?php

namespace Kek\ShopBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Tax
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translatable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Timestampable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Publishable;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0
     * )
     */
    protected $rate;

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->getTranslation()->getName();
    }
}
