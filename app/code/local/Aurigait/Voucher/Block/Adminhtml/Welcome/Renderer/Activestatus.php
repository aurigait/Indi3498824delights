<?php
class Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Activestatus extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{

	public function render(Varien_Object $row)
	{
		$expiredays = $configValue = Mage::getStoreConfig('welcomevoucher/gwelcomevoucher/vouchervaliditypperiod');
		 
		
		$daygap = ($expiredays * 24 * 60 * 60);
		$registerdate = $row->getCreatedAtTimestamp();
		
		$totaldayspassed = $registerdate + $daygap;
		
		//$totaldayspassed =  date('Y-m-d ' , $totaldayspassed);
		if(time()>$totaldayspassed)
		{
			$status ="No";
		}
		else
		{
			if($row['voucher_amount'])
			{
				$status ="No";
			}
			else
			{
				$status ="Yes";
			}
		}

		
		return $status;
	}
}
?>
