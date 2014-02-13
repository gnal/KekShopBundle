<?php

namespace Kek\ShopBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Address
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $phone;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $address;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $province;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $zip;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $country;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDefaultBilling;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDefaultShipping;

    public function __construct()
    {
        $this->isDefaultBilling = false;
        $this->isDefaultShipping = false;
    }

    public function getIsDefaultBilling()
    {
        return $this->isDefaultBilling;
    }

    public function setIsDefaultBilling($isDefaultBilling)
    {
        $this->isDefaultBilling = $isDefaultBilling;

        return $this;
    }

    public function getIsDefaultShipping()
    {
        return $this->isDefaultShipping;
    }

    public function setIsDefaultShipping($isDefaultShipping)
    {
        $this->isDefaultShipping = $isDefaultShipping;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}