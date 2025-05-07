<?php

namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;
use Drd\Subscribe\Model\SubscriptionFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item;

class SubscriptionPersister
{
    private SubscriptionFactory $subscriptionFactory;
    private SubscriptionResource $subscriptionResource;
    private NextSubscriptionDateCalculator $nextSubscriptionDateCalculator;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $subscriptionResource
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
    }

    /**
     * @param Order $order
     * @param Item $orderItem
     * @param SubscriptionInterface|array $optionSubscription
     * @return void
     */
    public function createProductSubscription(Order $order, Item $orderItem, SubscriptionInterface|array $optionSubscription)
    {
        $subscription = $this->subscriptionFactory->create();
        $subscription->setData([
            'order_id' => $order->getId(),
            'order_item_id' => $orderItem->getId(),
            'sku' => $orderItem->getSku(),
            'recurrence' => $optionSubscription['recurrence'],
        ]);

        $subscription->save();
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
