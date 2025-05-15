<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;

class SubscribeValidator implements ArgumentInterface
{
    private HttpContext $httpContext;

    /**
     * @param HttpContext $httpContext
     */
    public function __construct(
        HttpContext $httpContext
    ) {
        $this->httpContext = $httpContext;
    }

    public function isCustomerLoggedIn(): bool
    {
        return $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    public function getLoginUrl($block)
    {
        return $block->getUrl('customer/account/login', [
            'referer' => base64_encode($block->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]))
        ]);
    }
}
