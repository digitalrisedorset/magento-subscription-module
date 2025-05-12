<?php

namespace Drd\Subscribe\ViewModel;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionTranslator;
use Magento\Checkout\Block\Cart\Item\Renderer;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Drd\Subscribe\Model\Config\Data as PlanConfigProvider;
use Magento\Quote\Model\Quote\Item;

class CartItemSubscriptionInfo implements ArgumentInterface
{
    private PlanConfigProvider $planProvider;
    private SubscriptionTranslator $subscriptionTranslator;

    /**
     * @param PlanConfigProvider $planProvider
     * @param SubscriptionTranslator $subscriptionTranslator
     */
    public function __construct(
        PlanConfigProvider $planProvider,
        SubscriptionTranslator $subscriptionTranslator
    ) {
        $this->planProvider = $planProvider;
        $this->subscriptionTranslator = $subscriptionTranslator;
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
        /** @var \Magento\Framework\DataObject $plan */
        $plan = $this->getPlan($item);
        /** @phpstan-ignore-next-line */
        if (!$plan || !$plan->getDiscount()) {
            return null;
        }

        /** @phpstan-ignore-next-line */
        return __('Subscription Discount: %1%', $plan->getDiscount());
    }

    public function getFormatedOptionValue(Renderer $block, $_option)
    {
        if ($_option['label'] && (string) $_option['label'] === 'Recurrence') {
            $_option['value'] = $this->subscriptionTranslator->getFormatFrequency($_option['value']);
        }

        return $block->getFormatedOptionValue($_option);
    }
}
