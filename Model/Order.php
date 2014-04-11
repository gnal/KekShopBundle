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

    protected $addresses;

    protected $items;

    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    public function __construct()
    {
        $this->items = new ArrayCollection;
        $this->addresses = new ArrayCollection;
        $this->status = 1;
    }

    public function hasItemForProduct($product)
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                return $item;
            }
        }

        return false;
    }

    public function removeItemById($id)
    {
        foreach ($this->items as $key => $item) {
            if ($item->getId() === intval($id)) {
                $this->items->remove($key);
                break;
            }
        }
    }

    public function getItemById($id)
    {
        foreach ($this->items as $key => $item) {
            if ($item->getId() === intval($id)) {
                return $item;
            }
        }
    }

    public function getItemsTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    public function getNonTaxableItemsTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            if (!$item->getProduct()->getTaxable()) {
                $total += $item->getTotal();
            }
        }

        return $total;
    }

    public function getTaxableItemsTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            if ($item->getProduct()->getTaxable()) {
                $total += $item->getTotal();
            }
        }

        return $total;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;

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
