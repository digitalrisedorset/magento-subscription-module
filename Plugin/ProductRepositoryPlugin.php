<?php

namespace Drd\Subscribe\Plugin;

use Drd\Subscribe\Api\Data\ProductSuscriptionConfigInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

class ProductRepositoryPlugin
{
    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;
    /**
     * @var SubscriptionConfigReader
     */
    private $subscriptionConfigReader;

    /**
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     * @param SubscriptionConfigReader $subscriptionConfigReader
     */
    public function __construct(
        ExtensionAttributesFactory $extensionAttributesFactory,
        SubscriptionConfigReader $subscriptionConfigReader
    ) {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
        $this->subscriptionConfigReader = $subscriptionConfigReader;
    }

    public function afterGet(
        ProductRepositoryInterface $subject,
        ProductInterface $result
    ) {
        $this->attachSubscriptionConfig($result);
        return $result;
    }

    public function afterGetById(
        ProductRepositoryInterface $subject,
        ProductInterface $result
    ) {
        $this->attachSubscriptionConfig($result);
        return $result;
    }

    /**
     * @param ProductInterface $product
     * @return void
     */
    private function attachSubscriptionConfig(ProductInterface $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(ProductInterface::class);
        }

        $config = $this->subscriptionConfigReader->getProductSubscriptionConfig($product);
        $extensionAttributes->setSubscriptionConfig($config);
        $product->setExtensionAttributes($extensionAttributes);
    }
}
