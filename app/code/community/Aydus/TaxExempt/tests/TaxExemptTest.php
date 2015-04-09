<?php

/**
 * Required:
 * 1. Customer with taxable address
 * 2. Checkmo payment method
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */
require_once('bootstrap.php');

class TaxExemptTest extends PHPUnit_Framework_TestCase {
    
    protected $_email = 'davidt@aydus.com';
    
    public function setUp() 
    {
    }

    public function testCustomerCheckout() 
    {                
        try {
            
            //place a non-taxexempt order first as placing a taxexempt order will set the customer's group to taxexempt
            $order = $this->_placeOrder(0);
            $orderCreated = is_object($order);
            $this->assertTrue($orderCreated);
            $this->assertTrue(is_numeric($order->getId()));
            $taxExemptOrder = Mage::getModel('aydus_taxexempt/order')->load($order->getId(), 'order_id');
            $this->assertFalse(is_numeric($taxExemptOrder->getId()));
            
            //place taxexempt order, setting customer group to taxexempt
            $order = $this->_placeOrder(1);
            $orderCreated = is_object($order);
            $this->assertTrue($orderCreated);
            $this->assertTrue(is_numeric($order->getId()));
            $taxExemptOrder = Mage::getModel('aydus_taxexempt/order')->load($order->getId(), 'order_id');
            $this->assertTrue(is_numeric($taxExemptOrder->getId()));
            
        } catch (Exception $e){
            
            file_put_contents(dirname(__FILE__).'/taxexempt.log', $e, FILE_APPEND);
        }
        
    }
    
    /**
     * 
     * @param bool $taxExempt
     * @return Mage_Sales_Model_Order
     */
    protected function _placeOrder($taxExempt = 1)
    {
        $customer = Mage::getModel('customer/customer');
                
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        $customer->loadByEmail($this->_email);
                
        Mage::getSingleton('customer/session')->loginById($customer->getId());
                
        $product = Mage::getModel('catalog/product');
        $sku = 'TEST-PRODUCT';
        
        $productId = $product->getIdBySku($sku);        
        
        $product->load($productId);
        
        $cart = Mage::getSingleton('checkout/cart');
        $param = array(
                'product' => $product->getId(),
                'qty' => 1,
        );
        $request = new Varien_Object();
        $request->setData($param);
        $cart->addProduct($product, $request);
        $cart->save();
                
        $checkout = Mage::getSingleton('checkout/type_onepage');
        $checkout->initCheckout();
        $checkout->saveShippingMethod('flatrate_flatrate');
        
        if ($taxExempt){
            
            $payment = array(
                    'method'=>'checkmo',
                    'taxexempt' => $taxExempt,
                    'taxexempt_number' => '1234567890',
                    'taxexempt_state' => 'New York',
                    'taxexempt_save' => 1,
            );
            
        } else {
            
            $payment = array(
                    'method'=>'checkmo',
                    'taxexempt' => $taxExempt,
            );            
        }
        
        $checkout->savePayment($payment);
        
        try {
            
            $checkout->saveOrder();
            $cart->truncate();
            $cart->save();
            $cart->getItems()->clear()->save();
            
            Mage::getSingleton('customer/session')->logout();
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
        //set in Aydus_TaxExempt_Model_Taxexempt::saveTaxExemptDetails
        return Mage::registry('tax_exempt_order'); 
        
    }
    
}
