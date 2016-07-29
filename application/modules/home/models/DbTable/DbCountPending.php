<?php

class Home_Model_DbTable_DbCountPending extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_order';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function getPendingOrder(){
    	$db= $this->getAdapter();
    	$sql="SELECT COUNT(o.`pending`) AS counter FROM `ldc_order`AS o WHERE o.`pending`=1 AND o.`status`=1";
    	return $db->fetchRow($sql);
    }
    public function getOrderToday(){
    	$db= $this->getAdapter();
    	 // =new Zend_Date();
    	
    	 $now = date("Y-m-d");
    	$sql="SELECT COUNT(o.`invoice_no`) AS counter FROM `ldc_order`AS o WHERE o.`status`=1 AND o.order_date="."'$now'";
    	return $db->fetchRow($sql);
    }
    public function getAllCustomer(){
    	$db= $this->getAdapter();
    	$sql="SELECT COUNT(o.`id`) AS counter FROM `ldc_customers`AS o WHERE o.`status`=1 ";
    	return $db->fetchRow($sql);
    }
	
}

