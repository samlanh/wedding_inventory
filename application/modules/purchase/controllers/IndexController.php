<?php
class Purchase_indexController extends Zend_Controller_Action {
	private $activelist = array('áž˜áž·áž“áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹', 'áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹');
	const REDIRECT_URL_ADD ='/purchase/index/add';
	const REDIRECT_URL_ADD_CLOSE ='/purchase/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_make = new Purchase_Model_DbTable_DbPurchase();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				$search = array(
						'title' 		=> 	$formdata['adv_search'],
						'status_search'	=>	$formdata['search_status'],
						'start_date'	=>	$formdata["start_date"],
						'end_date'		=>	$formdata["end_date"],
				);
			}
			else{
				$search = array(
					'title' 		=> '',
					'status_search' => -1,
					'start_date'	=>	date("Y-m-d"),
					'end_date'		=>	date("Y-m-d"),
				);
			}
			
			$this->view->search = $search;
			$rows=$db_make->getAllPurchase($search);
			$glClass = new Application_Model_GlobalClass();
			$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("PURCHASE_NO","CUSTOMER","PHONE","EMAIL","ADDRESS","DATE_CEREMONY","SUPPLIER","STATUS");
			$link=array(
					'module'=>'purchase','controller'=>'index','action'=>'editbysulong',
			);
			$this->view->list=$list->getCheckList(1, $collumns, $rows,array('pu_code'=>$link,'first_name'=>$link,'date_ceremony'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllFoodCat();
		//array_unshift($model, array ( 'id' => -1, 'name' => 'Selected Make') );
		$this->view->all_make=$model;
		
	}
	public function addAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		if($this->getRequest()->isPost()){
			$formdata=$this->getRequest()->getPost();
			$search = array(
					'title' => $formdata['adv_search'],
					'status_search'=>$formdata['search_status'],
					//'company'=>$formdata['company'],
					'start_date'	=>	$formdata["start_date"],
					'end_date'		=>	$formdata["end_date"],
	
			);
		}
		else{
			$search = array(
					'title' 		=> '',
					'status_search' => -1,
					//'company'		=>-1,
					'start_date'	=>	date("Y-m-d"),
					'end_date'		=>	date("Y-m-d"),
			);
		}
		$this->view->search = $search;
		$rows = $db_order->getQuoteForPurchase($search);
		$glClass = new Application_Model_GlobalClass();
		$rows = $glClass->getImgActive($rows, BASE_URL, true);
		$list = new Application_Form_Frmtable();
		$collumns = array("QOUTE_NO","CUSTOMER","PHONE","EMAIL","ADDRESS","DATE_CEREMONY","STATUS");
		$link=array(
				'module'=>'purchase','controller'=>'index','action'=>'addbysulong',
		);
		$this->view->list=$list->getCheckList(0, $collumns, $rows,array('quot_code'=>$link,'first_name'=>$link,'phone'=>$link,'date_ceremony'=>$link));
	
		$this->view->qute_code = $db_order-> getQuoteCode();
		$this->view->supplier = $db_order->getSupplier();
		$this->view->food = $db_order->getFood();
	}
	public function addbysuAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		$db_make = new Order_Model_DbTable_DbQuote();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			if(isset($data["search"])){
				$su_id = $data["supplier_name"];
			}elseif(isset($data["save_close"])){
				
			}elseif (isset($data["save_new"])){
				
			}
		}
		else{
			$su_id = -1;
		}
		//$this->view->search = $search;
		$this->view->item = $db_order->getItemsPurchaseByQuoteIdShortBySu($id,$su_id);
	
		$this->view->qute_code = $db_order-> getQuoteCode();
		$this->view->pu_id = $db_order-> getPurchaseID();
		$this->view->supplier = $db_order->getSupplier();
		$this->view->items = $db_order->getItem();
		
		$row_quote_detail = $db_order->getQuoteById($id);
		$this->view->quote_detail = $row_quote_detail;
		
		$row_addr = $db_make->getAllAddress($row_quote_detail["ce_id"]);
		$this->view->alladdr = $row_addr;
	}
	
	public function addbysulongAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		$db_make = new Order_Model_DbTable_DbQuote();
		
		if($this->getRequest()->isPost()){
			
			$data = $this->getRequest()->getPost();
			$data['id'] = $id;
// 			print_r($data);exit();
			$su_id = $data["supplier_name"];
			if(isset($data["search"])){
				$su_id = $data["supplier_name"];
			}elseif(isset($data["save_close"])){
				$db_order->addPurchaseBySuLong($data);
			}elseif (isset($data["save_new"])){
				$db_order->addPurchaseBySuLong($data);
			}
		}
		else{
			$su_id = -1;
		}
		$this->view->search = $su_id;
		$this->view->item = $db_order->getItemsPurchaseByQuoteIdShortBySu($id,$su_id);
	
		$this->view->qute_code = $db_order-> getQuoteCode();
		$this->view->pu_id = $db_order-> getPurchaseID($id);
		$this->view->supplier = $db_order->getSupplier();
		$this->view->items = $db_order->getItem();
	
		$row_quote_detail = $db_order->getQuoteById($id);
		$this->view->quote_detail = $row_quote_detail;
	
		$row_addr = $db_make->getAllAddress($row_quote_detail["ce_id"]);
		$this->view->alladdr = $row_addr;
	}
	
	public function editbysulongAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		$db_make = new Order_Model_DbTable_DbQuote();
	
		if($this->getRequest()->isPost()){
				
			$data = $this->getRequest()->getPost();
			$data['id'] = $id;
			if(isset($data["search"])){
				$su_id = $data["supplier_name"];
			}elseif(isset($data["save_close"])){
				$db_order->updatePurchaseBySuLong($data);
			}elseif (isset($data["save_new"])){
				$db_order->updatePurchaseBySuLong($data);
			}
		}
		else{
			$su_id = -1;
		}
		$this->view->search = $su_id;
		$this->view->item = $db_order->getPurchaseDetail($id,$su_id);
	
		$this->view->qute_code = $db_order-> getQuoteCode();
		$this->view->pu_id = $db_order-> getPurchaseID($id);
		$this->view->supplier = $db_order->getSupplier();
		$this->view->items = $db_order->getItem();
	
		$row_quote_detail = $db_order->getQuoteById($id);
		$this->view->quote_detail = $row_quote_detail;
	
		$row_addr = $db_make->getAllAddress($row_quote_detail["ce_id"]);
		$this->view->alladdr = $row_addr;
	}
	public function addphpajaxAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		$db_make = new Order_Model_DbTable_DbQuote();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try {
				if(isset($data['save_new'])){
					$db_order->addPurchase($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_order->addPurchase($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				//print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->item = $db_order->getItemsPurchaseByQuoteId($id);
	
		$this->view->qute_code = $db_order-> getQuoteCode();
		$this->view->pu_id = $db_order-> getPurchaseID();
		$this->view->supplier = $db_order->getSupplier();
		$this->view->items = $db_order->getItem();
		$row_quote_detail = $db_order->getQuoteById($id);
		$this->view->quote_detail = $row_quote_detail;
		
		$row_addr = $db_make->getAllAddress($row_quote_detail["ce_id"]);
		$this->view->alladdr = $row_addr;
	}
	
	public function editphpajaxAction(){
		$id = $this->getRequest()->getParam("id");
		$db_order = new Purchase_Model_DbTable_DbPurchase();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try {
				if(isset($data['save_new'])){
					$db_order->editPurchase($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_order->editPurchase($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				//print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->item = $db_order->getPurchaseOrderDetail($id);
	
		//$this->view->qute_code = $db_order-> getQuoteCode();
		//$this->view->pu_id = $db_order-> getPurchaseID();
		$this->view->supplier = $db_order->getSupplier();
		$this->view->items = $db_order->getItem();
		$this->view->quote_detail = $db_order->getPurchaseOrder($id);
	}
	function viewpurchaseAction(){
		$id = $this->getRequest()->getParam("id");
		//$db = new Report_Model_DbTable_DbPurchase();
		$db = new Purchase_Model_DbTable_DbPurchase();
	
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$su_id = $data['supplier_name'];
		}else{
			$su_id = -1;
		}
	
		$this->view->rows = $db->getPurchaseDetailById($id,$su_id);
		$this->view->supplier = $db->getSupplier();
	}
	
	public function copyAction(){
		$db_order = new Order_Model_DbTable_DbOrder();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				if(isset($data['save_new'])){
					$db_order->addOrder($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_order->addOrder($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				//print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->quote_id = $db_order->getOrderID();
	
		$this->view->status = $db_order->getStatus();
		$db = new Application_Model_DbTable_DbGlobal();
	
		$this->view->food = $db->getFood();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
	
		$this->view->QuoteNo = $db_order->getQuoteNo();
	}	
	
	function mergeAction(){
		$id=$this->getRequest()->getParam('id');
		$db_make = new Order_Model_DbTable_DbQuote();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				if(isset($data['save_close'])){
					$db_make->updateQuote($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}elseif(isset($data['save_new'])){
					$db_make->updateQuote($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD);
				}elseif(isset($data['convert_to_order'])){
					$db_make->convertToOrder($id);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->status = $db_make->getStatus();
	
		$this->view->quote_wedding = $db_make->getQuoteDetailByid($id,1);
		$this->view->quote_breakfast = $db_make->getQuoteDetailByid($id,2);
		$this->view->quote_lunch = $db_make->getQuoteDetailByid($id,3);
		$this->view->quote_dinner = $db_make->getQuoteDetailByid($id,4);
	
		$this->view->quote = $db_make->getQuoteByid($id);
	
		$this->view->food = $db->getFood();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
	}
	
	function getItemPriceAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getProductPrice($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getCustomerByIdAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$row = $db->getCustomerById($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getCustomerByQuoteCodeAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbOrder();
			$row = $db->getCustomerByQuoteCode($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getQuoteWeddingAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getQuoteDetailByid($_data["id"],1);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getQuoteBreakfastAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getQuoteDetailByid($_data["id"],2);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getQuoteLunchAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getQuoteDetailByid($_data["id"],3);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getQuoteDinnerAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getQuoteDetailByid($_data["id"],4);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getItemPurchaseAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Purchase_Model_DbTable_DbPurchase();
			$row = $db->getItemsPurchaseByQuoteId($_data["id"],4);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getMeasureAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Purchase_Model_DbTable_DbPurchase();
			$row = $db->getMeasureNameByItemId($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getFoodAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Purchase_Model_DbTable_DbPurchase();
			$row = $db->getFoodByItem($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
}

