<?php
class Report_indexController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
  function indexAction(){
  	
  }
  /// Order Report
 function rptQuoteAction()
 {
	$db = new Report_Model_DbTable_DbQuote();
	
	$this->view->rows = $db->getQuote();
 }

 function viewindAction(){
 	$id = $this->getRequest()->getParam("id");
 	$db = new Report_Model_DbTable_DbQuote();
 	$rows_quote = $db->getQuoteById($id);
 	//$rows_connect = $db->getQuoteConnect($id, $type);
 	$this->view->rows_quote = $rows_quote;
 }
 
 function viewmergeAction(){
 	$id = $this->getRequest()->getParam("id");
 	$db = new Report_Model_DbTable_DbQuote();
 	$rows_quote = $db->getQuoteByMergeId($id);
 	//$rows_connect = $db->getQuoteConnect($id, $type);
 	$this->view->rows_quote = $rows_quote;
 }
 
 /// Purchase Report
 
 function rptpurchaseAction(){
 	$db = new Report_Model_DbTable_DbPurchase();
 	
 	$this->view->rows = $db->getPurchase();
 }
 
 function viewpurchaseAction(){
 	$id = $this->getRequest()->getParam("id");
 	$db = new Report_Model_DbTable_DbPurchase();
 	$db_order = new Purchase_Model_DbTable_DbPurchase();
 	
 	if($this->getRequest()->isPost()){
 		$data = $this->getRequest()->getPost();
 		$su_id = $data['supplier_name'];
 	}else{
 		$su_id = -1;
 	}
 
 	$this->view->rows = $db->getPurchaseDetailById($id,$su_id);
 	$this->view->supplier = $db_order->getSupplier();
 }
 function rptFoodAction(){
 	$db = new Report_Model_DbTable_DbPurchase();
 	$this->view->rows = $db->getPurchase();
 	$gat=new Report_Model_DbTable_DbFoodGategory();
 	$this->view->gategory=$gat->getGategory(); 
 }
 function rptItemFoodAction(){
 	$db = new Report_Model_DbTable_DbPurchase();
 	$this->view->rows = $db->getPurchase();
 	$gat=new Report_Model_DbTable_DbFoodGategory();
 	$this->view->gategory=$gat->getGategory();
 	$id=$this->getRequest()->getParam('id');
 	$this->view->row_item=$gat->getItermByFoodId($id);
 	
 }
}

