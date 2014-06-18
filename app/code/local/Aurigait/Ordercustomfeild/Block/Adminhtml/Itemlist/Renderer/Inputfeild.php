<?php

class Aurigait_Ordercustomfeild_Block_Adminhtml_Itemlist_Renderer_Inputfeild extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
     
    
    public function render(Varien_Object $row)
    {
    	$orderid = Mage::app()->getRequest()->getParam('order_id');
    	$saveddata= Mage::getModel("ordercustomfeild/ordercustomfeild")->getsavedata($orderid,$row->getItemId()); 
    	
    	$html = "<input type='hidden' id='order_qty_" .$row->getItemId()."' rel='".$row->getName()."' class='order_qty_" .$row->getItemId()."' value='".number_format($row->getQtyOrdered())."'>";
    	$html .= "<table><thead><th>Qty</th><th>Date </th><th>Track by</th><th>Tacking No.</th></thead>";
    	$html .="<tbody>";
    	
    	$k = 1;
    	foreach($saveddata as $saverow)
    	{
    		$html .="<tr>";
    		$html .= '<td><input name="qty_' . $row->getItemId() . '_'.$k.'" id="qty_' . $row->getItemId() . '_'.$k.'"  rel="' . $row->getItemId() . '" class="input-text row'.$k.'-class-' . $row->getItemId() . '" 	value="'.$saverow['qty'].'" style="" /></td>';
    		$html .= '<td><input name="date_' . $row->getItemId() . '_'.$k.'" rel="' . $row->getItemId() . '" class="input-text row'.$k.'-class-' . $row->getItemId() . '" type="date" value="'.$saverow['delivery_date'].'" style="" /></td>';
    		$html .= '<td><input name="trackby_' . $row->getItemId() . '_'.$k.'" rel="' . $row->getItemId() . '" class="input-text row'.$k.'-class-' . $row->getItemId() . '" value="'.$saverow['trackby'].'" /></td>';
    		$html .= '<td><input name="trackingnumber_' . $row->getItemId() . '_'.$k.'" rel="' . $row->getItemId() . '" class="input-text row'.$k.'-class-' . $row->getItemId() . '" value="'.$saverow['tracking_number'].'" style="" /></td>';
    		$html .="</tr>";
    		$k++;
    	}
    	if($k<=3)	
    	{
	    	for($km = $k; $km<=3;$km++)
	    	{
	    		$html .="<tr>";
	    		$html .= '<td><input name="qty_' . $row->getItemId() . '_'.$km.'"  ="qty_' . $row->getItemId() . '_'.$km.'"  rel="' . $row->getItemId() . '" class="input-text row'.$km.'-class-' . $row->getItemId() . '" 	value="" style="" /></td>';
	    		$html .= '<td><input name="date_' . $row->getItemId() . '_'.$km.'" rel="' . $row->getItemId() . '" class="input-text row'.$km.'-class-' . $row->getItemId() . '"   type="date" value="" style="" /></td>';
	    		$html .= '<td><input name="trackby_' . $row->getItemId() . '_'.$km.'" rel="' . $row->getItemId() . '" class="input-text row'.$km.'-class-' . $row->getItemId() . '" value="" style="" /></td>';
	    		$html .= '<td><input name="trackingnumber_' . $row->getItemId() . '_'.$km.'" rel="' . $row->getItemId() . '" class="input-text row'.$km.'-class-' . $row->getItemId() . '" value="" style="" /></td>';
	    		$html .="</tr>";
	    	}
    	}
    	
    	
    	
    	
    	 
    	
    	$html .="</tbody></table>";
    	
    	
    	return $html;
    } 

}
?>
