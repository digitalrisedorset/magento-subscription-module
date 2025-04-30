<?php

namespace Drd\Subscribe\Plugin;

use Drd\Subscribe\Model\Subscription;

class SubscriptionBeforeSavePlugin
{
    public function beforeSave(Subscription $subject)
    {
        // Normalize status
        if ($subject->getStatus()) {
            $subject->setStatus(strtolower($subject->getStatus()));
        }

        // If status is not set, and data seems valid, default to 'active'
        if (!$subject->getStatus() && $this->isValid($subject)) {
            $subject->setStatus('active');
        }

        // Set next_order_date if missing
        if (!$subject->getNextOrderDate() && $subject->getRecurrence()) {
            $subject->setNextOrderDate(
                $this->calculateNextDate($subject->getRecurrence())
            );
        }

        return [$subject];
    }

    private function isValid(Subscription $subject): bool
    {
        return $subject->getOrderId() && $subject->getSku() && $subject->getRecurrence();
    }

    private function calculateNextDate(string $recurrence): string
    {
        $date = new \DateTime();
        return match (strtolower($recurrence)) {
            'weekly' => $date->modify('+1 week')->format('Y-m-d H:i:s'),
            'monthly' => $date->modify('+1 month')->format('Y-m-d H:i:s'),
            default => $date->modify('+1 month')->format('Y-m-d H:i:s'),
        };
    }
}
