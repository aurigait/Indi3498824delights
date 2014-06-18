<?php
class Aurigait_Voucher_Adminhtml_VoucherbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Voucher Report"));
	   $this->renderLayout();
    }
    
    
    public function welcomereportAction()
    {
    	$this->loadLayout()->_setActiveMenu("report/voucher")->_addBreadcrumb(Mage::helper("adminhtml")->__("Welcome Voucher Report"),Mage::helper("adminhtml")->__("Welcome Voucher Report"));
		$this->renderLayout();
	}
	
	public function invitivcreportAction()
	{
		$this->loadLayout()->_setActiveMenu("report/voucher")->_addBreadcrumb(Mage::helper("adminhtml")->__("Invitation Voucher Report"),Mage::helper("adminhtml")->__("Invitation Voucher Report"));
		$this->renderLayout();
	}
	
	public function adminvcreportAction()
	{
		$this->loadLayout()->_setActiveMenu("report/voucher")->_addBreadcrumb(Mage::helper("adminhtml")->__("Admin Defined Voucher Report"),Mage::helper("adminhtml")->__("Invitation Voucher Report"));
		$this->renderLayout();
	}
	public function usercumulativevcreportAction()
	{
		$this->loadLayout()->_setActiveMenu("report/voucher")->_addBreadcrumb(Mage::helper("adminhtml")->__("User Cumulative Voucher Report"),Mage::helper("adminhtml")->__("Invitation Voucher Report"));
		$this->renderLayout();
	}
	public function ordercumulativevcreportAction()
	{
		
		$this->loadLayout()->_setActiveMenu("report/voucher")->_addBreadcrumb(Mage::helper("adminhtml")->__("Order Cumulative Voucher Report"),Mage::helper("adminhtml")->__("Invitation Voucher Report"));
		$this->renderLayout();
	}
	
	
	
	/**
	 *  Export order grid to Excel XML format
	 */
	public function exportExcelWelcomeAction()
	{
		$fileName   = 'Welcome_Voucher_Report.xls';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_welcome_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
	
	
	public function exportCsvWelcomeAction()
	{
		$fileName   = 'Welcome_Voucher_Report.csv';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_welcome_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	
	
		/**
	 *  Export order grid to Excel XML format
	 */
	public function exportCsvAdminvcAction()
	{
		$fileName   = 'Admin_Voucher_Report.csv';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_adminvcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	

	public function exportExcelAdminvcAction()
	{
		$fileName   = 'Admin_Voucher_Report.xls';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_adminvcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
	
		/**
	 *  Export order grid to Excel XML format
	 */
	public function exportCsvUsercmvcAction()
	{
		$fileName   = 'User_Cumulative_Voucher_Report.csv';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_usercumulativevcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	

	public function exportExcelUsercmvcAction()
	{
		$fileName   = 'User_Cumulative_Voucher_Report.xml';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_usercumulativevcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
	
	
	/**
	 *  Export order grid to Excel XML format
	 */
	public function exportCsvAction()
	{
		$fileName   = 'Invitation_Voucher_Report.csv';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_invitivoucherreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	
	
	public function exportExcelAction()
	{
		$fileName   = 'Invitation_Voucher_Report.xml';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_invitivoucherreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
	
	
	/**
	 *  Export order grid to Excel XML format
	 */
	public function exportCsvOrdercumulativevcreportAction()
	{
		$fileName   = '0rder_Cumulative_Voucher_Report.csv';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_ordercumulativevcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}
	
	
	public function exportExcelOrdercumulativevcreportAction()
	{
		$fileName   = '0rder_Cumulative_Voucher_Report.xml';
		$grid       = $this->getLayout()->createBlock('voucher/adminhtml_ordercumulativevcreport_grid');
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}
}