<?php
require_once "Mage/Checkout/controllers/CartController.php";  

require_once "Aurigait/Voucher/controllers/IndexController.php";

class Aurigait_Voucher_Checkout_CartController extends Mage_Checkout_CartController{

	public function couponPostAction()
	{
		 
		/**
		 * No reason continue with empty shopping cart
		 */
		if (!$this->_getCart()->getQuote()->getItemsCount()) {
			$this->_goBack();
			return;
		}
	
		$couponCode = (string) $this->getRequest()->getParam('coupon_code');
		if ($this->getRequest()->getParam('remove') == 1) {
			$couponCode = '';
		}
		$oldCouponCode = $this->_getQuote()->getCouponCode();
	
		if (!strlen($couponCode) && !strlen($oldCouponCode)) {
			$this->_goBack();
			return;
		}
	
		try {
			$codeLength = strlen($couponCode);
			
			// sandeep::  to add condtions for validation. 
			
			$retrun  = $this->checkcustomcondition($couponCode);
			
			if($retrun==-1)
			{
				$this->_goBack();
				return;
			}
			
            $codeLength = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;

            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')
                ->collectTotals()
                ->save();
			
            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                        $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                } else {
                    $this->_getSession()->addError(
                        $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode))
                    );
                }
            } else {
                $this->_getSession()->addSuccess($this->__('Coupon code was canceled.'));
            }

        
		} catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		} catch (Exception $e) {
			$this->_getSession()->addError($this->__('Cannot apply the coupon code.'));
			Mage::logException($e);
		}
	
		$this->_goBack();
	}
	
	
	public function checkcustomcondition($couponcode)
	{
		
		
		if($couponcode == Aurigait_Voucher_IndexController::WELCOMECODE)
		{
			
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$orders = Mage::getResourceModel('sales/order_collection')
			->addFieldToSelect('*')
			->addFieldToFilter('customer_id', $customer->getId());
				
			if ( $customer->getId())
			{
				if(!$orders->getSize())
				{
					$configValue = Mage::getStoreConfig('welcomevoucher/gwelcomevoucher/vouchervaliditypperiod');
						
					$daygap = ($configValue * 24 * 60 * 60);
					$registerdate = $customer->getCreatedAtTimestamp();
					
					$totaldayspassed =  $customer->getCreatedAtTimestamp() + $daygap;
					
					if( strtotime(now()) > $totaldayspassed)
					{
						//echo 123;die;
						$this->_getSession()->addError(
								$this->__('Coupon code "%s" is available for  %s days  after registration.', Mage::helper('core')->escapeHtml($couponcode) ,$configValue)
						);
						$this->_goBack();
						return -1;
					}
					else
						return 1;
				}
				else
				{
					// has already  placed an order
					$this->_getSession()->addError(
							$this->__('Coupon code "%s" is available only for First purchase.', Mage::helper('core')->escapeHtml($couponcode))
					);
					$this->_goBack();
					return -1;
				}
			}
			else
			{
				$this->_getSession()->addError(
						$this->__('Use Coupon code "%s" after login.', Mage::helper('core')->escapeHtml($couponcode))
				);
				$this->_goBack();
				return -1;
			}
		}
		else if(substr($couponcode,0,4) == Aurigait_Voucher_IndexController::CUSTCODE)
		{
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			if ( $customer->getId())
			{	
			    $isValiduser =  Mage::getModel('voucher/voucherlistcustomer')->checkCustomerByCoupon($customer->getId(),$couponcode);
				if(!$isValiduser)
				{
					$this->_getSession()->addError(
							$this->__('You are not valid person to use  "%s" coupon .', Mage::helper('core')->escapeHtml($couponCode))
					);
					$this->_goBack();
					return -1;
				}
				else
					return true;
			}
			else
			{
				$this->_getSession()->addError(
						$this->__('Use Coupon code "%s" after login .', Mage::helper('core')->escapeHtml($couponCode))
				);
				$this->_goBack();
				return -1;
			}
						 					
		}
	}
}

				
