<?php

class Aurigait_Voucher_Model_Voucherlistcustomer extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("voucher/voucherlistcustomer");

    }
    protected function thistablename()
    {
    	return "voucher_allcouponlist";
    }
    
    public function testcoupon($custid,$orderamount,$fromdate,$minimumthreshold)
    {
    	$sql= "select  orderamount from {$this->thistablename()} where customer_id= '".$custid."' and vc_todate > '".$fromdate."' and status =1  order by vc_todate desc limit 0,1  ";
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$oldorderamount = $write->fetchone($sql);
    	
    	if($oldorderamount)
    	{
    		if( ($oldorderamount - $orderamount)  >=$minimumthreshold )
    		{
    			return ($oldorderamount - $orderamount)   ;
    		}
    		else
    		{
    			return -1;
    		}
    		
    	}
    	else
    	{
    		return 0;
    	} 
    	
    	
    }
    
    public function savecuoponinfo($customer_id,$couponcode,$orderamount,$vc_fromdate,$vc_todate)
    { 
    	
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "insert into  {$this->thistablename()} set customer_id =  '".$customer_id."' , voucher_code =  '".$couponcode."', orderamount = '".$orderamount."' , vc_fromdate = '".$vc_fromdate."' , vc_todate = '".$vc_todate."' ,  dateofcreation = '".date('Y-m-d')."' ,  status= 1 " ;
    	
    	$write->query($sql);
    	
    }
    
    public function checkCustomerByCoupon($customerid,$couponcode)
    {
    	$sql = "select id from {$this->thistablename()} where  customer_id =  '".$customer_id."' and voucher_code =  '".$couponcode."' and status =1 ";
    	$oldorderamount = $write->fetchone($sql);
    	if($oldorderamount)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }

}
	 