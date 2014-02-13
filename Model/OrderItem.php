<?php

namespace Kek\ShopBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class OrderItem
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    protected $product;

    // protected $lastQuantity;

    public function getTotal()
    {
        // not sure why this was necessary hehehe
        // $quantity = $this->lastQuantity ?: $this->quantity;

        return $this->quantity * $this->product->getCurrentPrice();
    }

    public function getLastQuantity()
    {
        return $this->lastQuantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        if ($quantity < 1) {
            $quantity = 1;
        }

        if ($quantity > 999) {
            $quantity = 999;
        }

        // $this->lastQuantity = $this->quantity;

        $this->quantity = $quantity;

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

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function setCart($cart)
    {
        $this->cart = $cart;

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
