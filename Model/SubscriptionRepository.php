<?php

namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;
use Drd\Subscribe\Model\ResourceModel\Subscription\CollectionFactory as SubscriptionCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    private SubscriptionFactory $subscriptionFactory;
    private SubscriptionResource $subscriptionResource;
    private SubscriptionCollectionFactory $subscriptionCollectionFactory;

    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        SubscriptionCollectionFactory $subscriptionCollectionFactory
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
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
        $this->subscriptionResource->save($subscription);
        return $subscription;
    }

    public function delete(SubscriptionInterface $subscription): bool
    {
        $this->subscriptionResource->delete($subscription);
        return true;
    }
}
