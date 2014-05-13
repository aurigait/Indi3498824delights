<?php
 
class Aurigait_CommonIndidelights_Model_Eav_Entity_Attribute_Source_Options
   extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
   /*public function getAllOptions()
   {
   	
   	//print_r(Mage::getModel('catalog/product')->load(19)->getData());
   	$collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect("*")
   	->addFieldToFilter("is_tile",array("eq"=>1));
   $options=array();
   foreach ($collection as $tile)
   {
   		$options[]=array('value'=>$tile->getId(),'label'=>$tile->getName());
   	
   }
   	return $options;
      
   }
  public function getOptionArray()
  {
  	foreach ($this->getAllOptions() as $option)
  	{
  		$_options[$option['value']]=$option['label'];
  	}
  	return $_options;
  }
   public function getOptionText($value)
   {
       /* If this is null, then nobody has called getAllOptions() yet.
            return 34;
   }*/
   
   
   public function getAllOptions()
   {
   	$product=Mage::getModel('catalog/product')->load(19);
   	if (is_null($this->_options)) {
   		 
   		$collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect("*")
   	->addFieldToFilter("is_tile",array("eq"=>1));
   		//$arr[]=array("label"=>"Please Select a Fit Guide","value"=>'');
   		 
   		foreach($collection as $option)
   		{
   			$arr[]=array("label"=>$option->getName(),"value"=>$option->getId());
   		}
   		$this->_options = $arr;
   	}
   	return $this->_options;
   }
   
   /**
    * Retrieve option array
    *
    * @return array
    */
   public function getOptionArray()
   {
   	$product=Mage::getModel('catalog/product')->load(19);
   	echo '<pre>';print_r($product->getData());
   	$_options = array();
   	foreach ($this->getAllOptions() as $option) {
   		$_options[$option["value"]] = $option["label"];
   	}
   	return $_options;
   }
   
}