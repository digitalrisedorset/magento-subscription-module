<?php

namespace Drd\Subscribe\Model;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\Quote\Item;

class QuoteItemOptionsExtractor
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getQuoteItemCustomOption(Item $item): ?array
    {
        $buyRequestOption = $item->getOptionByCode('info_buyRequest');

        if (!$buyRequestOption) {
            return null;
        }

        try {
            $buyRequest = $this->serializer->unserialize($buyRequestOption->getValue());
        } catch (\Exception $e) {
            return null;
        }

        if (!isset($buyRequest['options']) || !is_array($buyRequest['options'])) {
            return null;
        }

        $options = $buyRequest['options'];
        $result = [];

        if (!empty($options['subscription'])) {
            $result[] = [
                'label' => __('Subscription'),
                'value' => __('Yes')
            ];
        }

        if (!empty($options['recurrence'])) {
            $result[] = [
                'label' => __('Recurrence'),
                'value' => ucfirst($options['recurrence'])
            ];
        }

        return !empty($result) ? $result : null;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isItemSubscribed(Item $item): bool
    {
        $options = $item->getProductOptions();

        if (!isset($options['additional_options']) || !is_array($options['additional_options'])) {
            return false;
        }

        foreach ($options['additional_options'] as $option) {
            if (
                strtolower($option['label']) === 'subscription' &&
                strtolower($option['value']) === 'yes'
            ) {
                return true;
            }
        }

        return false;
    }
}
