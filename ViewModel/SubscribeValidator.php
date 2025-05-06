<?php

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
}
