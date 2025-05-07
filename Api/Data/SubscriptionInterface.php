<?php

namespace Drd\Subscribe\Api\Data;

interface SubscriptionInterface
{
    /**
     * Get order ID
     *
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Get order Item ID
     *
     * @return int
     */
    public function getOrderItemId(): int;

    /**
     * Set order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId(int $orderId);

    /**
     * Set order ID
     *
     * @param int $orderItemId
     * @return $this
     */
    public function setOrderItemId(int $orderItemId);

    /**
     * Get SKU
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set SKU
     *
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku);

    /**
     * Get recurrence
     *
     * @return string
     */
    public function getRecurrence(): string;

    /**
     * Set recurrence
     *
     * @param string $recurrence
     * @return $this
     */
    public function setRecurrence(string $recurrence);

    /**
     * Get next order date
     *
     * @return string
     */
    public function getNextOrderDate(): string;

    /**
     * Set next order date
     *
     * @param string $nextOrderDate
     * @return $this
     */
    public function setNextOrderDate(string $nextOrderDate);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status);

    /**
     * Get SkipNextOrder flag
     *
     * @return bool
     */
    public function getSkipNextOrder(): bool;

    /**
     * Set SkipNextOrder flag
     *
     * @param bool $skip
     * @return $this
     */
    public function setSkipNextOrder(bool $skip);

    /**
     * Get create date
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set create date
     *
     * @param string $date
     * @return $this
     */
    public function setCreatedAt(string $date);

    /**
     * Get create date
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set create date
     *
     * @param string $date
     * @return $this
     */
    public function setUpdatedAt(string $date);
}
