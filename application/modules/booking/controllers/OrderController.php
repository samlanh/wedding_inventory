<?php
class Booking_orderController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Booking_Model_DbTable_DbOrder();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				//print_r($formdata);exit();
				$search = array(
						'search' => $formdata['search'],
						'from_date'=>$formdata['from_date'],
						'end_date' => $formdata['end_date'],
						'company'=>$formdata['company'],
						'pending'=>$formdata['pending'],
				);
				$this->view->txtpending = $formdata['pending'];
				$this->view->txtcompany = $formdata['company'];
				$this->view->txtsearch = $formdata['search'];
				$this->view->from_date = $formdata['from_date'];
				$this->view->end_date = $formdata['end_date'];
			}
			else{
				$search = array(
						'search' => '',
						'from_date' => date('Y-m-d'),
						'end_date' => date('Y-m-d'),
						'company' => -1,
						'pending'=>-1,
				);
			}
			
			$rs_rows = $db->getCusOrder($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("Inovice No","Customer Name","Order Date","Company","Branch Name","Total","Book","Balance Due","Return","Pending");
			$link=array(
					'module'=>'booking','controller'=>'order','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('invoice_no'=>$link,'name'=>$link,));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$form = new Booking_Form_FrmSearchBooking();
		$frm =$form->FormSearch();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
		
		$this->view->partner = $db->getAllPartner();
	}
	public function addAction(){
		$db = new Booking_Model_DbTable_DbOrder();
		
		if($this->getRequest()->isPost()){
			try{
				$data=$this->getRequest()->getPost();
				$db->addCustomer($data);
				Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/booking/order/add");
			}catch (Exception $e){
					Application_Form_FrmMessage::message("Application Error");
					Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$this->view->invoice = $db->getNewIvoiceID();
		$cus = $db->getAllCustomer();
		array_unshift($cus, array ( 'id' => -1, 'name' => 'Add Customer'));
		$this->view->cus = $cus;
		
		$branch = $db->getAllBranch();
		array_unshift($branch, array ( 'id' => -1, 'name' => 'Add Branch'));
		$this->view->branch = $branch;
		
		$this->view->cate = $db->getCategory();
		$this->view->pro =$db->getProduct();
		$this->view->company = $db->getAllCom();
		//$this->view->province = $db->getAllprovince();
		
		$this->view->newId=$db->getNewCustomerId();
		
		$db_client = new Group_Model_DbTable_DbClient();
		$this->view->province = $db_client->getProvince();
	}
	public function editAction(){
		$id = $this->getRequest()->getParam('id');
		$db = new Booking_Model_DbTable_DbOrder();
		if($this->getRequest()->isPost()){
			try{
				$data=$this->getRequest()->getPost();
				$data['id']=$id;
				$db->updateCustomer($data);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS","/booking/order");
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		
		$this->view->getOrder = $db->getOrderbyID($id);
		$this->view->getorderdetail =$db->getOrderDetailID($id);
		
		$this->view->invoice = $db->getNewIvoiceID();
		$cus = $db->getAllCustomer();
		array_unshift($cus, array ( 'id' => -1, 'name' => 'Add Customer'));
		$this->view->cus = $cus;
		
		$branch = $db->getAllBranch();
		array_unshift($branch, array ( 'id' => -1, 'name' => 'Add Branch'));
		$this->view->branch = $branch;
		
		$this->view->cate = $db->getCategory();
		$this->view->pro =$db->getProduct();
		$this->view->company = $db->getAllCom();
		//$this->view->province = $db->getAllprovince();
		
		$this->view->newId=$db->getNewCustomerId();
		
		$db_client = new Group_Model_DbTable_DbClient();
		$this->view->province = $db_client->getProvince();
	}
	function getCusAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbOrder();
			$cus = $db->getAllCustomerById($data['to_controll']);
			print_r(Zend_Json::encode($cus));
			exit();
		}
	}
	function getBranchAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbOrder();
			$cus = $db->getAllCustomerById($data['to_branch']);
			print_r(Zend_Json::encode($cus));
			exit();
		}
	}
	
	function getCusOrderAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbOrder();
			$cus = $db->getCustomerOrderInfoById($data['to_controll']);
			print_r(Zend_Json::encode($cus));
			exit();
		}
	}
	function getBranchOrderAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbOrder();
			$cus = $db->getCustomerOrderInfoById($data['to_branch']);
			print_r(Zend_Json::encode($cus));
			exit();
		}
	}
	function getProAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Booking_Model_DbTable_DbOrder();
			$cus = $db->getProductsByID($data['to_pro']);
			print_r(Zend_Json::encode($cus));
			exit();
		}
	}
	function getDistrictAction(){
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getDistrict($data['province_id']);
			//array_unshift($code, array ( 'id' => -1,'name' => 'បន្ថែមថ្មី'));
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function addCustomerAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Booking_Model_DbTable_DbOrder();
			$id = $_dbmodel->ajaxaddCustomer($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function addBranchAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Booking_Model_DbTable_DbOrder();
			$id = $_dbmodel->ajaxaddBranch($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
}

