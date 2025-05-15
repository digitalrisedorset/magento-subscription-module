<?php
/**
 * Copyright © Digital Rise Dorset. All rights reserved.YING.txt for license details.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);


namespace Drd\Subscribe\Model\Config\SubscriptionPlans;

use Magento\Framework\Config\ConverterInterface;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\Config\Reader\Filesystem;
use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Config\ValidationStateInterface;

class Reader extends Filesystem
{
    protected $_idAttributes = [
        '/config/plans/plan' => 'label'
    ];

    /**
     * @param \Magento\Framework\Config\FileResolverInterface $fileResolver
     * @param Converter $converter
     * @param SchemaLocator $schemaLocator
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     */
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface    $fileResolver,
        Converter                                          $converter,
        SchemaLocator                                      $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
                                                           $fileName = 'subscription_plans.xml',
                                                           $idAttributes = [],
                                                           $domDocumentClass = \Magento\Framework\Config\Dom::class,
                                                           $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
