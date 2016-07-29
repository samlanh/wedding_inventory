<?php
class Food_CategoryController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL_ADD ='/food/category/add';
	const REDIRECT_URL_ADD_CLOSE ='/food/category/';
	protected $tr;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db_make = new Food_Model_DbTable_DbCategory();
		$rows=$db_make->getAllCategory();
        try{
        $list = new Application_Form_Frmtable();
        $collumns = array("NAME EN","NAME KH","IMAGE FEATURE","STATUS");
        $link=array(
        		'module'=>'food','controller'=>'category','action'=>'edit',
        );
        $this->view->rows = $rows;
        $this->view->list=$list->getCheckList(0, $collumns, $rows,array('name_en'=>$link,'name_kh'=>$link));
        }catch (Exception $e){
        	Application_Form_FrmMessage::message("Application Error");
        	Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
        }
        
	}
	
	public function addAction(){
		if($this->getRequest()->isPost()){
			$data= $this->getRequest()->getPost();
// 			print_r($data);exit();    
			try {
				$db_make = new Food_Model_DbTable_DbCategory();
				if(!empty($data['save_new'])){
					$db_make->addFoodCategory($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else{
					$db_make->addFoodCategory($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$db = new Application_Model_DbTable_DbGlobal();
		$status=$db->getViews(2);
		$this->view->status_view=$status;
	}
	public function editAction(){
		if($this->getRequest()->isPost()){
			$data= $this->getRequest()->getPost();
				//print_r($data);exit();
			try {
				$db_make = new Food_Model_DbTable_DbCategory();
				if(!empty($data['save_close'])){
					$db_make->updateFoodCategory($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$id=$this->getRequest()->getParam('id');
		$db_make = new Food_Model_DbTable_DbCategory();
		$rows=$db_make->getFoodCategoryByid($id);
        $this->view->row=$rows;
        $db = new Application_Model_DbTable_DbGlobal();
        $status=$db->getViews(2);
        $this->view->status_view=$status;
	}
}

