<?php

class Aurigait_Voucher_Block_Adminhtml_Adminvcreport_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("adminvcreportGrid");
				$this->setDefaultSort("rule_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{  
		 
			$collection = Mage::getModel('salesrule/rule')->getCollection();
			 
			 $collection->getSelect()
	 
			 ->where('main_table.rule_type = 1')	;
			
			//	echo $collection->getSelect();
				$this->setCollection($collection);
				$collection->getSelect();
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
			
			$this->addColumn("code", array(
					"header" => Mage::helper("voucher")->__("Voucher Code"),
					"align" =>"left",
					"width" => "50px",
					//  "type" => "number",
					"index" => "code",
			));
				$this->addColumn("description", array(
				"header" => Mage::helper("voucher")->__(" Voucher Description"),
				"align" =>"left",
				"width" => "100px",
			  //  "type" => "number",
				"index" => "description",
				));
                
				 
				
				
				$this->addColumn("created_at", array(
						"header" => Mage::helper("voucher")->__("Start Date"),
						"align" =>"left",
						"width" => "50px",
						'type'   => 'datetime',
						"index" => "created_at",
						'sortable' => true,
				));
				
				$this->addColumn('from_date',array(
						'header' =>  Mage::helper("voucher")->__('End Date'),
						'type' => 'datetime',
						'sortable' => true,
						 'index'  =>'from_date',
						'width' => 190,
						 
				));
				
				$this->addColumn("uses_per_customer", array(
						"header" => Mage::helper("voucher")->__("Uses Per Customer"),
						"align" =>"left",
						"width" => "50px",
						'sortable' => true,
						//  "type" => "number",
						"index" => "uses_per_customer",
				));
				$this->addColumn("uses_per_coupon", array(
						"header" => Mage::helper("voucher")->__("Uses Per Coupon"),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						//  "type" => "number",
						"index" => "uses_per_coupon",
				));
				$this->addColumn('Voucher Amount',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Amount'),
						'type' => 'number',
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						'width' => 100,
							
				));
				 
				$this->addColumn('max_discount_amount',array(
						'header' =>  Mage::helper("voucher")->__('Maximum Discount Amount Permitted'),
						'type' => 'number',
						"align" =>"left",
						'sortable' => true,
						'index'  =>'max_discount_amount',
						'width' => '20px',
							
				));
				 
				$this->addColumn("is_active", array(
						"header" => Mage::helper("voucher")->__("Active"),
						'type'    => 'options',
						'options' => array('1' => 'Yes', '0' => 'No'),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						"index" => "is_active",
				));
				$this->addColumn("times_used", array(
						"header" => Mage::helper("voucher")->__("UsedCount"),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						"index" => "times_used",
				));	
				
				
				
			$this->addExportType('*/*/exportCsvAdminvc', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcelAdminvc', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return '#';
		}


		

}
