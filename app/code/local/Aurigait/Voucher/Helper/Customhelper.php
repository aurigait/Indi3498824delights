<?php
class Aurigait_Voucher_Helper_Customhelper extends Mage_Core_Helper_Abstract
{
	
	public function getVoucherlistatcart()
	{
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		
		$rulesCollection = Mage::getModel('salesrule/rule')->getCollection();//$rulesCollection->load(true,true);
		
		if($customer->getId())
		{
			$rulesCollection->getSelect()->where('( rule_coupons.expiration_date > now() or rule_coupons.expiration_date is NULL ) and main_table.is_active =1 ');
		}
		else
		{
			$rulesCollection->getSelect()->where('( rule_coupons.expiration_date > now() or rule_coupons.expiration_date is NULL ) and main_table.is_active =1 and main_table.rule_type in (1,4) ');
		}
		
		//$rulesCollection->load(true,true);
		//	print_r($rulesCollection);
		$vouchercustomreturnarr = array();
		
		foreach($rulesCollection as $rule)
		{
			$vouchercustomarr = array();
		// 	echo  $rule->getCode().'##'.$rule->getRuleType().'@@'.$rule->getTimesUsed().'@<br>';
			$isValidcoupon = true;
			if($customer->getId())
			{
				switch($rule->getRuleType())
				{
					case 2:
						//for welcome voucher
					    if($this->checkWelcomevoucher($customer,$rule->getCode()))
						{
							$isValidcoupon = true;
						}
						else
						{
							$isValidcoupon = false;
						}
						
						break;
					case 3:
						//for user cumulative voucher
						$ThresholdAmount =  $rule->getThresholdAmount();
						$PurchaseDays =  $rule->getPurchaseDays();
						$isValiduser =  Mage::getModel('voucher/voucherlistcustomer')->cehckCustomerforUsercoupon($customer->getId(),$PurchaseDays,$ThresholdAmount);
							
						if(!$isValiduser)
						{
							$isValidcoupon = false;
						}
						else
						{
							$isValidcoupon =true; 
						}
						break;
					case 5:
						//for invitation voucher
						if(($rule->getTimesUsed()) && $rule->getTimesUsed()>=$oRule->getUsesPerCustomer() )
						{
							$isValidcoupon = false;
						}
						else
						{
							$isValidcoupon = true;
						}
						break;
							
						
				}
			}
			
			if($isValidcoupon && $rule->getCode())
			{
				$vouchercustomarr['voucher_code'] = $rule->getCode();
				$vouchercustomarr['description'] = $rule->getDescription();
				$vouchercustomarr['expiration_date'] = $rule->getToDate();
				$vouchercustomarr['offer_amount'] = $this->getCouponValuebyrule($rule);
				$vouchercustomreturnarr[] =$vouchercustomarr;
			}
			
		} 
		return $vouchercustomreturnarr;
	}

		
	
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
	
			
			$oCoupon = Mage::getModel('salesrule/coupon')->load($ref['voucher_code'], 'code');
			$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
			
			$ref['offer_amount'] = $this->getCouponValuebyrule($oRule);
			
			$ref['description'] =  $oRule->getDescription(); 
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
	
	public function checkWelcomevoucher($customer,$code)
	{
		$orders = Mage::getResourceModel('sales/order_collection')
		->addFieldToSelect('*')
		->addFieldToFilter('customer_id',$customer->getId())
		->addFieldToFilter('coupon_code',$code);
		
		if($orders->getSize()>=1)
		{
			return false;
		}
		else 
			return true;
		
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
			if($this->checkWelcomevoucher($customer,$oRule->getCouponCode()))
			{
			
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
								'description' =>$oRule->getDescription()
									
						);
						return $welcomearr;
					}
				else
				{
					return false;
				}
	 		}
			else
			{
				return false;
			} 
		}
		else
		{
			return false;
		}
			
	}
	
	public function getCouponValuebyrule($oRule)
	{
		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$currency_symbol = Mage::app()->getLocale()->currency( $currency_code )->getSymbol();
		
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
