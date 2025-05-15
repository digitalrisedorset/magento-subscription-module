<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
use Drd\Subscribe\Model\Payment\TokenReader;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;
use Drd\Subscribe\Model\SubscriptionFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class SubscriptionPersister
{
    private SubscriptionFactory $subscriptionFactory;
    private SubscriptionResource $subscriptionResource;
    private NextSubscriptionDateCalculator $nextSubscriptionDateCalculator;
    private SubscriptionRepositoryInterface $subscriptionRepository;
    private TokenReader $tokenReader;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $subscriptionResource
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     * @param TokenReader $tokenReader
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator,
        SubscriptionRepositoryInterface $subscriptionRepository,
        TokenReader $tokenReader
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->tokenReader = $tokenReader;
    }

    /**
     * @param Order $order
     * @param Item $orderItem
     * @param SubscriptionInterface|array $optionSubscription
     * @return void
     */
    public function createProductSubscription(Order $order, Item $orderItem, SubscriptionInterface|array $optionSubscription)
    {
        /** @var \Drd\Subscribe\Api\Data\SubscriptionInterface $subscription */
        $subscription = $this->subscriptionFactory->create();
        $subscription->setOriginalOrderId((int) $order->getId());
        $subscription->setRecurrence($optionSubscription['recurrence']);
        $subscription->setOrderItemId((int) $orderItem->getId());
        $subscription->setSku($orderItem->getSku());
        $subscription->setPaymentToken($this->tokenReader->getPaymentToken($order));
        $this->subscriptionRepository->save($subscription);
    }

    public function skipNextSubscriptionOrder(int $subscriptionId)
    {
        /** @var \Drd\Subscribe\Api\Data\SubscriptionInterface $subscription */
        $subscription = $this->subscriptionFactory->create();
        $this->subscriptionResource->load($subscription, $subscriptionId);
        $subscription->setSkipNextOrder(true);
        $subscription->setNextOrderDate(
            $this->nextSubscriptionDateCalculator->calculateNextDate($subscription)
        );
        $this->subscriptionRepository->save($subscription);
    }
}
