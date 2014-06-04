<?php
/**
 * Webshopapps Shipping Module
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * Shipping MatrixRates
 *
 * @category   Webshopapps
 * @package    Webshopapps_Matrixrate
 * @copyright  Copyright (c) 2011 Zowta Ltd (http://www.webshopapps.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Karen Baker <sales@webshopapps.com>
*/

class Webshopapps_Matrixrate_Model_Carrier_Matrixrate
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'matrixrate';
    protected $_default_condition_name = 'package_weight';

    protected $_conditionNames = array();

    public function __construct()
    {
        parent::__construct();
        foreach ($this->getCode('condition_name') as $k=>$v) {
            $this->_conditionNames[] = $k;
        }
    }
    

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
		
       /* if(count($request->getAllItems())<2)
        {
        	$allItem=$request->getAllItems();
        	if($allItem[0]->getQty()<2)
        	{
        		return false;
        	}
        }
        */
        
        // exclude Virtual products price from Package value if pre-configured
        if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }
        
  		// Free shipping by qty
        $freeQty = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeQty += $item->getQty() * ($child->getQty() - (is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0));
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeQty += ($item->getQty() - (is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0));
                }
            }
        }

        if (!$request->getMRConditionName()) {
            $request->setMRConditionName($this->getConfigData('condition_name') ? $this->getConfigData('condition_name') : $this->_default_condition_name);
        }

         // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

		if ($this->getConfigData('allow_free_shipping_promotions') && !$this->getConfigData('include_free_ship_items')) {
			$request->setPackageWeight($request->getFreeMethodWeight());
			$request->setPackageQty($oldQty - $freeQty);
		}
        
        $result = Mage::getModel('shipping/rate_result');
     	$ratearray = $this->getRate($request);

     	$freeShipping=false;
     	
     	if (is_numeric($this->getConfigData('free_shipping_threshold')) && 
	        $this->getConfigData('free_shipping_threshold')>0 &&
	        $request->getPackageValue()>$this->getConfigData('free_shipping_threshold')) {
	         	$freeShipping=true;
	    }
    	if ($this->getConfigData('allow_free_shipping_promotions') &&
	        ($request->getFreeShipping() === true || 
	        $request->getPackageQty() == $this->getFreeBoxes()))
        {
         	$freeShipping=true;
        }
        if ($freeShipping)
        {
		  	$method = Mage::getModel('shipping/rate_result_method');
			$method->setCarrier('matrixrate');
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethod('matrixrate_free');
			$method->setPrice('0.00');
			$method->setMethodTitle($this->getConfigData('free_method_text'));
			$result->append($method);
			
			if ($this->getConfigData('show_only_free')) {
				return $result;
			}
		}
     	
	   foreach ($ratearray as $rate)
		{
		   if (!empty($rate) && $rate['price'] >= 0) {
				$method = Mage::getModel('shipping/rate_result_method');
				$cod="";
				$method->setCarrier('matrixrate');
				$method->setCarrierTitle($this->getConfigData('title'));
				$method->setMethod('matrixrate_'.$rate['pk']);
				
				if($rate['is_cod_enable'])
				{
				//	echo "hii";
					$method->setMethodTitle(Mage::helper('matrixrate')->__($rate['delivery_type'])." (Cod Available )");
					$shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
					$method->setDeliveryType($rate['delivery_type']);
					
				}
				else
				{
					$method->setMethodTitle(Mage::helper('matrixrate')->__($rate['delivery_type']));
					$method->setDeliveryType($rate['delivery_type']);
					
				}
				$shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
				
				$method->setCost($rate['cost']);
				
				$method->setPrice($shippingPrice);
				$result->append($method);
			}
		}

        return $result;
    }

    public function getRate(Mage_Shipping_Model_Rate_Request $request)
    {
/*    	$itemCount=0;
    	$old=$request->getData($request->getMRConditionName());
    	$itemList=array();
    	if ($request->getAllItems()) {
    		foreach ($request->getAllItems() as $item) {
    			$request->setData($request->getMRConditionName(),$item->getProduct()->getWeight());
    		//	echo $item->getProduct()->getWeight();
    		//	echo $request->getData($request->getMRConditionName());
    			$itemCount++;
    			$itemList[]=Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate')->getNewRate($request,$this->getConfigFlag('zip_range'));
    		}
    	}
   		//print_r($itemList);die;
   		
   		$carrList=array();
   		$count=array();
   		foreach ($itemList as $item)
   		{
   			foreach ($item as $i)
   			{
   				if(@$carrList[$i['pk']])
   				{
   					$i['price']=$i['price']+$carrList[$i['pk']]['price'];
   				}	
   				$carrList[$i['pk']]=$i;
   				$count[$i['pk']]=$count[$i['pk']]+1;
   			}
   		}
   		
   		foreach ($count as $index=>$c)
   		{
   			if($c<$itemCount)
   			{
   				unset($carrList[$index]);
   			}   			
   		}
   		
    	$old=$request->setData($request->getMRConditionName(),$old);
    	return $carrList;
  */  	
    	return Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate')->getNewRate($request,$this->getConfigFlag('zip_range'));
    	
    }
    
    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array('matrixrate'=>$this->getConfigData('name'));
    }
    

    public function getCode($type, $code='')
    {
        $codes = array(

            'condition_name'=>array(
                'package_weight' => Mage::helper('shipping')->__('Weight vs. Destination'),
                'package_value'  => Mage::helper('shipping')->__('Price vs. Destination'),
                'package_qty'    => Mage::helper('shipping')->__('# of Items vs. Destination'),
            ),

            'condition_name_short'=>array(
                'package_weight' => Mage::helper('shipping')->__('Weight'),
                'package_value'  => Mage::helper('shipping')->__('Order Subtotal'),
                'package_qty'    => Mage::helper('shipping')->__('# of Items'),
            ),

        );

        if (!isset($codes[$type])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Matrix Rate code type: %s', $type));
        }

        if (''===$code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Matrix Rate code for type %s: %s', $type, $code));
        }

        return $codes[$type][$code];
    }



}
