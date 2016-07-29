<?php
class Category_indexController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL='/category';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Category_Model_DbTable_DbCategory();
			$rs_rows = $db->getAllcategory();
				
			$list = new Application_Form_Frmtable();
			$collumns = array("Category Name","Parent","Description","Create Date","Status","User");
			$link=array(
					'module'=>'category','controller'=>'index','action'=>'edit',
			);
				
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('category_name'=>$link,'parent'=>$link,'description'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function addAction(){
		$db =new Category_Model_DbTable_DbCategory();
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			try{
				$db->addCategory($data);
				if(!empty($data['save_new'])){
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS",self::REDIRECT_URL . '/index/add');
				}else{
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS",self::REDIRECT_URL . '/index');
				}
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->rs = $db->getAllcategory();
		$this->view->category = $db->getCategory();
		$this->view->status = $db->getStatus();
		
	}
	public function editAction(){
		$id = $this->getRequest()->getParam('id');
		$db =new Category_Model_DbTable_DbCategory();
		if($this->getRequest()->isPost()){
		try{
				$data = $this->getRequest()->getPost();
				$db->editCategory($data, $id);
				Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS",self::REDIRECT_URL . '/index');
			}catch(Exception $e){
				Application_Form_FrmMessage::message("EDIT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
			$this->view->rs = $db->getAllcategoryByID($id);
			$this->view->category = $db->getCategory();
			$this->view->status = $db->getStatus();
	}

	
}

