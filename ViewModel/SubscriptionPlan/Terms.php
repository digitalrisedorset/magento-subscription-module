<?php

namespace Drd\Subscribe\ViewModel\SubscriptionPlan;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Terms implements ArgumentInterface
{
    public function getCmsBlockId(): string
    {
        return 'subscription_tos_v2';
    }

    public function getTermsUrl(): string
    {
        return '/terms'; // optionally renderable as fallback
    }
}
