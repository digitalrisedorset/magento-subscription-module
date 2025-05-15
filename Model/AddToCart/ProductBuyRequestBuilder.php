<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\AddToCart;

use Drd\Subscribe\Model\Config\Data as SubscriptionPlanProvider;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class ProductBuyRequestBuilder
{
    private SerializerInterface $serializer;
    private SubscriptionPlanProvider $subscriptionPlanProvider;

    /**
     * @param SubscriptionPlanProvider $subscriptionPlanProvider
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SubscriptionPlanProvider $subscriptionPlanProvider,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->subscriptionPlanProvider = $subscriptionPlanProvider;
    }

    public function getProductBuyRequest(ProductInterface $product, $subscriptionPlanId, $superAttributes = null): DataObject
    {
        $plan = $this->subscriptionPlanProvider->getById($subscriptionPlanId);

        if (!$plan) {
            throw new LocalizedException(
                __('The subscription plan is missing.')
            );
        }

        $buyRequest = [
            'product' => $product->getId(),
            'qty' => 1,
            'options' => [
                'subscription_plan_id' => $plan->getId(),
                'recurrence' => $plan->getFrequency(),
            ]
        ];

        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            if (!$superAttributes) {
                throw new LocalizedException(
                    __('Missing super attributes for configurable product.')
                );
            }

            $buyRequest['super_attribute'] = $this->serializer->unserialize($superAttributes);
        }

        return new DataObject($buyRequest);
    }
}
