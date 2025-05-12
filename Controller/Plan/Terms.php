<?php

namespace Drd\Subscribe\Controller\Plan;

class Terms extends \Magento\Framework\App\Action\Action
{
    private \Magento\Framework\Controller\Result\RawFactory $resultRawFactory;
    private \Magento\Cms\Model\BlockFactory $blockFactory;
    private \Magento\Cms\Model\Template\FilterProvider $filterProvider;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->blockFactory = $blockFactory;
        $this->filterProvider = $filterProvider;
    }

    public function execute()
    {
        $blockId = $this->getRequest()->getParam('block_id');
        $block = $this->blockFactory->create()->load($blockId, 'identifier');

        $result = $this->resultRawFactory->create();

        if (!$block->getIsActive()) {
            return $result->setContents('<p>Terms not available.</p>');
        }

        $renderedContent = $this->filterProvider->getPageFilter()->filter($block->getContent());

        return $result->setContents($renderedContent);
    }
}
