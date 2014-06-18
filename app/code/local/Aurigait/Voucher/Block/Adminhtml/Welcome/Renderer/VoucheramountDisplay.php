<?php
class Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
 
	private  $_currency_symbol;
	public function _construct()
	{
		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$this->_currency_symbol = Mage::app()->getLocale()->currency( $currency_code )->getSymbol();
		
	}
		
	public function render(Varien_Object $row)
	{
		
		if($row['simple_action']=='by_percent')
		{
			$retrunstr = number_format($row['discount_amount'],2)." %";
		}
		else
		{
			$retrunstr = number_format($row['discount_amount'],2)." ".$this->_currency_symbol;
		}
		return $retrunstr; 
	}
	public function getFilter()
	{
		return true;
	}
}
?>
