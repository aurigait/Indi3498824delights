<?php
class Aurigait_CodPerProduct_Model_Observer
{

		public function PaymentMethodIsActive(Varien_Event_Observer $observer)
			{
				//Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
				//$user = $observer->getEvent()->getUser();
				//$user->doSomething();
				
				$event           = $observer->getEvent();
        		 $method          = $event->getMethodInstance();
        		 $result          = $event->getResult();
        		 $quote			  = $event->getQuote();
        		 if($method->getCode()=="cashondelivery" && $result->isAvailable)
				 {
				 	
				 	$active=1;
				 	foreach($quote->getAllItems() as $item)
				 	{
				 		if(!$item->getProduct()->getData('cod_available'))
				 		{
				 			if(!Mage::registry('Cod_not_avail'))
				 				Mage::register('Cod_not_avail', "Cod is not available for product ".$item->getProduct()->getName());
				 			$active=0;break;
				 		}
				 		
				 	}
				 	if(!$active)
				 		$result->isAvailable = false;
				 	
				 }
				
			}
		
}
