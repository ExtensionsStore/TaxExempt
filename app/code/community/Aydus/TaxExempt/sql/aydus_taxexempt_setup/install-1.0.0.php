<?php

/**
 * TaxExempt setup
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author     	Aydus Consulting <davidt@aydus.com>
 */

$this->startSetup();
echo 'TaxExempt setup started...<br />';

//install tax class
$taxClass = Mage::getModel('tax/class');
$className = Aydus_TaxExempt_Model_Taxexempt::CLASS_NAME;
$taxClass->load($className,'class_name');

if (!$taxClass->getId()){
    $taxClass->setClassName($className)->setClassType('CUSTOMER')->save();
}

//install customer tax exempt group
$customerGroup = Mage::getModel('customer/group');
$customerGroup->load($className, 'customer_group_code');

if (!$customerGroup->getId()){
    $customerGroup->setCustomerGroupCode($className)->setTaxClassId($taxClass->getId())->save();
}

//install tax exempt state
$customerEntityTypeId = $this->getEntityTypeId('customer');
$attributeCode = 'taxvat_state';
$attributeId = $this->getAttributeId($customerEntityTypeId, $attributeCode);

if (!$attributeId){

    $frontendLabel = array('Tax Exempt State', 'Tax Exempt State');

    $this->addAttribute('customer', $attributeCode, array(
            'type' => 'varchar',
            'input' => 'text',
            'label' => $frontendLabel[0],
            'source' => '',
            'backend' => '',
            'global' => 1,
            'visible' => false,
            'required' => false,
            'user_defined' => true,
            'visible_on_front' => false,
            'note' => 'Tax Exempt State'
    ));

    $attributeId = $this->getAttributeId($customerEntityTypeId, $attributeCode);
    $attribute = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeCode);
    $attribute->load($attribute->getId());
    $attribute->setSortOrder(101)->setIsSystem(0);
    $attribute->save();    
}

//create order table of tax exempt numbers/states
$this->run("CREATE TABLE IF NOT EXISTS {$this->getTable('aydus_taxexempt_order')} (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`order_id` INT(11) NOT NULL,
`taxexempt_number` VARCHAR(255) NOT NULL,
`taxexempt_state` VARCHAR(255) NOT NULL,
PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

echo 'TaxExempt setup ended.<br />';

$this->endSetup();