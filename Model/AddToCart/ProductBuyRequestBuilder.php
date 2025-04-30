<?php

namespace Drd\Subscribe\Model\AddToCart;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class ProductBuyRequestBuilder
{
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public function getProductBuyRequest(ProductInterface $product, $recurrence, $subscription, $superAttributes = null): DataObject
    {
        $buyRequest = [
            'product' => $product->getId(),
            'qty' => 1,
            'options' => [
                'subscription' => $subscription,
                'recurrence' => $recurrence
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
