<?php

class Aurigait_Voucher_Model_Invitefriend extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("voucher/invitefriend");
		
    }
    protected function thistablename()
    {
    	return "voucher_referfriendlist";
    }
    
    public function savecustom($senderid,$sender_emailid,$receiver_emailid,$senddate)
    { 
    	
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "insert into  {$this->thistablename()} set sender_id =  '".$senderid."' , sender_emailid =  '".$sender_emailid."', receiver_emailid = '".$receiver_emailid."' , senddate = '".$senddate."'   , register_status = 0 ,  status= 1 , senddatetime = '".date('Y-m-d h:i:s')."'" ;
    	$write->query($sql);
    	
    }
    
    // update tabel
 public function updatereferaldone($sender_emailid,$receiver_emailid,$senddate,$couponcode)
    {
    	 
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	

    	
    	$sql = "update   {$this->thistablename()} set register_status = 2  where receiver_emailid = '".$receiver_emailid."' and  senddate = '".$senddate."'   and   status= 1 " ;
    	$write->query($sql);
    	
    	$sql = "update   {$this->thistablename()} set register_status = 1, voucher_code =  '".$couponcode."'  where sender_emailid =  '".$sender_emailid."' and  receiver_emailid = '".$receiver_emailid."' and  senddate = '".$senddate."'   and   status= 1 " ;
    	$write->query($sql);
    	 
    }
    
    
    public function checkcustomerbyreferal($customeremail)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select  * from  {$this->thistablename()} where  receiver_emailid = '".$customeremail."' and status= 1  and register_status = 0" ;
    	//echo $sql;
    	$senderinfo = $write->fetchAll($sql);
    	
    	if($senderinfo)
    	{
    		return $senderinfo;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    public function checkcustomerbylastreferal($customeremail)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select  * from  {$this->thistablename()} where  receiver_emailid = '".$customeremail."' and status= 1  and register_status = 0 order by senddatetime desc limit 0,1 " ;
    	echo $sql; 
    	
    	$senderinfo = $write->fetch($sql);
    	 
    	if($senderinfo)
    	{
    		return $senderinfo;
    	}
    	else
    	{
    		return false;
    	}
    }
   
    
}
	 