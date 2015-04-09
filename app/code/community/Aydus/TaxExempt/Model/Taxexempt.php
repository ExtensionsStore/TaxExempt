<?php

/**
 * TaxExempt model
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Model_Taxexempt extends Mage_Core_Model_Abstract
{
    const CLASS_NAME = 'Tax Exempt';
    
    public function getTaxClass($taxExempt=true)
    {
        $taxClass = Mage::getModel('tax/class');
        
        if ($taxExempt){
            $taxClass->load(self::CLASS_NAME,'class_name');
        } else {
            $taxClass->load('Retail Customer','class_name');
        }
        
        return $taxClass;
    }
    
    public function getCustomerGroup($taxExempt=true)
    {
        $customerGroup = Mage::getModel('customer/group');
        
        if ($taxExempt){
            $customerGroup->load(self::CLASS_NAME, 'customer_group_code');
        } else {
            
            $customerSession = Mage::getSingleton('customer/session');
            
            if($customerSession->isLoggedIn()){
                
                $customerGroup->load('General', 'customer_group_code');
                
            } else {
                
                $customerGroup->load('NOT LOGGED IN', 'customer_group_code');
                
            }
            
        }
        
        return $customerGroup;
    }
    
    /**
     * 
     * @param bool $apply Whether to apply or not
     * @param array $payment Payment data from post
     */
    public function applyTaxExempt($apply = false, $payment = array())
    {
        try {
            
            $checkoutSession = Mage::getSingleton('checkout/session');
            $quote = $checkoutSession->getQuote();
            $customerSession = Mage::getSingleton('customer/session');
            $customer = $customerSession->getCustomer();
            
            if ($apply){
                
                $taxExempt = (isset($payment['taxexempt'])) ? $payment['taxexempt'] : 0;
                $taxExemptNumber = (isset($payment['taxexempt_number'])) ? $payment['taxexempt_number'] : 0;
                $taxExemptState = (isset($payment['taxexempt_state'])) ? $payment['taxexempt_state'] : '';
                $taxExemptSave = (isset($payment['taxexempt_save'])) ? $payment['taxexempt_save'] : 0;
                
                $taxExemptAr = array(
                        'taxexempt' => $taxExempt,
                        'taxexempt_number' => $taxExemptNumber,
                        'taxexempt_state' => $taxExemptState,
                        'taxexempt_save' => $taxExemptSave,
                );
            
                $checkoutSession->setTaxExempt($taxExemptAr);
                
                $customerGroup = $this->getCustomerGroup(true);
                $customerGroupId = $customerGroup->getId();
                $taxClassId = $customerGroup->getTaxClassId();

                if ($taxExemptSave){
                    
                    $customer->setTaxvat($taxExemptNumber)->setTaxvatState($taxExemptState)->save();
                }
                 
            } else {
                    
                $checkoutSession->setTaxExempt(null);
                
                $customerGroup = $this->getCustomerGroup(false);
                $customerGroupId = $customerGroup->getId();
                $taxClassId = $customerGroup->getTaxClassId();
            }      

            $customer->setGroupId($customerGroupId);
            $quote->setCustomerTaxClassId($taxClassId);
            $quote->setCustomerGroupId($customerGroupId);
            $quote->save();            
        
        } catch (Exception $e){
        
            Mage::log($e->getMessage(),null,'aydus_taxexempt.log');
        }       
        
    }
    
    /**
     * On save submit, save tax exempt details
     * 
     * @param Mage_Sales_Model_Order $order
     */
    public function saveTaxExemptDetails($order)
    {
        try {
            
            $checkoutSession = Mage::getSingleton('checkout/session');
            
            $taxExempt = $checkoutSession->getTaxExempt();
            
            if (is_array($taxExempt) && count($taxExempt)>0 && isset($taxExempt['taxexempt']) && $taxExempt['taxexempt'] && isset($taxExempt['taxexempt_number'])) {
                
                $taxexemptNumber = $taxExempt['taxexempt_number'];
                $taxexemptState = $taxExempt['taxexempt_state'];
            
                $orderModel = Mage::getModel('aydus_taxexempt/order');
                $orderModel->setOrderId($order->getId())
                ->setTaxexemptNumber($taxexemptNumber)
                ->setTaxexemptState($taxexemptState)
                ->save();
                
            }
            
            //needed for testing
            Mage::register('tax_exempt_order', $order);
        
        } catch (Exception $e){
        
            Mage::log($e->getMessage(),null,'aydus_taxexempt.log');
        }        
        
    }
    
}