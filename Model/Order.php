<?php

namespace Kek\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 */
abstract class Order
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Timestampable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $frozenAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $subtotal;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $gstTotal;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $pstTotal;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $total;

    // shipping

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingFirstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingLastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingPhone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $shippingPhoneExt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingCity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingProvince;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingCountry;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shippingZip;

    // billing

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingFirstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingLastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingPhone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $billingPhoneExt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingCity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingProvince;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingCountry;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $billingZip;

    protected $items;

    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    public function __construct()
    {
        $this->items = new ArrayCollection;
        $this->status = 1;
    }

    public function hasBothAdresses()
    {
        // ..
    }

    public function getShippingFullName()
    {
        return $this->getShippingFirstName().' '.$this->getShippingLastName();
    }

    public function getBillingFullName()
    {
        return $this->getBillingFirstName().' '.$this->getBillingLastName();
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getGstTotal()
    {
        return $this->gstTotal;
    }

    public function setGstTotal($gstTotal)
    {
        $this->gstTotal = $gstTotal;

        return $this;
    }

    public function getPstTotal()
    {
        return $this->pstTotal;
    }

    public function setPstTotal($pstTotal)
    {
        $this->pstTotal = $pstTotal;

        return $this;
    }

    public function getShippingFirstName()
    {
        return $this->shippingFirstName;
    }

    public function setShippingFirstName($shippingFirstName)
    {
        $this->shippingFirstName = $shippingFirstName;

        return $this;
    }

    public function getShippingLastName()
    {
        return $this->shippingLastName;
    }

    public function setShippingLastName($shippingLastName)
    {
        $this->shippingLastName = $shippingLastName;

        return $this;
    }

    public function getShippingEmail()
    {
        return $this->shippingEmail;
    }

    public function setShippingEmail($shippingEmail)
    {
        $this->shippingEmail = $shippingEmail;

        return $this;
    }

    public function getShippingPhone()
    {
        return $this->shippingPhone;
    }

    public function setShippingPhone($shippingPhone)
    {
        $this->shippingPhone = $shippingPhone;

        return $this;
    }

    public function getShippingPhoneExt()
    {
        return $this->shippingPhoneExt;
    }

    public function setShippingPhoneExt($shippingPhoneExt)
    {
        $this->shippingPhoneExt = $shippingPhoneExt;

        return $this;
    }

    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    public function setBillingFirstName($billingFirstName)
    {
        $this->billingFirstName = $billingFirstName;

        return $this;
    }

    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    public function setBillingLastName($billingLastName)
    {
        $this->billingLastName = $billingLastName;

        return $this;
    }

    public function getBillingEmail()
    {
        return $this->billingEmail;
    }

    public function setBillingEmail($billingEmail)
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }

    public function getBillingPhone()
    {
        return $this->billingPhone;
    }

    public function setBillingPhone($billingPhone)
    {
        $this->billingPhone = $billingPhone;

        return $this;
    }

    public function getBillingPhoneExt()
    {
        return $this->billingPhoneExt;
    }

    public function setBillingPhoneExt($billingPhoneExt)
    {
        $this->billingPhoneExt = $billingPhoneExt;

        return $this;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingCity()
    {
        return $this->shippingCity;
    }

    public function setShippingCity($shippingCity)
    {
        $this->shippingCity = $shippingCity;

        return $this;
    }

    public function getShippingProvince()
    {
        return $this->shippingProvince;
    }

    public function setShippingProvince($shippingProvince)
    {
        $this->shippingProvince = $shippingProvince;

        return $this;
    }

    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry($shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;

        return $this;
    }

    public function getShippingZip()
    {
        return $this->shippingZip;
    }

    public function setShippingZip($shippingZip)
    {
        $this->shippingZip = $shippingZip;

        return $this;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getBillingCity()
    {
        return $this->billingCity;
    }

    public function setBillingCity($billingCity)
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    public function getBillingProvince()
    {
        return $this->billingProvince;
    }

    public function setBillingProvince($billingProvince)
    {
        $this->billingProvince = $billingProvince;

        return $this;
    }

    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    public function getBillingZip()
    {
        return $this->billingZip;
    }

    public function setBillingZip($billingZip)
    {
        $this->billingZip = $billingZip;

        return $this;
    }

    public function getFrozenAt()
    {
        return $this->frozenAt;
    }

    public function setFrozenAt($frozenAt)
    {
        $this->frozenAt = $frozenAt;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
