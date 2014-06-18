<?php

class Aurigait_Voucher_Block_Adminhtml_Welcome_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("welcomeGrid");
				$this->setDefaultSort("entity_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{  
			$oRule = Mage::getModel('salesrule/rule')->load(2,'rule_type');
			$couponCode = ($oRule['coupon_code']);

			$collection = Mage::getModel('customer/customer')->getCollection()
			   ->addAttributeToSelect('entity_id')
			   ->addAttributeToSelect('email')
			   ->addAttributeToSelect('created_at');
			
			 $collection->getSelect()
			   ->joinLeft(array('d' => 'sales_flat_order'), 'e.entity_id = d.customer_id and d.coupon_code = "'.$couponCode.'" ', array(
			   		'orderid'       => 'increment_id',
			   		'use_satus'   => '(if(d.increment_id is NULL,"Unused","Used") )',
			   		'voucher_amount' =>'d.base_discount_amount'
			   ))
			   
				;
				//echo $collection->getSelect();
				$this->setCollection($collection);
				$collection->getSelect();
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("entity_id", array(
				"header" => Mage::helper("voucher")->__("User Id"),
				"align" =>"left",
				"width" => "50px",
			  //  "type" => "number",
				"index" => "entity_id",
				));
                
				$this->addColumn("email", array(
						"header" => Mage::helper("voucher")->__("User Email"),
						"align" =>"left",
						"width" => "50px",
						"index" => "email",
				));
				
				
				$this->addColumn("created_at", array(
						"header" => Mage::helper("voucher")->__("Issue Date"),
						"align" =>"left",
						"width" => "50px",
						'type'   => 'datetime',
						"index" => "created_at",
				));
				
				$this->addColumn('Expiry Date',array(
						'header' =>  Mage::helper("voucher")->__('Expiry Date'),
						'type' => 'datetime',
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Expirydate',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Expirydate',
						'width' => 190,
						 
				));
				
				
				$this->addColumn("use_satus", array(
						"header" => Mage::helper("voucher")->__("Status"),
						"align" =>"left",
						"width" => "50px",
						"index" => "use_satus",
				));
				$this->addColumn('Active',array(
						'header' =>  Mage::helper("voucher")->__('Active'),
						'type' => 'number',
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Activestatus',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Activestatus',
						'width' => 50,
							
				));
				$this->addColumn('Voucher Amount',array(
						'header' =>  Mage::helper("voucher")->__('Voucher Amount'),
						'type' => 'number',
						'sortable' => true,
						'renderer' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Voucheramount',
						'filter' => 'Aurigait_Voucher_Block_Adminhtml_Welcome_Renderer_Voucheramount',
						'width' => 100,
							
				));
				$this->addColumn("orderid", array(
						"header" => Mage::helper("voucher")->__("Order Id"),
						"align" =>"left",
						"width" => "50px",
						"index" => "orderid",
				));
				
				
				
			$this->addExportType('*/*/exportCsvWelcome', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcelWelcome', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return '#';
		}


		

}
