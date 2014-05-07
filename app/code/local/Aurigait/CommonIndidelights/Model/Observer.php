<?php
class Aurigait_CommonIndidelights_Model_Observer
{
		public function addRemarkToQuote(Varien_Event_Observer $observer)
			{
				$remark=Mage::getSingleton('core/app')->getRequest()->getParam('user_remark');
				$quote           = $observer->getEvent()->getQuoteItem();
				$quote->setUserRemark($remark);
				echo "here<pre>";
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
