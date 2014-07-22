<?php
class Aurigait_CommonIndidelights_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	function getLayer($id)
	{
	
		$layer = Mage::getModel('catalog/layer');
		$layer->setCurrentCategory(Mage::getModel('catalog/category')->load($id));
		$filterableAttributes=$layer->getFilterableAttributes();
		foreach ($filterableAttributes as $attribute) {
			if ($attribute->getAttributeCode() == 'price') {
				$filterBlockName = 'catalog/layer_filter_price';
			} elseif ($attribute->getBackendType() == 'decimal') {
				$filterBlockName = 'catalog/layer_filter_decimal';
			} else {
				$filterBlockName = 'catalog/layer_filter_attribute';
			}
			Mage::app()->getLayout()->createBlock($filterBlockName)
			->setLayer($layer)
			->setAttributeModel($attribute)
			->init();
		}
		$layer->apply();
		return $layer;
	}
}
?>