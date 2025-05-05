<?php

namespace Drd\Subscribe\Model\Setup;

use Magento\Catalog\Model\Product;

class AttributeHandler
{

    /**
     * @param $sttributeCode
     * @param $eavSetup
     * @return void
     */
    public function addAttributeToSubscriptionGroup($sttributeCode, $eavSetup): void
    {
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        $allAttributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        foreach ($allAttributeSetIds as $attributeSetId) {
            // Create group if it doesn't exist
            $groupName = 'Subscription';
            try {
                $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
            } catch (\Exception $e) {
                $groupId = $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 99);
            }
            $eavSetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $groupId,
                $sttributeCode
            );
        }
    }
}
