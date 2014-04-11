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
    public function calculateTotalTax(Order $order, $taxRate)
    {
        $totalTaxable = $order->getTaxableItemsTotal();

        return $totalTaxable * ($taxRate / 100);
    }

    public function calculateTotalTaxes(Order $order, $taxRates)
    {
        $total = 0;
        foreach ($taxRates as $taxRate) {
            $total += $this->calculateTotalTax($order, $taxRate);
        }

        return $total;
    }

    public function calculateOrderTotalWithTaxes(Order $order, $taxes)
    {
        $taxRates = [];
        foreach ($taxes as $tax) {
            if (is_object($tax)) {
                $taxRates[] = $tax->getRate();
            } else {
                $taxRates[] = $tax['rate'];
            }
        }

        $totalTaxes = $this->calculateTotalTaxes($order, $taxRates);
        $orderTotal = $order->getItemsTotal();

        return $totalTaxes + $orderTotal;
    }
}
