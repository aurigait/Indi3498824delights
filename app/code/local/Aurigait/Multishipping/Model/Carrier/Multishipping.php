<?php  
    class Aurigait_Multishipping_Model_Carrier_Multishipping     
		extends Mage_Shipping_Model_Carrier_Abstract
		implements Mage_Shipping_Model_Carrier_Interface
	{  
        protected $_code = 'multishipping';  
        protected $_default_condition_name = 'package_weight';
        
        protected $_conditionNames = array();
        /** 
        * Collect rates for this shipping method based on information in $request 
        * 
        * @param Mage_Shipping_Model_Rate_Request $data 
        * @return Mage_Shipping_Model_Rate_Result 
        */  
        public function collectRates(Mage_Shipping_Model_Rate_Request $request)
        {
        	/*if (!$this->getConfigFlag('active')) {
        		return false;
        	}*/
        
        	 if(count($request->getAllItems())<2)
        	 {
	        	return false;
          	 }
        	
        
        	// exclude Virtual products price from Package value if pre-configured
        	if ($request->getAllItems()) {
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
        		$request->setMRConditionName($this->_default_condition_name);
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
        /*
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
        */
        	foreach ($ratearray as $rate)
        	{
        		if (!empty($rate) && $rate['price'] >= 0) {
        			$method = Mage::getModel('shipping/rate_result_method');
        			$method->setCarrier('multishipping');
        			$method->setCarrierTitle($this->getConfigData('title'));
        			$method->setMethod('multishipping_'.$rate['pk']);
        			
        			if($rate['is_cod_enable'])
        			{
        				$method->setMethodTitle($rate['delivery_type']." (Cod vailable) ");
        			}
        			else 
        			{
        				$method->setMethodTitle($rate['delivery_type']." (Cod not available) ");
        			}
        			
        			$method->setDeliveryType($rate['delivery_type']);
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
        	$itemCount=0;
        	$old=$request->getData($request->getMRConditionName());
        	$itemList=array();
        	if ($request->getAllItems()) {
        		foreach ($request->getAllItems() as $item) {
        			$request->setData($request->getMRConditionName(),$item->getProduct()->getWeight()*$item->getQty());
        			$itemCount++;
        			//echo $item->getProduct()->getWeight()."<br>";
        			$itemList[]=$this->getNewRate($request);
        		}
        	}
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
        	//return Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate')->getNewRate($request,$this->getConfigFlag('zip_range'));
        	 
        }
        
        public function getNewRate(Mage_Shipping_Model_Rate_Request $request,$zipRangeSet=1)
        {
        	$read = Mage::getSingleton('core/resource')->getConnection('core_read');
        	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        	$postcode = $request->getDestPostcode();
        	$table = Mage::getSingleton('core/resource')->getTableName('matrixrate_shipping/matrixrate');
        
        	if ($zipRangeSet && is_numeric($postcode)) {
        		#  Want to search for postcodes within a range
        		$zipSearchString = ' AND ( '.$postcode.' BETWEEN dest_zip AND dest_zip_to or dest_zip=""))';
        	} else {
        		$zipSearchString = $read->quoteInto(" AND (? LIKE dest_zip or dest_zip ='' ) )", $postcode);
        	}
        	$select = $read->select()->from($table);
        
        	$select->where(
        			$read->quoteInto(" ((dest_country_id=? or dest_country_id='0') ", $request->getDestCountryId()).
        			$read->quoteInto(" AND (dest_region_id=? or dest_region_id='0')", $request->getDestRegionId()).
        			$read->quoteInto(" AND (STRCMP(LOWER(dest_city),LOWER(?)) = 0 or dest_city='')  ", $request->getDestCity()).
        			$zipSearchString
        	);
        
        	if (is_array($request->getMRConditionName())) {
        		$i = 0;
        		foreach ($request->getMRConditionName() as $conditionName) {
        			if ($i == 0) {
        				$select->where('condition_name=?', $conditionName);
        			} else {
        				$select->orWhere('condition_name=?', $conditionName);
        			}
        			$select->where('condition_from_value<=?', $request->getData($conditionName));
        		}
        	} else {
        		$select->where('condition_name=?', $request->getMRConditionName());
        		$select->where('condition_from_value<=?', $request->getData($request->getMRConditionName()));
        		$select->where('condition_to_value>=?', $request->getData($request->getMRConditionName()));
        	}
        
        	$select->where('website_id=?', $request->getWebsiteId());
        	$select->order('dest_country_id DESC');
        	$select->order('dest_region_id DESC');
        	$select->order('dest_zip DESC');
        	$select->order('condition_from_value DESC');
        	$newdata=array();
        	//echo $select->__toString();
        	$row = $read->fetchAll($select);
        		
        
        	return $row;
        }
		/**
		 * Get allowed shipping methods
		 *
		 * @return array
		 */
		public function getAllowedMethods()
		{
			return array($this->_code=>$this->getConfigData('name'));
		}
    }  
