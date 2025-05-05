<?php

namespace Drd\Subscribe\Model\Config\Source;

use Drd\Subscribe\Model\Config\Data as SubscriptionConfigProvider;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Drd\Subscribe\Model\Config\PlanConfigProvider;

class AvailablePlans extends AbstractSource
{
    private SubscriptionConfigProvider $planConfigProvider;

    public function __construct(SubscriptionConfigProvider $planConfigProvider)
    {
        $this->planConfigProvider = $planConfigProvider;
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [];
            foreach ($this->planConfigProvider->get() as $id => $plan) {
                $this->_options[] = [
                    'label' => $plan['label'] ?? $id,
                    'value' => $id,
                ];
            }
        }
        return $this->_options;
    }
}
