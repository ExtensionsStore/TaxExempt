<?php

/**
 * TaxExempt observer
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Model_Observer
{
    /**
     * 
     * @see sales_quote_payment_import_data_before
     * @param Varien_Event_Observer $observer
     */
    public function applyTaxExempt($observer)
    {
        $inputObject = $observer->getInput();
        $payment = $inputObject->getData();
                
        $model = Mage::getModel('aydus_taxexempt/taxexempt');
        
        if (is_array($payment) && isset($payment['taxexempt']) && $payment['taxexempt'] && isset($payment['taxexempt_number'])){
                    
            $model->applyTaxExempt(true, $payment);
        
        } else {
        
            $model->applyTaxExempt(false);
        }

    }
        
    /**
     * 
     * @see core_block_abstract_to_html_after
     * @param Varien_Event_Observer $observer
     */
    public function appendTaxExemptInfo($observer)
    {
        $block = $observer->getBlock();
        
        if ($block->getNameInLayout()=='order_payment'){
            
            $order = Mage::registry('current_order');
            
            if ($order && $order->getId()){
                
                $taxExemptOrderModel = Mage::getModel('aydus_taxexempt/order');
                $taxExemptOrderModel->load($order->getId(),'order_id');
                
                if ($taxExemptOrderModel->getId()){
                
                    $transport = $observer->getTransport();
                    $html = $transport->getHtml();
                
                    $taxexemptNumber = $taxExemptOrderModel->getTaxexemptNumber();
                    $taxexemptState = $taxExemptOrderModel->getTaxexemptState();
                    if (is_numeric($taxexemptState)){
                        $region = Mage::getModel('directory/region')->load($taxexemptState);
                        if($region->getId()){
                            $taxexemptState = $region->getDefaultName();
                        }
                    }
                
                    $taxExemptDetails = '<br /> Tax Exempt Number: ' . $taxexemptNumber;
                    $taxExemptDetails .= '<br /> Tax Exempt State: ' . $taxexemptState;
                
                    $transport->setHtml($html . $taxExemptDetails);
                
                }                
            }
            
        }
        
    }
    
    /**
     * 
     * @see sales_order_place_after
     * @param Varien_Event_Observer $observer
     */
    public function saveTaxExemptDetails($observer)
    {
        $order = $observer->getOrder();
        
        $taxExemptModel = Mage::getModel('aydus_taxexempt/taxexempt');
        
        $taxExemptModel->saveTaxExemptDetails($order);
        
    }
    
}