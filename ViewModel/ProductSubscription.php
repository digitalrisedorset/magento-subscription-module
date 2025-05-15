<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\ViewModel;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionConfigReader;
use Drd\Subscribe\Model\ProductSubscription\SubscriptionTranslator;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductSubscription implements ArgumentInterface
{
    private SubscriptionConfigReader $subscriptionConfigReader;
    private $productSubscriptionConfig;
    private SubscriptionTranslator $subscriptionTranslator;

    /**
     * @param SubscriptionConfigReader $subscriptionConfigReader
     */
    public function __construct(
        SubscriptionConfigReader $subscriptionConfigReader,
        SubscriptionTranslator $subscriptionTranslator
    ) {
        $this->subscriptionConfigReader = $subscriptionConfigReader;
        $this->subscriptionTranslator = $subscriptionTranslator;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isProductSubscriptionEnabled(ProductInterface $product): bool
    {
        $this->productSubscriptionConfig = $this->subscriptionConfigReader->getProductSubscriptionConfig($product);

        return $this->productSubscriptionConfig->getIsSubscribable();
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    public function getAssignedPlans(ProductInterface $product): array
    {
        $this->productSubscriptionConfig = $this->subscriptionConfigReader->getProductSubscriptionConfig($product);

        return $this->productSubscriptionConfig->getAssignedPlans();
    }

    public function getMaxSubscriptionDiscount(ProductInterface $product): ?string
    {
        $plans = $this->getAssignedPlans($product);

        $maxDiscount = 0;
        foreach ($plans as $plan) {
            $maxDiscount = max($maxDiscount, (float) $plan->getDiscount());
        }

        return $maxDiscount > 0 ? $maxDiscount . '%' : null;
    }

    public function getSubscribeLabel(ProductInterface $product): string
    {
        $label = __('Subscribe & Save');
        $plans = $this->getAssignedPlans($product);
        $maxDiscount = $this->getMaxSubscriptionDiscount($product);

        if (count($plans) === 1) {
            $plan = current($plans);
            $label = sprintf(
                '%s %s %d%% Off',
                $label,
                $this->subscriptionTranslator->getFormatFrequency($plan->getFrequency()),
                $maxDiscount
            );
        }

        if (count($plans) > 1) {
            $label = sprintf('%s up to %d%% Off', $label, $maxDiscount);
        }

        return (string)$label;
    }
}
