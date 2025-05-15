<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Drd\Subscribe\Model\ResourceModel\Subscription as SubscriptionResource;
use Drd\Subscribe\Model\ResourceModel\Subscription\CollectionFactory as SubscriptionCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Drd\Subscribe\Model\ProductSubscription\NextSubscriptionDateCalculator;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    private SubscriptionFactory $subscriptionFactory;
    private SubscriptionResource $subscriptionResource;
    private SubscriptionCollectionFactory $subscriptionCollectionFactory;
    private NextSubscriptionDateCalculator $nextSubscriptionDateCalculator;
    private TimezoneInterface $timezone;

    /**
     * @param SubscriptionFactory $subscriptionFactory
     * @param SubscriptionResource $subscriptionResource
     * @param SubscriptionCollectionFactory $subscriptionCollectionFactory
     * @param NextSubscriptionDateCalculator $nextSubscriptionDateCalculator
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        SubscriptionFactory $subscriptionFactory,
        SubscriptionResource $subscriptionResource,
        SubscriptionCollectionFactory $subscriptionCollectionFactory,
        NextSubscriptionDateCalculator $nextSubscriptionDateCalculator,
        TimezoneInterface $timezone
    ) {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionResource = $subscriptionResource;
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->nextSubscriptionDateCalculator = $nextSubscriptionDateCalculator;
        $this->timezone = $timezone;
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

    public function getDueSubscriptions(): array
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone($this->timezone->getConfigTimezone()));

        $collection = $this->subscriptionCollectionFactory->create();
        $collection->addFieldToFilter('next_order_date', ['lteq' => $now->format(DateTime::DATETIME_PHP_FORMAT)]);
        $collection->addFieldToFilter('status', ['eq' => 'active']);
        return $collection->getItems();
    }
}
