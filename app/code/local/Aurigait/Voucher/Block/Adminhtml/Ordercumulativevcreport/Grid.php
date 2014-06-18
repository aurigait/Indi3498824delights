<?php

class Aurigait_Voucher_Block_Adminhtml_Ordercumulativevcreport_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("ordercumulativevcreportGrid");
				$this->setDefaultSort("rule_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{  
		 
			$collection = Mage::getModel('salesrule/rule')->getCollection();
			 
			 $collection->getSelect()
		 
			 ->where('main_table.rule_type = 4')	;
			
				$this->setCollection($collection);
				$collection->getSelect();
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
			$this->addColumn("is_active", array(
					"header" => Mage::helper("voucher")->__("Active"),
					'type'    => 'options',
					'options' => array('1' => 'Yes', '0' => 'No'),
					"align" =>"left",
					'sortable' => true,
					"width" => "50px",
					"index" => "is_active",
			));
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
			 
		
				
				$this->addColumn("threshold_amount", array(
						"header" => Mage::helper("voucher")->__("Threshold Amount"),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						//  "type" => "number",
						"index" => "threshold_amount",
				));
				$this->addColumn("alert_threshold_amount", array(
						"header" => Mage::helper("voucher")->__("Alert Threshold Amount"),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						//  "type" => "number",
						"index" => "alert_threshold_amount",
				));
				
				$this->addColumn("discount_amount", array(
						"header" => Mage::helper("voucher")->__("Discount Amount"),
						"align" =>"left",
						"width" => "50px",
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_VoucheramountDisplay',
						
						//"index" => "discount_amount",
				)); 
				 
			
				$this->addColumn("times_used", array(
						"header" => Mage::helper("voucher")->__("UsedCount"),
						"align" =>"left",
						'sortable' => true,
						"width" => "50px",
						"index" => "times_used",
				));	
				
				
				
			$this->addExportType('*/*/exportCsvOrdercumulativevcreport', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcelOrdercumulativevcreport', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return '#';
		}


		

}
