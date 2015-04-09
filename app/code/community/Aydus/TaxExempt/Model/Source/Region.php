<?php

/**
 * Region source model
 *
 * @category    Aydus
 * @package     Aydus_TaxExempt
 * @author     	Aydus <davidt@aydus.com>
 */

class Aydus_TaxExempt_Model_Source_Region extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{

    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            
            $options = array();
            $options[] = array('value'=>null, 'label'=>Mage::helper('core')->__('-- Please Select --'));
            
            $collection = Mage::getModel('directory/region')->getCollection();
            $collection->addFieldToFilter('country_id','US');
            
            if ($collection && $collection->getSize()){
                
                foreach ($collection as $region){
                
                    $option = array(
                            'value' => $region->getId(),
                            'label' => $region->getDefaultName()
                    );
                
                    $options[] = $option;
                }
            }
            
            $this->_options = $options;
        }
        
        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

}
