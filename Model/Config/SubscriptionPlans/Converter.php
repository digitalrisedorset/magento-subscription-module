<?php

namespace Drd\Subscribe\Model\Config\SubscriptionPlans;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convert($source)
    {
        $result = [];

        /** @var \DOMNode $plansNode */
        foreach ($source->documentElement->childNodes as $plansNode) {
            if ($plansNode->nodeType !== XML_ELEMENT_NODE || $plansNode->nodeName !== 'plans') {
                continue;
            }

            foreach ($plansNode->childNodes as $planNode) {
                if ($planNode->nodeType !== XML_ELEMENT_NODE || $planNode->nodeName !== 'plan') {
                    continue;
                }

                $planId = $planNode->attributes->getNamedItem('id')->nodeValue ?? null;
                if (!$planId) {
                    continue;
                }

                $planData = [];

                foreach ($planNode->childNodes as $planChild) {
                    if ($planChild->nodeType !== XML_ELEMENT_NODE) {
                        continue;
                    }

                    if ($planChild->nodeName === 'frequency') {
                        $planData['frequency'] = $planChild->nodeValue;
                    } else {
                        $planData[$planChild->nodeName] = $planChild->nodeValue;
                    }
                }

                $result[$planId] = $planData;
            }
        }

        return $result;
    }

}
