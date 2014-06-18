<?php

class Aurigait_Ordercustomfeild_Model_Ordercustomfeild extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("ordercustomfeild/ordercustomfeild");

    }
    
    public function savedata($post_data)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "insert into  aurigait_ordercustome set order_id = '".$post_data['order_id']."' , item_id = '".$post_data['item_id']."' , delivery_date = '".$post_data['delivery_date']."' , tracking_number = '".$post_data['tracking_number']."' , qty = '".$post_data['qty']."' , trackby = '".$post_data['trackby']."', status ='".$post_data['status']."' ,create_at = '".$post_data['create_at']."' ";
    	$write->query($sql);
    	
    }

    public function deletealldata($orderid,$itemid)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "delete   from  aurigait_ordercustome where  order_id = '".$orderid."' and  item_id = '".$itemid."' ";
     
    	$write->query($sql);
    	 
    }
    
    public function getsavedata($orderid,$itemid)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select * from  aurigait_ordercustome where  order_id = '".$orderid."' and  item_id = '".$itemid."' and status =1 ";
     
    	return $write->fetchAll($sql);
    }
    
    
    public function getsavedatabyOrder($orderid )
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select sum(qty) as totalreceived from  aurigait_ordercustome where  order_id = '".$orderid."'  and status =1 ";
    	 
    	return $write->fetchOne($sql);
    }
    
    public function getsavedatabyOrderItem($orderid,$itemid)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select sum(qty) as totalreceived from  aurigait_ordercustome where  order_id = '".$orderid."' and  item_id = '".$itemid."'  and status =1 ";
    	
    	return $write->fetchOne($sql);
    	
    }
}
	 