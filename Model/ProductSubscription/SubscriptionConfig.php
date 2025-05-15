<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Api\Data\ProductSuscriptionConfigInterface;
use Magento\Framework\DataObject;

class SubscriptionConfig extends DataObject implements ProductSuscriptionConfigInterface
{
    public function getIsSubscribable(): bool
    {
        return (bool) $this->getData('is_subscribable');
    }

    public function setIsSubscribable(bool $value): ProductSuscriptionConfigInterface
    {
        return $this->setData('is_subscribable', $value);
    }

    public function getFrequencies(): array
    {
        return (array) $this->getData('frequencies');
    }

    public function setFrequencies(array $value): ProductSuscriptionConfigInterface
    {
        return $this->setData('frequencies', $value);
    }

    public function getDefault(): string
    {
        return (string) $this->getData('default');
    }

    public function setDefault(string $value): ProductSuscriptionConfigInterface
    {
        return $this->setData('default', $value);
    }

    public function setAssignedPlans(array $value): ProductSuscriptionConfigInterface
    {
        return $this->setData('assigned_plans', $value);
    }

    public function getAssignedPlans(): array
    {
        return (array) $this->getData('assigned_plans');
    }
}
