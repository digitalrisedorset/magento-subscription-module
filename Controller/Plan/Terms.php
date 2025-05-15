<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Controller\Plan;

use Drd\Subscribe\Model\ProductSubscription\TermsAndConditionCmsBlockLoader;

class Terms extends \Magento\Framework\App\Action\Action
{
    private \Magento\Framework\Controller\Result\RawFactory $resultRawFactory;
    private TermsAndConditionCmsBlockLoader $termsAndConditionCmsBlockLoader;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        TermsAndConditionCmsBlockLoader $termsAndConditionCmsBlockLoader
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->termsAndConditionCmsBlockLoader = $termsAndConditionCmsBlockLoader;
    }

    public function execute()
    {
        $blockId = $this->getRequest()->getParam('block_id');

        $result = $this->resultRawFactory->create();

        return $result->setContents($this->termsAndConditionCmsBlockLoader->getCmsBlockContent($blockId));
    }
}
