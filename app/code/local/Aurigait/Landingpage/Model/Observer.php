<?php
class Aurigait_Landingpage_Model_Observer
{
		public function saveEthnicity(Varien_Event_Observer $observer)
		{
			$customer=$observer->getEvent()->getCustomer();
			$postData = Mage::app()->getRequest()->getPost();
			if($postData['region'])
				$customer->setRegion($postData['region']);
			else
				$customer->setRegion($postData['region_id']);
			$customer->setCountry($postData['country_id'])->save();									
		}
				
}
