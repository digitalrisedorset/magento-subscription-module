<?php

namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
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

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $subscriptionResource
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator,
        SubscriptionRepositoryInterface $subscriptionRepository
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
        $this->subscriptionRepository = $subscriptionRepository;
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
        $subscription->setOriginalOrderId($order->getId());
        $subscription->setRecurrence($optionSubscription['recurrence']);
        $subscription->setOrderItemId($orderItem->getId());
        $subscription->setSku($orderItem->getSku());
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
        $subscription->save();
    }
}
