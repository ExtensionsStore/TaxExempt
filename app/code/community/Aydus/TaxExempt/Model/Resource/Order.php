<?php

/**
 * TaxExempt order model
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Model_Resource_Order extends Mage_Core_Model_Resource_Db_Abstract
{
	
	protected function _construct()
	{
		$this->_init('aydus_taxexempt/order', 'id');
	}
	
}

