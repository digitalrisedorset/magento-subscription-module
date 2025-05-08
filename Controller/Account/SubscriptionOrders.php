<?php
namespace Drd\Subscribe\Controller\Account;

use Drd\Subscribe\ViewModel\SubscribeOrders;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\App\RequestInterface;

class SubscriptionOrders extends Action
{
    protected $resultRawFactory;
    protected $layoutFactory;
    protected $request;
    private SubscribeOrders $subscribeOrdersViewModel;
    private Session $customerSession;

    /**
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param RequestInterface $request
     * @param SubscribeOrders $subscribeOrdersViewModel
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        RequestInterface $request,
        SubscribeOrders $subscribeOrdersViewModel,
        Session $customerSession
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->request = $request;
        parent::__construct($context);
        $this->subscribeOrdersViewModel = $subscribeOrdersViewModel;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        }

        $parentOrderId = $this->request->getParam('parent_order_id');

        $layout = $this->layoutFactory->create();
        $block = $layout->createBlock(\Drd\Subscribe\Block\Account\Subscription\SubscriptionOrders::class)
            ->setTemplate('Drd_Subscribe::account/subscription/subscription_orders.phtml')
            ->setData('viewModel', $this->subscribeOrdersViewModel)
            ->setParentOrderId($parentOrderId);

        $output = $block->toHtml();

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($output);
    }
}
