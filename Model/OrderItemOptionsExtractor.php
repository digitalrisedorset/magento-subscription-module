<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model;

use Drd\Subscribe\Api\Data\SubscriptionInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Model\Order\Item;

class OrderItemOptionsExtractor
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Item $item
     * @return SubscriptionInterface
     */
    public function getOrderItemCustomOption(Item $item): ?array
    {
        $options = $item->getProductOptions();

        if (empty($options['additional_options'])) {
            return null;
        }

        $result = [];

        foreach ($options['additional_options'] as $option) {
            if (strtolower($option['label']) === 'subscription') {
                $result['subscription'] = $option['value'];
            }
            if (strtolower($option['label']) === 'recurrence') {
                $result['recurrence'] = $option['value'];
            }
        }

        return !empty($result) ? $result : null;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isItemSubscribed(Item $item): bool
    {
        $options = $item->getExtensionAttributes()->getSubscription();

        return $options !== null;
    }
}
