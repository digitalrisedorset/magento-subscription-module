<?php

namespace Drd\Subscribe\ViewModel;

use Drd\Subscribe\Model\ProductSubscription\SubscriptionConfigReader;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductSubscription implements ArgumentInterface
{
    private SubscriptionConfigReader $subscriptionConfigReader;
    private $productSubscriptionConfig;

    /**
     * @param SubscriptionConfigReader $subscriptionConfigReader
     */
    public function __construct(
        SubscriptionConfigReader $subscriptionConfigReader
    ) {
        $this->subscriptionConfigReader = $subscriptionConfigReader;
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

    public function getProductId(): int
    {
        return (int) $this->request->getParam('id');
    }

    public function getSubmitUrl(): string
    {
        return $this->urlBuilder->getUrl('subscribe/index/submit');
    }

    public function getProductSku(): ?string
    {
        try {
            $product = $this->productRepository->getById($this->getProductId());
            return $product->getSku();
        } catch (\Exception $e) {
            return null;
        }
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
            $label = sprintf('%s %d%% Off', $label, $maxDiscount);
        }

        if (count($plans) > 1) {
            $label = sprintf('%s up to %d%% Off', $label, $maxDiscount);
        }

        return (string)$label;
    }
}
