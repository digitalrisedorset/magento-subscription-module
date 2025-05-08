<?php

namespace Drd\Subscribe\Api;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Magento\Sales\Model\Order;

interface PaymentTransactionHandlerInterface
{
    public function processTransaction(SubscriptionInterface $subscription, Order $order): string;
}
