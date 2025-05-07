<?php

namespace Drd\Subscribe\Model;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionTranslator;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\Quote\Item;

class QuoteItemOptionsExtractor
{
    protected SerializerInterface $serializer;
    private SubscriptionTranslator $subscriptionTranslator;

    /**
     * @param SerializerInterface $serializer
     * @param SubscriptionTranslator $subscriptionTranslator
     */
    public function __construct(
        SerializerInterface $serializer,
        SubscriptionTranslator $subscriptionTranslator
    ) {
        $this->serializer = $serializer;
        $this->subscriptionTranslator = $subscriptionTranslator;
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

        if (!empty($options['recurrence'])) {
            $result[] = [
                'label' => __('Recurrence'),
                'value' => $options['recurrence'],
                'value_display' => $this->subscriptionTranslator->getFormatFrequency($options['recurrence'])
            ];
        }

        return !empty($result) ? $result : null;
    }
}
