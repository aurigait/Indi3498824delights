<?php

class Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Qtyreceived extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
     
    
    public function render(Varien_Object $row)
    {
    	
    	$orderid = Mage::app()->getRequest()->getParam('order_id');
    	$html= Mage::getModel("ordercustomfeild/ordercustomfeild")->getsavedatabyOrderItem($orderid,$row->getItemId());
    	 
    	    	
    	return $html;
    }
     

}
?>
