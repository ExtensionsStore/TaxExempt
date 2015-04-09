<?php

/**
 * TaxExempt data setup
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author     	Aydus <davidt@aydus.com>
 */

if (Mage::getIsDeveloperMode()){
    
    echo 'TaxExempt data setup started...<br />';

    try {
        $store = Mage::app()->getDefaultStoreView();
        $websiteId = $store->getWebsiteId();
        
        //add test customer
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId($websiteId);
        $email = 'davidt@aydus.com';
        $customer->loadByEmail($email);        
        
        if (!$customer->getId()){
            
            $customer->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname('David')
            ->setLastname('Tay')
            ->setEmail($email)
            ->setPassword('testing123');
            $customer->save();
        }
        
        //add test product
        $product = Mage::getModel('catalog/product');
        $sku = 'TEST-PRODUCT';
        
        $productId = $product->getIdBySku($sku);
        
        if (!$productId){
            
            $product->setSku($sku);
            $product->setName('Test Product');
            $product->setDescription("Test product description");
            $product->setShortDescription("Test product short description.");
            $product->setPrice(10.00);
            $product->setTypeId('simple');
            $product->setAttributeSetId(Mage::getModel('catalog/product')->getDefaultAttributeSetId());
            $product->setWeight(1.0);
            $product->setTaxClassId(2);
            $product->setVisibility(1);
            $product->setStatus(1);
            $product->setWebsiteIds(array($websiteId));
            $product->setStockData(array(
                'use_config_manage_stock' => 0, 
                'manage_stock'=>0, 
                'is_in_stock' => 1,
                'qty' => 100000,
                )
            );
            
            $product->save();            
        }
         
    } catch (Exception $e){
        
        echo $e->getMessage().'.<br />';
    }
    
    echo 'TaxExempt data setup ended.<br />';

}