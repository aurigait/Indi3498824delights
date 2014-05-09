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
    
    public function savecuoponinfo($customer_id,$couponcode,$orderamount,$vc_fromdate,$vc_todate,$vouchertype=1)
    { 
    	
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$sql = "insert into  {$this->thistablename()} set customer_id =  '".$customer_id."' , voucher_code =  '".$couponcode."', orderamount = '".$orderamount."' , vc_fromdate = '".$vc_fromdate."' , vc_todate = '".$vc_todate."' ,  dateofcreation = '".date('Y-m-d')."'  , voucher_type = '".$vouchertype."' ,  status= 1 " ;
    	
    	$write->query($sql);
    	
    }
    
    public function checkCustomerByCoupon($customerid,$couponcode)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
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
    
    public function cehckCustomerforUsercoupon($customerid,$voucherperiod,$minimumthreshold)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$todate = date('Y-m-d');
    	$fromdate = date('Y-m-d' , strtotime('-'.$voucherperiod.' days' ));
    	$sql = "select  sum(base_subtotal ) as totalorderamount from sales_flat_order where status= 'complete' and created_at >'".$fromdate."' and created_at <='".$todate."'   and customer_id ='".$customerid."'  group by customer_id having totalorderamount >= ".$minimumthreshold."  ";
    	$isvalid = $write->fetchone($sql);
    	if($isvalid>=$minimumthreshold)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    	
    }
    
    public function getallVouchersbycustomerid($customerid)
    {
    	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
    	$todate = date('Y-m-d');
    	 
    	$sql = "select  voucher_code, vc_fromdate, vc_todate,voucher_type  from {$this->thistablename()} where status= 1  and (vc_todate IS NULL or vc_todate>='".$todate."' )  and customer_id = '".$customerid."'";
    	$data= $write->fetchAll($sql);
    	return $data;
    	
    }
    
}
	 