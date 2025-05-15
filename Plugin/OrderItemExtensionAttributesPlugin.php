<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Plugin;

use Drd\Subscribe\Api\SubscriptionRepositoryInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Drd\Subscribe\Api\Data\SubscriptionInterfaceFactory;
use Magento\Sales\Api\Data\OrderItemInterface;

class OrderItemExtensionAttributesPlugin
{
    private OrderItemExtensionFactory $extensionFactory;
    private SubscriptionInterfaceFactory $subscriptionFactory;
    private SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @param OrderItemExtensionFactory $extensionFactory
     * @param SubscriptionInterfaceFactory $subscriptionFactory
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(
        OrderItemExtensionFactory $extensionFactory,
        SubscriptionInterfaceFactory $subscriptionFactory,
        SubscriptionRepositoryInterface $subscriptionRepository
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function afterGetExtensionAttributes(OrderItemInterface $item, $extensionAttributes)
    {
        // Initialize extension attributes if they are null
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->extensionFactory->create();
        }

        try {
            $subscription = $this->subscriptionRepository->getByOrderItemId($item->getItemId());
            if ($subscription) {
                $extensionAttributes->setSubscription($subscription);
                $item->setExtensionAttributes($extensionAttributes);
            }
        } catch (\Exception $e) {

        }

        return $extensionAttributes;
    }
}
