<?php

namespace Kek\ShopBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class TaxTranslation
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translation;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
