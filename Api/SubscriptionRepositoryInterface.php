<?php

namespace Drd\Subscribe\Api;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface SubscriptionRepositoryInterface
 *
 * Repository for accessing subscription records.
 */
interface SubscriptionRepositoryInterface
{
    /**
     * Get a subscription record by order item ID.
     *
     * @param int $orderItemId
     * @return \Drd\Subscribe\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOrderItemId(int $orderItemId): SubscriptionInterface;

    /**
     * Save a subscription.
     *
     * @param \Drd\Subscribe\Api\Data\SubscriptionInterface $subscription
     * @return \Drd\Subscribe\Api\Data\SubscriptionInterface
     */
    public function save(SubscriptionInterface $subscription): SubscriptionInterface;

    /**
     * Delete a subscription.
     *
     * @param \Drd\Subscribe\Api\Data\SubscriptionInterface $subscription
     * @return bool
     */
    public function delete(SubscriptionInterface $subscription): bool;

    public function getDueSubscriptions(): array;
}
