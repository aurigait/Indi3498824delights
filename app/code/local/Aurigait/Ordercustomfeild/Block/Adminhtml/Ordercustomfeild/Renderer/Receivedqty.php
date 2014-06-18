<?php

class Aurigait_Ordercustomfeild_Block_Adminhtml_Ordercustomfeild_Renderer_Receivedqty extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
     
    
    public function render(Varien_Object $row)
    {
    	$html = Mage::getModel("ordercustomfeild/ordercustomfeild")->getsavedatabyOrder($row->getId() );
    	
    	return $html;
    }
     

}
?>
