<?php

namespace Drd\Subscribe\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Drd\Subscribe\Model\QuoteItemOptionsExtractor;
use Magento\Framework\Serialize\SerializerInterface;

class ConvertItemOptionsPlugin
{
    private QuoteItemOptionsExtractor $optionsExtractor;
    private SerializerInterface $serializer;

    public function __construct(
        QuoteItemOptionsExtractor $optionsExtractor,
        SerializerInterface $serializer
    ) {
        $this->optionsExtractor = $optionsExtractor;
        $this->serializer = $serializer;
    }

    /**
     * Copy additional options from quote item to order item
     *
     * @param ToOrderItem $subject
     * @param OrderItem $result
     * @param $item
     * @param array $data
     * @return OrderItem
     */
    public function afterConvert(
        ToOrderItem $subject,
        \Magento\Sales\Api\Data\OrderItemInterface $result,
        $item,
        $additional = []
    ) {
        $customOptions = $this->optionsExtractor->getQuoteItemCustomOption($item);

        if ($customOptions === null) {
            return $result;
        }

        $existingOptions = $result->getProductOptionByCode('additional_options') ?? [];
        $mergedOptions = array_merge($existingOptions, $customOptions);

        $orderItemOptions = $result->getProductOptions() ?? [];
        $orderItemOptions['additional_options'] = $mergedOptions;

        $result->setProductOptions($orderItemOptions);

        return $result;
    }
}

