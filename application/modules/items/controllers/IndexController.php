<?php
class Items_indexController extends Zend_Controller_Action {
	private $activelist = array('áž˜áž·áž“áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹', 'áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹');
	const REDIRECT_URL_ADD ='/items/index/add';
	const REDIRECT_URL_ADD_CLOSE ='/items/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_make = new Items_Model_DbTable_DbVehicle();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
				$this->view->search_status=$search;
				//print_r($search);exit();
			}
			else{
				$search = array(
						'status_search' =>-1,
						'adv_search' 	=> '',
						'make'			=> -1,
				//		'model'=> -1,
				// 		'submodel'=> -1,
						
				);
			}
			$rows=$db_make->getAllproduct($search);
			$glClass = new Application_Model_GlobalClass();
			$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("ITEM_NO","ITEM_NAME","PRICE","MANAGE_CATEGORY","STATUS");
			$link=array(
					'module'=>'items','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rows,array('pro_no'=>$link,'pro_name_kh'=>$link,'pro_name_en'=>$link,'bar_code'=>$link,'price'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		$this->view->rs = $rows;
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllItemCat();
		//array_unshift($model, array ( 'id' => -1, 'name' => 'Selected Category') );
		$this->view->all_make=$model;
		$form=new Items_Form_FrmSearchInfo();
		$form=$form->FrmDepartment();
		Application_Model_Decorator::removeAllDecorator($form);
		$this->view->form_search=$form;
		
	}
	public function addAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try {
				$db_make = new Items_Model_DbTable_DbVehicle();
				if(isset($data['save_new'])){
					$db_make->addProduct($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_make->addProduct($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$db=new Items_Model_DbTable_DbVehicle();
		$this->view->porid = $db->getProID();
		$this->view->status = $db->getStatus();
		$model= $db->getUnitOption();
		array_unshift($model, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->unit=$model;
		
		$sup=$db->getSupplier();
		array_unshift($sup, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->supplier =$sup; 
		
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllItemCat();
		array_unshift($model, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->all_make=$model;
		//select supplier id
		$db_cus = new Application_Model_DbTable_DbGlobal();
		$this->view->su_no = $db_cus->getNewSupplierId();
	}
	function editAction(){
		$id=$this->getRequest()->getParam('id');
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				$db_make = new Items_Model_DbTable_DbVehicle();
				if(isset($data['save_close'])){
					$db_make->updateProduct($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$db=new Items_Model_DbTable_DbVehicle();
		$this->view->product =$db->getProductByid($id);
		
		
		$this->view->status = $db->getStatus();
		$model= $db->getUnitOption();
		array_unshift($model, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->unit=$model;
		
		$sup=$db->getSupplier();
		array_unshift($sup, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->supplier =$sup; 
		
		$this->view->item_supplier = $db->getItemSupplier($id);
		
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllItemCat();
		$this->view->all_make=$model;
		
	}
	function addSubModelAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Items_Model_DbTable_DbVehicle();
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
	
	function addSupplierAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Supplier_Model_DbTable_DbClient();
			$id = $db->addSupplierAjax($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function addMeasureAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Supplier_Model_DbTable_DbClient();
			$id = $db->addMeasureAjax($_data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	 
	function getItemAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Items_Model_DbTable_DbVehicle();
			$row = $db->getItem($data['name'],$data["cat_id"],$data["mea_id"]);
			if(!empty($row)){
				$rs=1;
			}else{
				$rs=0;
			}
			print_r(Zend_Json::encode($rs));
			exit();
		}
	}
	
	
}

