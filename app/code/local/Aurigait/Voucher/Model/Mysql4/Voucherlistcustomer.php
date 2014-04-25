<?php
class Aurigait_Voucher_Model_Mysql4_Voucherlistcustomer extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("voucher/voucherlistcustomer", "id");
    }
}