<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Drd\Subscribe\Model\ProductSubscription\SubscriptionPersister;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;

class Skip extends Action
{
    protected PageFactory $resultPageFactory;
    private Session $customerSession;
    private FormKeyValidator $formKeyValidator;
    private SubscriptionPersister $subscriptionPersister;

    /**
     * @param Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param FormKeyValidator $formKeyValidator
     * @param SubscriptionPersister $subscriptionPersister
     */
    public function __construct(
        Context                    $context,
        RedirectFactory            $resultRedirectFactory,
        FormKeyValidator           $formKeyValidator,
        SubscriptionPersister $subscriptionPersister,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->subscriptionPersister = $subscriptionPersister;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->resultRedirectFactory->create()->setPath('customer/account/login');
        }

        $request = $this->getRequest();

        if (!$this->formKeyValidator->validate($request)) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $subscriptionId = (int) $request->getParam('subscription_id');

        try {
            $this->subscriptionPersister->skipNextSubscriptionOrder($subscriptionId);
            $this->messageManager->addSuccessMessage(__('The next order has been successfully skipped.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not skip the next order: ') . $e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/subscriptions');
    }
}
