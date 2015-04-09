<?php

/**
 * TaxExempt block
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author     	Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Block_Taxexempt extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('aydus/taxexempt/taxexempt.phtml');
    }
    
    public function isActive()
    {
        return Mage::getStoreConfig('tax/taxexempt/active');
    }
    
    public function showState()
    {
        return Mage::getStoreConfig('tax/taxexempt/show_state');
    }    
    
    public function getRegions()
    {
        if ($this->showState()){
            
            if (!$this->_options) {
            
                $collection = Mage::getResourceModel('directory/region_collection');
            
                $collection->addFieldToFilter('country_id','US');
            
                $this->_options = $collection->loadData()->toOptionArray(false);
            
            }
            
            return $this->_options;
            
        }

        return false;
    }
    
    public function isLoggedIn()
    {
        $customerSession = Mage::getSingleton('customer/session');
        
        return $customerSession->isLoggedIn();
    }
    
    public function getTaxExemptGroup()
    {
        return Mage::getModel('aydus_taxexempt/taxexempt')->getCustomerGroup();
    }
    
    public function getChecked($field)
    {
        $checkoutSession = Mage::getSingleton('checkout/session');
        
        $taxExempt = $checkoutSession->getTaxExempt();
        
        if (is_array($taxExempt) && count($taxExempt)>0 && isset($taxExempt['taxexempt'])) {
            
            return ($taxExempt[$field]) ? 'checked="checked"' : '';
            
        } else {
            
            $customerSession = Mage::getSingleton('customer/session');

            if ($customerSession->isLoggedIn()){
                $customer = $customerSession->getCustomer();
                
                return ($customer->getGroupId() == $this->getTaxExemptGroup()->getId()) ? 'checked="checked"' : '';
              
            }
            
        }
        
    }
    
    public function getTaxExempt($field)
    {
        $checkoutSession = Mage::getSingleton('checkout/session');
        
        $taxExempt = $checkoutSession->getTaxExempt();
        
        if (is_array($taxExempt) && count($taxExempt)>0 && isset($taxExempt[$field])) {
            
            return $taxExempt[$field];
            
        } else {
            
            $customerSession = Mage::getSingleton('customer/session');
            
            if ($customerSession->isLoggedIn()){
                
                $customer = $customerSession->getCustomer();
                
                if ($customer->getGroupId() == $this->getTaxExemptGroup()->getId()){
                    
                    if ($field == 'taxexempt_number'){
                        
                        return $customer->getTaxvat();
                        
                    } else if ($field == 'taxexempt_state'){
                        
                        return $customer->getTaxvatState();
                        
                    } else {
                        
                        return $customer->getData($field);
                        
                    }
                    
                    
                }
                        
            }            
        }
        
        return false;
        
    }
    
}
