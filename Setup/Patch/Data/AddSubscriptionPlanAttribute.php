<?php

namespace Drd\Subscribe\Setup\Patch\Data;

use Drd\Subscribe\Constants;
use Drd\Subscribe\Model\Config\Source\AvailablePlans;
use Drd\Subscribe\Model\Setup\AttributeHandler;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddSubscriptionPlanAttribute implements DataPatchInterface
{
    private $moduleDataSetup;
    private $eavSetupFactory;
    private AttributeHandler $attributeHandler;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeHandler $attributeHandler
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeHandler = $attributeHandler;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            Constants::SUBSCRIPTION_PLAN_IDS,
            [
                'type'                    => 'text',
                'label'                   => 'Subscription Plans',
                'input'                   => 'multiselect',
                'backend'                 => ArrayBackend::class,
                'source'                  => AvailablePlans::class,
                'visible'                 => true,
                'required'                => false,
                'user_defined'            => true,
                'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                'used_in_product_listing' => false,
                'visible_on_front'        => false
            ]
        );

        $this->attributeHandler->addAttributeToSubscriptionGroup(
            Constants::SUBSCRIPTION_PLAN_IDS,
            $eavSetup
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
