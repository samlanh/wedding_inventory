<?php
class Order_indexController extends Zend_Controller_Action {
	private $activelist = array('áž˜áž·áž“áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹', 'áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹');
	const REDIRECT_URL_ADD ='/order/index/add';
	const REDIRECT_URL_ADD_CLOSE ='/order/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_make = new Order_Model_DbTable_DbOrder();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				$search = array(
						'title' => $formdata['adv_search'],
						'search_status'=>$formdata['search_status'],
						//'company'=>$formdata['company'],
						'start_date'	=>	$formdata["start_date"],
						'end_date'		=>	$formdata["end_date"],
						
						);
			}
			else{
				$search = array(
					'title' 		=> '',
					'search_status' => -1,
					//'company'		=>-1,
					'start_date'	=>	date("Y-m-d"),
					'end_date'		=>	date("Y-m-d"),
				);
			}
			$this->view->search = $search;
			$rows=$db_make->getAllOrder($search);
			$glClass = new Application_Model_GlobalClass();
			$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("ORDER_NO","CUSTOMER_NAME","PHONE","ADDRESS","CEREMONY_DATE","TOTAL_AMOUNT","STATUS");
			$link=array(
					'module'=>'order','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(1, $collumns, $rows,array('order_code'=>$link,'first_name'=>$link,'date_ceremony'=>$link));
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
		$db_order = new Order_Model_DbTable_DbOrder();
		$db_make = new Order_Model_DbTable_DbQuote();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				if(isset($data['save_new'])){
					$db_order->addOrder($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_order->addOrder($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
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
		$this->view->food_cat = $db_make->getFoodCat();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
		
		$this->view->service = $db_make->getAllService();
		$this->view->QuoteNo = $db_order->getQuoteNo();
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
	function editAction(){
		$id=$this->getRequest()->getParam('id');
		$db_make = new Order_Model_DbTable_DbOrder();
		$db_order = new Order_Model_DbTable_DbQuote();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				
				if(isset($data['save_close'])){
					$db_make->updateOrder($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}elseif(isset($data['save_new'])){
					$db_make->updateOrder($data);
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
		$this->view->QuoteNo = $db_make->getQuoteNo();
		
		$this->view->order_wedding = $db_make->getOrderDetailByid($id,1);
		$this->view->order_breakfast = $db_make->getOrderDetailByid($id,2);
		$this->view->order_lunch = $db_make->getOrderDetailByid($id,3);
		$this->view->order_dinner = $db_make->getOrderDetailByid($id,4);
		$this->view->order_service = $db_make->getOrderDetailByid($id,5);
		
		$this->view->order = $db_make->getOrderByid($id);
		
		$this->view->service = $db_order->getAllService();
		
		$this->view->food = $db->getFood();
		$this->view->food_cat = $db_order->getFoodCat();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
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
	
	function getQuoteServiceAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getQuoteDetailByid($_data["id"],5);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	
	function getCeremonyAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getCeremony($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
}

