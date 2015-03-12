<?php

/**
 * TaxExempt order model
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */
	
class Aydus_TaxExempt_Model_Resource_Order_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract 
{

	protected function _construct()
	{
        parent::_construct();
		$this->_init('aydus_taxexempt/order');
	}
	
}