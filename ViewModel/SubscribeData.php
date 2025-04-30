<?php
namespace Drd\Subscribe\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class SubscribeData implements ArgumentInterface
{
    private UrlInterface $urlBuilder;
    private RequestInterface $request;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        UrlInterface $urlBuilder,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->productRepository = $productRepository;
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
}
