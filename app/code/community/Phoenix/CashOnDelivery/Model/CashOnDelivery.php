<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2008-2009 Andrej Sinicyn, Mik3e
 * @copyright  Copyright (c) 2010 - 2013 PHOENIX MEDIA GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Phoenix_CashOnDelivery_Model_CashOnDelivery extends Mage_Payment_Model_Method_Abstract
{
    const XML_CONFIG_PATH_CASHONDELIVERY_COST_TYPE = 'payment/phoenix_cashondelivery/cost_type';

    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code                    = 'phoenix_cashondelivery';
    protected $_canUseForMultishipping  = false;

    protected $_formBlockType = 'phoenix_cashondelivery/form';
    protected $_infoBlockType = 'phoenix_cashondelivery/info';

    /**
     * Get the configured inland fee.
     * If percentage is configured we calculate it from the configured address attribute.
     *
     * @param Mage_Customer_Model_Address_Abstract|null $address
     * @return float
     */
    public function getInlandCosts($address = null)
    {
        $inlandCost = $this->getConfigData('inlandcosts');

        if (is_object($address) && Mage::getStoreConfigFlag(self::XML_CONFIG_PATH_CASHONDELIVERY_COST_TYPE)) {
            $calcBase   = $this->getConfigData('cost_calc_base');
            $inlandCost = ($address->getData($calcBase) / 100) * $inlandCost;
        }
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $count=0;
        foreach ($cart->getAllItems() as $item) {
        	if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
        		continue;
        	}
        	$count++;	
        }	
        $cost=ceil($count/2)*100;
        
        return floatval($cost);
    }

    /**
     * Get the configured foreign country fee.
     * If percentage is configured we calculate it from the configured address attribute.
     *
     * @param Mage_Customer_Model_Address_Abstract|null $address
     * @return float
     */
    public function getForeignCountryCosts($address = null)
    {
        $foreignCost = $this->getConfigData('foreigncountrycosts');

        if (is_object($address) && Mage::getStoreConfigFlag(self::XML_CONFIG_PATH_CASHONDELIVERY_COST_TYPE)) {
            $calcBase   = $this->getConfigData('cost_calc_base');
            $foreignCost = ($address->getData($calcBase) / 100) * $foreignCost;
        }

        return floatval($foreignCost);
    }

    /**
     * Returns an configured custom text which is used to show additional information to the customer.
     *
     * @return string
     */
    public function getCustomText()
    {
        return $this->getConfigData('customtext');
    }

    /**
     * Returns COD fee for certain address
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @return float
     */
    public function getAddressCosts(Mage_Customer_Model_Address_Abstract $address)
    {
        if ($address->getCountry() == Mage::getStoreConfig('shipping/origin/country_id')) {
            return $this->getInlandCosts($address);

        } else {
            return $this->getForeignCountryCosts($address);
        }
    }

    /**
     * Returns the payment fee excluding the tax.
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @param null|float $value
     * @param bool $alreadyExclTax
     * @return float|null
     */
    public function getAddressCodFee(Mage_Customer_Model_Address_Abstract $address, $value = null, $alreadyExclTax = false)
    {
        $helper = Mage::helper('phoenix_cashondelivery');

        if (is_null($value)) {
            $value = $this->getAddressCosts($address);
        }

        if ($helper->codPriceIncludesTax()) {
            if (!$alreadyExclTax) {
                $value = $helper->getCodPrice($value, false, $address, $address->getQuote()->getCustomerTaxClassId());
            }
        }
        return $value;
    }

    /**
     * Returns the tax for the payment fee.
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @param null|float $value
     * @param bool $alreadyExclTax
     * @return int|float
     */
    public function getAddressCodTaxAmount(Mage_Customer_Model_Address_Abstract $address, $value = null, $alreadyExclTax = false)
    {
        $helper = Mage::helper('phoenix_cashondelivery');

        if (is_null($value)) {
            $value = $this->getAddressCosts($address);
        }

        if ($helper->codPriceIncludesTax()) {
            $includingTax = $helper->getCodPrice($value, true, $address, $address->getQuote()->getCustomerTaxClassId());
            if (!$alreadyExclTax) {
                $value = $helper->getCodPrice($value, false, $address, $address->getQuote()->getCustomerTaxClassId());
            }
            return $includingTax - $value;
        }
        return 0;
    }

    /**
     * Return true if the method can be used at this time
     *
     * @param Mage_Sales_Model_Quote|null $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }
        if (!is_null($quote)) {
            if ($this->getConfigData('shippingallowspecific', $quote->getStoreId()) == 1) {
                $country            = $quote->getShippingAddress()->getCountry();
                $availableCountries = $this->getConfigData('shippingspecificcountry', $quote->getStoreId());
                if (!in_array($country, explode(',', $availableCountries))) {
                    return false;
                }
            }
            if ($this->getConfigData('disallowspecificshippingmethods', $quote->getStoreId()) == 1) {
                $shippingMethodCode        = explode('_', $quote->getShippingAddress()->getShippingMethod());
                $shippingMethodCode        = $shippingMethodCode[0];
                $disallowedShippingMethods = $this->getConfigData('disallowedshippingmethods', $quote->getStoreId());

                if (in_array($shippingMethodCode, explode(',', $disallowedShippingMethods))) {
                    return false;
                }
            }
            $shippingMethodCode = explode('_', $quote->getShippingAddress()->getShippingMethod());
		    /*Somesh : If Shipping Method not gives COD */
		    if($shippingMethodCode[0]=='matrixrate' || $shippingMethodCode[0]=='multishipping')
            {
	            $table = Mage::getSingleton('core/resource')->getTableName('matrixrate_shipping/matrixrate');
	            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
	            $select = $read->select()->from($table);
	            $select->where($read->quoteInto("pk=?",$shippingMethodCode[2]));
	            $row = $read->fetchRow($select);
	            if(!empty($row) && !$row['is_cod_enable'])
	            {
	            	if(!Mage::registry('Cod_not_avail'))
	            		Mage::register('Cod_not_avail', "COD is not available with ".$row['delivery_type']." Carrier at this address ");
	            	return false;
	            }
            }
            /*Somesh Ends Shipping Method Level COD*/
			
            /* Somesh : If Product Level COD is Not Available*/
            foreach($quote->getAllItems() as $item)
            {
            	$_product=$item->getProduct()->load($item->getProduct()->getId());
            	$cod= $_product->getResource()->getAttribute('cod_available');
            	if($cod && strtolower($cod->getFrontend()->getValue($_product))!='yes')
            	{
            		if(!Mage::registry('Cod_not_avail'))
            			Mage::register('Cod_not_avail', "Cod is not available for product ".$item->getProduct()->getName());
            		return false;
            	}
            }
            //Somesh Ends Product Level COD
            
        }
        return true;
    }
}
