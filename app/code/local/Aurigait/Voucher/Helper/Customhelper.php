<?php
class Aurigait_Voucher_Helper_Customhelper extends Mage_Core_Helper_Abstract
{

		
	
	public function getAllvoucher()
	{
			
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$referal =   Mage::getModel('voucher/voucherlistcustomer')->getallVouchersbycustomerid($customer->getId());
		$welcome = $this->getWelcomevoucher($customer);
		if($welcome)
		{
			$referal[] = $welcome;
		}
		$referalnewarray = array();
		foreach ($referal as $ref)
		{
	
			$ref['offer_amount'] = $this->getCouponvalue($ref['voucher_code']);
			$oCoupon = Mage::getModel('salesrule/coupon')->load($ref['voucher_code'], 'code');
			$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
			if($ref['voucher_type']==3)
			{
	
				
					
				$ThresholdAmount =  $oRule->getThresholdAmount();
				$PurchaseDays =  $oRule->getPurchaseDays();
				$isValiduser =  Mage::getModel('voucher/voucherlistcustomer')->cehckCustomerforUsercoupon($customer->getId(),$PurchaseDays,$ThresholdAmount);
			
				if(!$isValiduser)
				{		
					$ref['voucher_status'] =0;
				}
				else
				{
					$ref['voucher_status'] =1;
					$ref['voucher_price'] =1;
				}
			}
			else if($ref['voucher_type']==5 or $ref['voucher_type']==6)
			{
					
				if(($oRule->getUsesPerCustomer()) && $oCoupon->getTimesUsed()>=$oRule->getUsesPerCustomer() )
				{
					$ref['voucher_status'] =0;
				}
				else
				{
					$ref['voucher_status'] =1;
				}
			}
			$referalnewarray[] = $ref;
		}
			
		return $referalnewarray ;
	}
	
	
	public function getWelcomevoucher($customer)
	{
	
		$oRule = Mage::getModel('salesrule/rule')->load(2,'rule_type');
	
		if(($oRule->getCouponCode()))
		{
			$orders = Mage::getResourceModel('sales/order_collection')
			->addFieldToSelect('*')
			->addFieldToFilter('customer_id',$customer->getId());
				
		//	if(!$orders->getSize())
		//	{
				$configValue = Mage::getStoreConfig('welcomevoucher/gwelcomevoucher/vouchervaliditypperiod');
					
				$daygap = ($configValue * 24 * 60 * 60);
				$registerdate = $customer->getCreatedAtTimestamp();
	
				$totaldayspassed =  $customer->getCreatedAtTimestamp() + $daygap;
				if( strtotime(now()) <= $totaldayspassed)
				{
					$totaldayspassed =  date('Y-m-d' , $totaldayspassed);
					$welcomearr = array(
							'voucher_code' =>$oRule->getCouponCode(),
							'vc_fromdate' =>$customer->getCreatedAt(),
							'vc_todate' => $totaldayspassed,
							'voucher_type'=> 2,
							'voucher_status'=> 1,
								
					);
					return $welcomearr;
				}
				else
				{
					return false;
				}
	/*		}
			else
			{
				return false;
			} */
		}
		else
		{
			return false;
		}
			
	}
	
	public function getCouponvalue($couponcode)
	{
		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$currency_symbol = Mage::app()->getLocale()->currency( $currency_code )->getSymbol();
	
	
		$oCoupon = Mage::getModel('salesrule/coupon')->load($couponcode, 'code');
			
		$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
		if($oRule->getSimpleAction()=='by_percent')
		{
			$retrunstr = number_format($oRule->getDiscountAmount(),2)." %";
		}
		else
		{
			$retrunstr = number_format($oRule->getDiscountAmount(),2)." ".$currency_symbol;
		}
		return $retrunstr;
	
	}
}
