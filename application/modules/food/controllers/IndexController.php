<?php
class Food_indexController extends Zend_Controller_Action {
	private $activelist = array('áž˜áž·áž“áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹', 'áž”áŸ’ážšáž¾â€‹áž”áŸ’ážšáž¶ážŸáŸ‹');
	const REDIRECT_URL_ADD ='/food/index/add';
	const REDIRECT_URL_ADD_CLOSE ='/food/index/';
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db_make = new Food_Model_DbTable_DbFood();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
				//print_r($search);exit();
			}
			else{
				$search = array(
						'adv_search' => '',
						'make'=> -1,
						// 					'model'=> -1,
				// 					'submodel'=> -1,
						'status_search' =>-1,
				);
			}
			$rows=$db_make->getAllFood($search);
			$glClass = new Application_Model_GlobalClass();
			$rows = $glClass->getImgActive($rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			//$list->showAddBuntton($url_new);
			$collumns = array("FOOD NO","FOOD NAME","CATEGORY","STATUS");
			$link=array(
					'module'=>'food','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(1, $collumns, $rows,array('food_code'=>$link,'name_kh'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllFoodCat();
		//array_unshift($model, array ( 'id' => -1, 'name' => 'Selected Make') );
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
				$db_make = new Food_Model_DbTable_DbFood();
				if(isset($data['save_new'])){
					$db_make->addFood($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else if(isset($data['save_close'])){
					$db_make->addFood($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				//print_r($e->getMessage());exit();
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$db=new Food_Model_DbTable_DbFood();
		$this->view->porid = $db->getFoodID();
		$this->view->status = $db->getStatus();
		$this->view->unit = $db->getUnitOption();
		
		$this->view->supplier = $db->getSupplier();
		
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllFoodCat();
		array_unshift($model, array ( 'id' => -1, 'name' => 'Add New') );
		$this->view->all_make=$model;
		
		$this->view->pro = $db->getItem();
	}
	function editAction(){
		$id=$this->getRequest()->getParam('id');
		$db_make = new Food_Model_DbTable_DbFood();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
				
				if(isset($data['save_close'])){
					$db_make->updateFood($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$db=new Food_Model_DbTable_DbFood();
		$this->view->product =$db->getFoodByid($id);
		
		
		$this->view->status = $db->getStatus();
		$this->view->unit = $db->getUnitOption();
		
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllFoodCat();
		$this->view->all_make=$model;
		
		$this->view->pro = $db->getItem();
		
		$this->view->food_ingredients = $db_make->getFoodIngredients($id);
		
	}
	function copyAction(){
		$id=$this->getRequest()->getParam('id');
		$db_make = new Food_Model_DbTable_DbFood();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$data['id'] = $id;
			try{
	
				if(isset($data['save_close'])){
					$db_make->copyFood($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$db=new Food_Model_DbTable_DbFood();
		$this->view->porid = $db->getFoodID();
		$this->view->product =$db->getFoodByid($id);
	
	
		$this->view->status = $db->getStatus();
		$this->view->unit = $db->getUnitOption();
	
		$db = new Application_Model_DbTable_DbGlobal();
		$model = $db->getAllFoodCat();
		$this->view->all_make=$model;
	
		$this->view->pro = $db->getItem();
	
		$this->view->food_ingredients = $db_make->getFoodIngredients($id);
	
	}
	function addSubModelAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Food_Model_DbTable_DbFood();
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

	function getSupplierAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$db = new Food_Model_DbTable_DbFood();
			$row = $db->getSupplierByItemId($_data["id"]);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}}

