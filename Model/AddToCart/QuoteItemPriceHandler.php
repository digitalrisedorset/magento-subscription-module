<?php

namespace Drd\Subscribe\Model\AddToCart;

use Drd\Subscribe\Model\Config\Data as PlanConfigProvider;

class QuoteItemPriceHandler
{
    private PlanConfigProvider $planConfigProvider;

    public function __construct(PlanConfigProvider $planConfigProvider)
    {
        $this->planConfigProvider = $planConfigProvider;
    }

    public function addSubscriptionDiscount($quoteItem, $product, $requestInfo)
    {
        $buyRequest = $requestInfo instanceof \Magento\Framework\DataObject
            ? $requestInfo
            : new \Magento\Framework\DataObject($requestInfo);

        $planId = $buyRequest->getData('options')['subscription_plan_id'] ?? null;
        if (!$planId) {
            return null;
        }

        $plan = $this->planConfigProvider->getById($planId);
        if (!$plan || empty($plan['discount'])) {
            return null;
        }

        $discount = (float) $plan['discount'];
        $originalPrice = $product->getFinalPrice();
        $discountedPrice = $originalPrice * (1 - $discount / 100);

        // ✅ Apply custom price
        $quoteItem->setCustomPrice($discountedPrice);
        $quoteItem->setOriginalCustomPrice($discountedPrice);
        $quoteItem->getProduct()->setIsSuperMode(true);

        $quoteItem->addOption([
            'product_id' => $product->getId(),
            'code' => 'subscription_plan_id',
            'value' => $planId
        ]);
    }
}
