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
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Controller\Result\JsonFactory;

class Submit extends Action
{
    protected $productRepository;

    protected $cart;
    protected $resultRedirectFactory;
    protected $formKeyValidator;
    private ProductBuyRequestBuilder $productBuyRequestBuilder;
    private JsonFactory $resultJsonFactory;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param Cart $cart
     * @param ProductBuyRequestBuilder $productBuyRequestBuilder
     * @param FormKeyValidator $formKeyValidator
     */
    public function __construct(
        Context                    $context,
        ProductRepositoryInterface $productRepository,
        Cart                       $cart,
        ProductBuyRequestBuilder   $productBuyRequestBuilder,
        FormKeyValidator           $formKeyValidator,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->formKeyValidator = $formKeyValidator;
        $this->productBuyRequestBuilder = $productBuyRequestBuilder;
        $this->resultJsonFactory = $jsonFactory;
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

            $message = __(
                'You added %1 as a subscription to your shopping cart.',
                $product->getName()
            );
            $this->messageManager->addSuccessMessage($message);
            $response = [
                'errors' => false,
                'message' => $message
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => __('Could not add subscription: ') . $e->getMessage(),
            ];
            $this->messageManager->addErrorMessage($message);
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}
