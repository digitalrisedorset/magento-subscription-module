<?php

namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class PaymentHandler
{
    /**
     * @param Order $order
     * @param Quote $quote
     * @return void
     */
    public function assignPaymentToQuote(Order $order, Quote $quote)
    {
        $originalPaymentMethod = $order->getPayment()->getMethod(); // e.g. 'checkmo', 'stripe_payments', etc.
        $quotePayment = $quote->getPayment();
        $quotePayment->setMethod($originalPaymentMethod);
        $quote->setPayment($quotePayment);
    }
}
