<?php
class Home_indexController extends Zend_Controller_Action {
	
	
public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
	}
	public function indexAction()
	{
// 		$this->_helper->layout()->disableLayout();
		$db= new Home_Model_DbTable_DbCountPending();
		$this->view->countPending = $db->getPendingOrder();
		$this->view->orderToday = $db->getOrderToday();
		$this->view->custmers = $db->getAllCustomer();
	}
	
}

