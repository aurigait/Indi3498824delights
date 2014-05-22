<?php
class Aurigait_CommonIndidelights_Model_Observer
{
		public function addRemarkToQuote(Varien_Event_Observer $observer)
			{
				$remark=Mage::getSingleton('core/app')->getRequest()->getParam('user_remark');
				
				$quote           = $observer->getEvent()->getQuoteItem();
				$item_parent=$quote->getParentItem();
				if($item_parent)
				{
					$item_parent->setUserRemark(trim($remark));
				}
				$quote->setUserRemark(trim($remark));
				
				//$quote=Mage::getModel("sales/quote_item")->load($quote->getItemId());
			/*	$children=$quote->getChildren();
				$q=Mage::getModel('checkout/cart')->getQuote()->getAllItems();
				foreach ($q as $q1)
				{
					echo $q1->getProduct()->getName();
					echo "<br>";
				}
				//echo $quote->getProduct()->getName();
				//echo $quote->getParentId();
				//echo "ere";
			//	die;
				/*if($children) {
				//	print_r($children);die;
					$parentQuote = Mage::getModel("sales/quote_item")->load($quote->getParentItemId());
					$parentQuote->setUserRemark(trim($remark));
				}				
				/*$method          = $event->getMethodInstance();
				$result          = $event->getResult();
				$quote			 = $event->getQuote();
				*/
			}
		public function addRemarkToOrder(Varien_Event_Observer $observer)
		{
			$quote = $observer->getItem();
			$orderItem = $observer->getOrderItem();
			$orderItem->setUserRemark($quote->getUserRemark());			
		}		
}
