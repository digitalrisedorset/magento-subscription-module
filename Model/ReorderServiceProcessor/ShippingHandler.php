<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class ShippingHandler
{
    /**
     * @param Order $order
     * @param Quote $quote
     * @return void
     */
    public function assignShippingToQuote(Order $order, Quote $quote)
    {
        $originalShippingMethod = $order->getShippingMethod(); // e.g. "flatrate_flatrate"
        $originalShippingDescription = $order->getShippingDescription();

        $shippingAddress = $quote->getShippingAddress();

        // Required: set the method
        $shippingAddress->setShippingMethod($originalShippingMethod);
        // Optional: carry over the label/description
        $shippingAddress->setShippingDescription($originalShippingDescription);

        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        $quote->setShippingAddress($shippingAddress);
    }
}
