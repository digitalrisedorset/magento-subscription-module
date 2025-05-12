<?php

namespace Drd\Subscribe\Model\ReorderServiceProcessor;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Api\PaymentTransactionHandlerInterface;
use Magento\Sales\Model\Order;

class PaymentTransactionHandler implements PaymentTransactionHandlerInterface
{
    /**
     * @param SubscriptionInterface $subscription
     * @param Order $order
     * @return string
     */
    public function processTransaction(SubscriptionInterface $subscription, Order $order): string
    {
        // overridden
        return '';
    }
}
