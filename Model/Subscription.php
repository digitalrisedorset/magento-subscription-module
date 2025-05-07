<?php

namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Magento\Framework\Model\AbstractModel;

class Subscription extends AbstractModel implements SubscriptionInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Subscription::class);
    }

    public function getOrderId(): int
    {
        return (int) $this->getData('order_id');
    }

    public function getSku(): string
    {
        return (string) $this->getData('sku');
    }

    public function getRecurrence(): string
    {
        return (string) $this->getData('recurrence');
    }

    public function getNextOrderDate(): string
    {
        return (string) $this->getData('next_order_date');
    }

    public function getStatus(): string
    {
        return (string) $this->getData('status');
    }

    public function setOrderId(int $orderId)
    {
        return $this->setData('order_id', $orderId);
    }

    public function setSku(string $sku)
    {
        return $this->setData('sku', $sku);
    }

    public function setRecurrence(string $recurrence)
    {
        return $this->setData('recurrence', $recurrence);
    }

    public function setNextOrderDate(string $nextOrderDate)
    {
        return $this->setData('next_order_date', $nextOrderDate);
    }

    public function setStatus(string $status)
    {
        return $this->setData('status', $status);
    }

    public function getOrderItemId(): int
    {
        return (int) $this->getData('order_item_id');
    }

    public function setOrderItemId(int $orderItemId)
    {
        return $this->setData('order_item_id', $orderItemId);
    }

    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    public function setCreatedAt(string $date)
    {
        return $this->setData('created_at', $date);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData('updated_at');
    }

    public function setUpdatedAt(string $date)
    {
        return $this->setData('updated_at', $date);
    }

    public function getSkipNextOrder(): bool
    {
        return $this->getData('skip_next_order');
    }

    public function setSkipNextOrder(bool $skip)
    {
        return $this->setData('skip_next_order', $skip);
    }
}
