<?php

namespace Kek\ShopBundle\Calculation;

use JMS\DiExtraBundle\Annotation as DI;

use Kek\ShopBundle\Model\Order;
use Kek\ShopBundle\Model\Tax;

/**
 * @DI\Service("kek_shop.calculator")
 */
class Calculator
{
    public function calculateTotalTax(Order $order, Tax $tax)
    {
        $totalTaxable = $order->getTaxableItemsTotal();
        $rate = $tax->getRate() / 100;

        return $totalTaxable * $rate;
    }

    public function calculateTotalTaxes(Order $order, $taxes)
    {
        $total = 0;
        foreach ($taxes as $tax) {
            $total += $this->calculateTotalTax($order, $tax);
        }

        return $total;
    }

    public function calculateOrderTotalWithTaxes(Order $order, $taxes)
    {
        $totalTaxes = $this->calculateTotalTaxes($order, $taxes);
        $orderTotal = $order->getItemsTotal();

        return $totalTaxes + $orderTotal;
    }
}
