<?php
namespace Drd\Subscribe\Controller\Index;

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

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        Cart $cart,
        RedirectFactory $resultRedirectFactory,
        FormKeyValidator $formKeyValidator
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->formKeyValidator = $formKeyValidator;
    }

    public function execute()
    {
        $request = $this->getRequest();

        if (!$this->formKeyValidator->validate($request)) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $productId = (int) $request->getParam('product_id');
        $recurrence = $request->getParam('interval'); // e.g., 'monthly'
        $subscription = 1;

        try {
            $product = $this->productRepository->getById($productId);

            // Add product with custom options
            $params = [
                'product' => $productId,
                'qty' => 1,
                'options' => [
                    'subscription' => $subscription,
                    'recurrence' => $recurrence
                ]
            ];

            $this->cart->addProduct($product, $params);
            $this->cart->save();

            $this->messageManager->addSuccessMessage(__('Subscription added to cart.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not add subscription: ') . $e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('checkout/cart');
    }
}
