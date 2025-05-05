<?php

namespace Drd\Subscribe\ViewModel;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Drd\Subscribe\Model\Config\Data as PlanConfigProvider;
use Magento\Quote\Model\Quote\Item;

class CartItemSubscriptionInfo implements ArgumentInterface
{
    private PlanConfigProvider $planProvider;

    public function __construct(PlanConfigProvider $planProvider)
    {
        $this->planProvider = $planProvider;
    }

    /**
     * @param Item $item
     * @return DataObject|null
     */
    public function getPlan(Item $item): ?DataObject
    {
        $planId = $item->getOptionByCode('subscription_plan_id')?->getValue();
        return $planId ? $this->planProvider->getById($planId) : null;
    }

    /**
     * @param Item $item
     * @return string|null
     */
    public function getDiscountLabel(Item $item): ?string
    {
        $plan = $this->getPlan($item);
        if (!$plan || !$plan->getDiscount()) {
            return null;
        }

        return __('Subscription Discount: %1%', $plan->getDiscount());

    }
}
