<?php
class Aurigait_Voucher_Block_Voucherlist extends Mage_Core_Block_Template{   



	public function getAllvoucher()
	{
		$helperobj = Mage::Helper('voucher/customhelper');
		return $helperobj->getAllvoucher();
	 	
	}
}
