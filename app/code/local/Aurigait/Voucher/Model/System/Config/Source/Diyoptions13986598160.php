<?php
class Aurigait_Voucher_Model_System_Config_Source_Diyoptions13986598160
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
		
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Percent of product price discount')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Fixed amount discount')),
        );
    }

}
