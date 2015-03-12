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
    
    public function getTaxClass()
    {
        $taxClass = Mage::getModel('tax/class');
        $taxClass->load(self::CLASS_NAME,'class_name');
        
        return $taxClass;
    }
    
    public function getCustomerGroup()
    {
        $customerGroup = Mage::getModel('customer/group');
        $customerGroup->load(self::CLASS_NAME, 'customer_group_code');
        
        return $customerGroup;
    }
    
    /**
     * 
     * @param bool $apply Whether to apply or not
     * @param array $payment Payment data from post
     */
    public function applyTaxExempt($apply = false, $payment = array())
    {
        $checkoutSession = Mage::getSingleton('checkout/session');
        $quote = $checkoutSession->getQuote();
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        $taxClass = $this->getTaxClass();
        $customerGroup = $this->getCustomerGroup();;
        
        try {
            
            if ($apply){
            
                $customer->setGroupId($customerGroup->getId());
                $quote->setCustomerTaxClassId($taxClass->getId());
                $quote->setCustomerGroupId($customerGroup->getId());
                $quote->save();
                
                $taxExempt = $payment['taxexempt'];
                $taxExemptNumber = $payment['taxexempt_number'];
                $taxExemptState = $payment['taxexempt_state'];
                $taxExemptSave = $payment['taxexempt_save'];
                
                $taxExempt = array(
                        'taxexempt' => $taxExempt,
                        'taxexempt_number' => $taxExemptNumber,
                        'taxexempt_state' => $taxExemptState,
                        'taxexempt_save' => $taxExemptSave,
                );
            
                $checkoutSession->setTaxExempt($taxExempt);
                
                if ($taxExemptSave){
                    
                    $customer->setTaxvat($taxExemptNumber)->setTaxvatState($taxExemptState)->save();
                    
                }
                 
            } else {
            
                if ($quote->getCustomerTaxClassId() == $taxClass->getId()){
            
                    $customerGroupId = 1;
                    $taxClassId = Mage::getModel('customer/group')->getTaxClassId($customerGroupId);
            
                    $quote->setCustomerTaxClassId($taxClassId);
                    $quote->setCustomerGroupId($customerGroupId);
                    $quote->save();
            
                    $checkoutSession->setTaxExempt(null);
            
                }
            }        
        
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
            
            if (is_array($taxExempt) && count($taxExempt)>0 && isset($taxExempt['taxexempt']) && isset($taxExempt['taxexempt_number'])) {
                
                $taxexemptNumber = $taxExempt['taxexempt_number'];
                $taxexemptState = $taxExempt['taxexempt_state'];
            
                $orderModel = Mage::getModel('aydus_taxexempt/order');
                $orderModel->setOrderId($order->getId())
                ->setTaxexemptNumber($taxexemptNumber)
                ->setTaxexemptState($taxexemptState)
                ->save();
            
            }
        
        } catch (Exception $e){
        
            Mage::log($e->getMessage(),null,'aydus_taxexempt.log');
        }        
        
    }
    
}