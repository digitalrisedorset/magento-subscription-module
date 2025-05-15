<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Drd\Subscribe\Controller\Index;

use Drd\Subscribe\Model\AddToCart\ProductBuyRequestBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;

class Submit extends Action
{
    protected $productRepository;

    protected $cart;
    protected $resultRedirectFactory;
    protected $formKeyValidator;
    private ProductBuyRequestBuilder $productBuyRequestBuilder;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param Cart $cart
     * @param ProductBuyRequestBuilder $productBuyRequestBuilder
     * @param RedirectFactory $resultRedirectFactory
     * @param FormKeyValidator $formKeyValidator
     */
    public function __construct(
        Context                    $context,
        ProductRepositoryInterface $productRepository,
        Cart                       $cart,
        ProductBuyRequestBuilder   $productBuyRequestBuilder,
        RedirectFactory            $resultRedirectFactory,
        FormKeyValidator           $formKeyValidator
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->productBuyRequestBuilder = $productBuyRequestBuilder;
    }

    public function execute()
    {
        $request = $this->getRequest();

        if (!$this->formKeyValidator->validate($request)) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $productId = (int) $request->getParam('product_id');
        $subscriptionPlanId = $request->getParam('subscription_plan_id'); // e.g., 'monthly'
        $superAttributes = $request->getParam('super_attribute_snapshot');

        try {
            $product = $this->productRepository->getById($productId);
            $buyRequest = $this->productBuyRequestBuilder->getProductBuyRequest($product, $subscriptionPlanId, $superAttributes);
            $this->cart->addProduct($product, $buyRequest);
            $this->cart->save();

            $this->messageManager->addSuccessMessage(__('Subscription added to cart.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not add subscription: ') . $e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('checkout/cart');
    }
}
