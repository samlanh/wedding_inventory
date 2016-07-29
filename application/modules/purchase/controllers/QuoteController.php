<?php
class Order_quoteController extends Zend_Controller_Action {
	private $activelist = array('áž˜áž·áž“áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹', 'áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹');
	const REDIRECT_URL_ADD ='/order/quote/add';
	const REDIRECT_URL_ADD_CLOSE ='/quote/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_make = new Order_Model_DbTable_DbQuote();
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
			$rows=$db_make->getAllQuote($search);
			$glClass = new Application_Model_GlobalClass();
			$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("QUOTE NO","CUSTOMER","DATE CEREMONY","ADDRESS","TABLE_FOR_WEDDING","TABLE_FOR_BREAKFAST","TABLE_FOR_LUNCH","TABLE_FOR_DINNER","TOTAL_AMOUNT","STATUS");
			$link=array(
					'module'=>'order','controller'=>'quote','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(1, $collumns, $rows,array('quot_code'=>$link,'customer'=>$link,'date_ceremony'=>$link));
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
		$db_make = new Order_Model_DbTable_DbQuote();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				if(isset($data['save_new'])){
					$db_make->addQuoteNew($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_make->addQuoteNew($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				//print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->quote_id = $db_make->getQuoteID();
		
		$this->view->service = $db_make->getAllService();
		
		$this->view->status = $db_make->getStatus();
		$db = new Application_Model_DbTable_DbGlobal();
		
		$this->view->food = $db->getFood();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
	}
	
	public function copyAction(){
		$id=$this->getRequest()->getParam('id');
		$db_make = new Order_Model_DbTable_DbQuote();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				
				if(isset($data['save_close'])){
					$db_make->addQuote($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}elseif(isset($data['save_new'])){
					$db_make->addQuote($data);
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
		$this->view->quote_id = $db_make->getQuoteID();
		
		$this->view->food = $db->getFood();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
	}
	
	public function addsAction(){
		$db_order = new Order_Model_DbTable_DbOrder();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				if(isset($data['save_new'])){
					$db_order->addQuoteNew($data);
					//Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_order->addQuoteNew($data);
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
		$db_make = new Order_Model_DbTable_DbQuote();
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				
				if(isset($data['save_close'])){
					$db_make->updateQuoteNew($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}elseif(isset($data['save_new'])){
					$db_make->updateQuoteNew($data);
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
		
// 		$this->view->quote_wedding = $db_make->getQuoteDetailByid($id,1);
// 		$this->view->quote_breakfast = $db_make->getQuoteDetailByid($id,2);
// 		$this->view->quote_lunch = $db_make->getQuoteDetailByid($id,3);
// 		$this->view->quote_dinner = $db_make->getQuoteDetailByid($id,4);
		
		$this->view->quote_wedding = $db_make->getQuoteOrderDetail($id,1);
		$this->view->quote_breakfast = $db_make->getQuoteOrderDetail($id,2);
		$this->view->quote_lunch = $db_make->getQuoteOrderDetail($id,3);
		$this->view->quote_dinner = $db_make->getQuoteOrderDetail($id,4);
		$this->view->quote_service = $db_make->getQuoteOrderDetail($id,5);
		
		$this->view->quote = $db_make->getQuoteOrderById($id);
		
		$this->view->food = $db->getFood();
		$this->view->Customer_name = $db->getCustomer(1);
		$this->view->Customer_code = $db->getCustomer(2);
	}
	
	function mergeAction(){
		$id = $this->getRequest()->getParam("id");
		$db = new Order_Model_DbTable_DbQuote();
		$db->mergerQuote($id);
		Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
	}
	function addSubModelAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Order_Model_DbTable_DbQuote();
			$id = $_dbmodel->addSubModelajx($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function addMakeAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Application_Model_DbTable_DbGlobal();
			$id = $_dbmodel->ajaxaddMake($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
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
	function getCeremonyDateAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getCeremonyDate($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getAddressAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Order_Model_DbTable_DbQuote();
			$row = $db->getAddress($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
}

