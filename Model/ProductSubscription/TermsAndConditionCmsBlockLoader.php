<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\ProductSubscription;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class TermsAndConditionCmsBlockLoader
{
    private BlockRepositoryInterface $blockRepository;
    private FilterProvider $filterProvider;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        FilterProvider $filterProvider
    ) {
        $this->blockRepository = $blockRepository;
        $this->filterProvider = $filterProvider;
    }

    /**
     * @param string $blockId
     * @return string
     * @throws LocalizedException
     */
    public function getCmsBlockContent(string $blockId): string
    {
        try {
            $block = $this->blockRepository->getById($blockId);

            if (!$block->isActive()) {
                return '<p>Terms not available.</p>';
            }

            return $this->filterProvider->getPageFilter()->filter($block->getContent());
        } catch (NoSuchEntityException $e) {
            return '<p>Terms not available.</p>';
        }
    }
}
