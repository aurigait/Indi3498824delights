<?php
/**
 * Rewrites Mage_Core_Model_Store
 * Returns currency code based on visitor's IP Address
 *
 * @category   Chapagain
 * @package    Chapagain_AutoCurrency
 * @version    0.1.0
 * @author     Mukesh Chapagain <mukesh.chapagain@gmail.com> 
 * @link 	   http://blog.chapagain.com.np/category/magento/
 */
class Aurigait_CountyCurrencySwitcher_Model_CurrencySwitcher extends Mage_Core_Model_Store
{    	
    /**
     * Update default store currency code
     *
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
		/*if (Mage::helper('countycurrencyswitcher')->isEnabled()) {
			$result = $this->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_DEFAULT);		
			return $this->getCurrencyCodeByIp($result);
		} else {
			return parent::getDefaultCurrencyCode();
		}
		*/
    	//echo "here".Mage::helper('countycurrencyswitcher')->getCurrentCurrencyCode();die;
    	$curr_code_cookie= Mage::getModel('core/cookie')->get('currency_code');
    	if(empty($curr_code_cookie))
    	{
    		$code =Mage::helper('countycurrencyswitcher')->getCurrentCurrencyCode();
    		if (in_array($code, $this->getAvailableCurrencyCodes())) {
    			$this->_getSession()->setCurrencyCode($code);
    			Mage::app()->getCookie()->set(self::COOKIE_CURRENCY, $code);
    			Mage::getModel('core/cookie')->set('currency_code',$code);
    			return $code;
    		}
    	}
    	return parent::getDefaultCurrencyCode();
    }
	/**
     * Get Currency code by IP Address
     *
     * @return string
     */
}