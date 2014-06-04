<?php

class Aurigait_Invitefriend_Model_Invitefriend extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("invite/invitefriend");
		
    }
    protected function thistablename()
    {
    	return "voucher_referfriendlist";
    }
    
    public function savecustom($senderid,$sender_emailid,$receiver_emailid,$senddate)
    { 
    	
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "insert into  {$this->thistablename()} set sender_id =  '".$senderid."' , sender_emailid =  '".$sender_emailid."', receiver_emailid = '".$receiver_emailid."' , senddate = '".$senddate."'   , register_status = 0 ,  status= 1 , senddatetime = '".date('Y-m-d h:i:s')."' " ;
    	$write->query($sql);
    	
    }
    
    // update tabel
    public function updatereferaldone($sender_emailid,$receiver_emailid,$senddate)
    {
    	 
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "update   {$this->thistablename()} set register_status = 1  where sender_emailid =  '".$sender_emailid."' and  receiver_emailid = '".$receiver_emailid."' and  senddate = '".$senddate."'   and   status= 1 " ;
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
    	//echo $sql;
    	$senderinfo = $write->fetchRow($sql);
    	
    	if($senderinfo)
    	{
    		return $senderinfo;
    	}
    	else
    	{
    		return false;
    	}
    }
   
    public function getAllreferal($customerid)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "select  * from  {$this->thistablename()} where  sender_id = '".$customerid."' and status= 1  " ;
    	//echo $sql;
    	$senderinfo = $write->fetchAll($sql);
    	$returnarray = array();
    	if($senderinfo)
    	{
			foreach($senderinfo as $row)
			{
				$customer_email = $row['receiver_emailid'];//sandeepjain@aurigait.com';
				$customer = Mage::getModel("customer/customer");
				$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
				$data=  $customer->loadByEmail($customer_email);
				
				$registerdate = strtotime($data['created_at']);
				$referaldate = strtotime($row['senddatetime']);
			 	
				if($data['created_at'])
				{
					if(($registerdate>=$referaldate ))
					{
						if($row['register_status'] ==1)
						{
							$row['show_message'] = 'Coupon Generated';
						}
						else
						{
							$row['show_message'] = 'Registered but Coupon not generated';
						}
					}
					else
					{
						$row['show_message'] = 'Already Registered';
					}	
				}
				else
				{
					$row['show_message'] = 'Not Registered Yet';
				}
				
			
				$returnarray[] = $row;
			}			    		 
    		return $returnarray;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    
    
}
	 
