<?php

namespace Kek\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Product
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translatable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Timestampable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Publishable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Uploadable;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $discountedPrice;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $discountedFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $discountedTo;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $taxable;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $image;

    protected $imageFile;

    public function getCurrentPrice()
    {
        if (1) {
            return $this->price;
        } else {
            return $this->price;
        }
    }

    public function getUploadFields()
    {
        return ['image'];
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        $this->updateAt = new \DateTime;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
    }

    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;

        return $this;
    }

    public function getDiscountedFrom()
    {
        return $this->discountedFrom;
    }

    public function setDiscountedFrom($discountedFrom)
    {
        $this->discountedFrom = $discountedFrom;

        return $this;
    }

    public function getDiscountedTo()
    {
        return $this->discountedTo;
    }

    public function setDiscountedTo($discountedTo)
    {
        $this->discountedTo = $discountedTo;

        return $this;
    }

    public function getTaxable()
    {
        return $this->taxable;
    }

    public function setTaxable($taxable)
    {
        $this->taxable = $taxable;

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
