<?php

/**
 * TaxExempt order model
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Model_Order extends Mage_Core_Model_Abstract
{
	/**
	 * Initialize resource model
	 */
	protected function _construct()
	{
        parent::_construct();
        
		$this->_init('aydus_taxexempt/order');
	}	
	
}