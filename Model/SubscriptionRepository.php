<?php

namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;
use Drd\Subscribe\Model\ResourceModel\Subscription\CollectionFactory as SubscriptionCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Drd\Subscribe\Model\ProductSubscription\NextSubscriptionDateCalculator;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    private SubscriptionFactory $subscriptionFactory;
    private SubscriptionResource $subscriptionResource;
    private SubscriptionCollectionFactory $subscriptionCollectionFactory;
    private NextSubscriptionDateCalculator $nextSubscriptionDateCalculator;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $subscriptionResource
     * @param SubscriptionCollectionFactory $subscriptionCollectionFactory
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        SubscriptionCollectionFactory $subscriptionCollectionFactory,
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
    }

    public function getByOrderItemId(int $orderItemId): SubscriptionInterface
    {
        $collection = $this->subscriptionCollectionFactory->create()
            ->addFieldToFilter('order_item_id', $orderItemId)
            ->setPageSize(1);

        $subscription = $collection->getFirstItem();

        if (!$subscription || !$subscription->getId()) {
            throw new NoSuchEntityException(
                __('No subscription found for order item ID %1', $orderItemId)
            );
        }

        return $subscription;
    }

    public function save(SubscriptionInterface $subscription): SubscriptionInterface
    {
        if ($subscription->getStatus()) {
            $subscription->setStatus(strtolower($subscription->getStatus()));
        }

        if (!$subscription->getNextOrderDate() && $subscription->getRecurrence()) {
            $subscription->setNextOrderDate(
                $this->nextSubscriptionDateCalculator->calculateNextDate($subscription)
            );
        }

        $this->subscriptionResource->save($subscription);

        return $subscription;
    }

    public function delete(SubscriptionInterface $subscription): bool
    {
        $this->subscriptionResource->delete($subscription);
        return true;
    }

    public function getDueSubscriptions(\DateTimeInterface $date): array
    {
        $collection = $this->subscriptionCollectionFactory->create();
        $collection->addFieldToFilter('next_order_date', ['lteq' => $date->format('Y-m-d')]);
        $collection->addFieldToFilter('status', ['eq' => 'active']);
        return $collection->getItems();
    }
}
