<?php 
class Aurigait_Voucher_Model_Observer
{
	public function __construct()
	{
		
	}
	
	public function apply_discount_percent(Varien_Event_Observer $observer)
	{
		$event = $observer->getEvent();
		$product = $event->getProduct();
		
		$quote = $observer->getEvent()->getQuote();
		
	/*	echo $quote->getSubtotal();
		
		echo "<pre>";
		echo "########################";
		print_r($quote);
		echo "</pre>"; die; */	 
	}
}
?>