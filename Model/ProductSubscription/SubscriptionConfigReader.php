<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

use Drd\Subscribe\Api\Data\ProductSuscriptionConfigInterfaceFactory;
use Drd\Subscribe\Constants;
use Drd\Subscribe\Model\Config\Data as SubscriptionPlanProvider;
use Magento\Catalog\Api\Data\ProductInterface;

class SubscriptionConfigReader
{
    /**
     * @var SubscriptionOptionsInterfaceFactory
     */
    private $subscriptionOptionsFactory;
    private SubscriptionPlanProvider $subscriptionPlanProvider;

    /**
     * @param ProductSuscriptionConfigInterfaceFactory $subscriptionOptionsFactory
     */
    public function __construct(
        ProductSuscriptionConfigInterfaceFactory $subscriptionOptionsFactory,
        SubscriptionPlanProvider $subscriptionPlanProvider
    ) {
        $this->subscriptionOptionsFactory = $subscriptionOptionsFactory;
        $this->subscriptionPlanProvider = $subscriptionPlanProvider;
    }

    public function getProductSubscriptionConfig(ProductInterface $product)
    {
        /** @var ProductSuscriptionConfigInterface $config */
        $config = $this->subscriptionOptionsFactory->create();
        $config->setIsSubscribable($this->getProductSubscriptionStatus($product));
        $config->setAssignedPlans($this->getProductSubscriptionPlans($product));
        $config->setDefault("monthly");

        return $config;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    private function getProductSubscriptionStatus(ProductInterface $product): bool
    {
        $attribute = $product->getCustomAttribute(Constants::IS_SUBSCRIBE);
        $isSubscribable = $attribute ? (bool) $attribute->getValue() : false;

        return $isSubscribable;
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    private function getProductSubscriptionPlans(ProductInterface $product): array
    {
        $planIds = $product->getData(Constants::SUBSCRIPTION_PLAN_IDS);
        $planIds = is_array($planIds) ? $planIds : explode(',', (string) $planIds);

        $plans = [];
        foreach ($planIds as $id) {
            $plan = $this->subscriptionPlanProvider->getById($id);
            if ($plan) {
                $plans[] = $plan;
            }
        }

        return $plans;
    }
}
