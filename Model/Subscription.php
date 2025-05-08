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

    public function getOriginalOrderId(): int
    {
        return (int) $this->getData(self::FIELD_ORIGINAL_ORDER_ID);
    }

    public function setOriginalOrderId(int $orderId): self
    {
        return $this->setData(self::FIELD_ORIGINAL_ORDER_ID, $orderId);
    }

    public function getSku(): string
    {
        return (string) $this->getData(self::FIELD_SKU);
    }

    public function setSku(string $sku): self
    {
        return $this->setData(self::FIELD_SKU, $sku);
    }

    public function getRecurrence(): string
    {
        return (string) $this->getData(self::FIELD_RECURRENCE);
    }

    public function setRecurrence(string $recurrence): self
    {
        return $this->setData(self::FIELD_RECURRENCE, $recurrence);
    }

    public function getNextOrderDate(): string
    {
        return (string) $this->getData(self::FIELD_NEXT_ORDER_DATE);
    }

    public function setNextOrderDate(string $nextOrderDate): self
    {
        return $this->setData(self::FIELD_NEXT_ORDER_DATE, $nextOrderDate);
    }

    public function getStatus(): string
    {
        return (string) $this->getData(self::FIELD_STATUS);
    }

    public function setStatus(string $status): self
    {
        return $this->setData(self::FIELD_STATUS, $status);
    }

    public function getOrderItemId(): int
    {
        return (int) $this->getData(self::FIELD_ORDER_ITEM_ID);
    }

    public function setOrderItemId(int $orderItemId): self
    {
        return $this->setData(self::FIELD_ORDER_ITEM_ID, $orderItemId);
    }

    public function getCreatedAt(): string
    {
        return $this->getData(self::FIELD_CREATED_AT);
    }

    public function setCreatedAt(string $date): self
    {
        return $this->setData(self::FIELD_CREATED_AT, $date);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData(self::FIELD_UPDATED_AT);
    }

    public function setUpdatedAt(string $date): self
    {
        return $this->setData(self::FIELD_UPDATED_AT, $date);
    }

    public function getSkipNextOrder(): bool
    {
        return (bool) $this->getData(self::FIELD_SKIP_NEXT_ORDER);
    }

    public function setSkipNextOrder(bool $skip): self
    {
        return $this->setData(self::FIELD_SKIP_NEXT_ORDER, $skip);
    }

    public function getPaymentToken(): string
    {
        return (string)$this->getData(self::FIELD_PAYMENT_TOKEN);
    }

    public function setPaymentToken(string $token)
    {
        return $this->setData(self::FIELD_PAYMENT_TOKEN, $token);
    }

}
