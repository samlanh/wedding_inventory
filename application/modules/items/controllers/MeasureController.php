<?php
class Items_measureController extends Zend_Controller_Action {
	const REDIRECT_URL_ADD ='/items/measure/add';
	const REDIRECT_URL_CLOSE ='/items/measure/index';
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db=new Items_Model_DbTable_DbMeasure();
		try{
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => '',
						'search_status' =>-1,
				);
			}
			$rows=$db->getMeasure($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("MEASURE NAME IN KHMER","MEASURE NAME IN ENGLISH","DESCRIPTION","STATUS");
			$link=array(
					'module'=>'items','controller'=>'measure','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns, $rows,array('measure_name_kh'=>$link,'measure_name_en'=>$link,'status'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	
	public function addAction(){
		$db=new Items_Model_DbTable_DbMeasure();
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try{
				if(isset($data['save_new'])){
					$db->addMeasure($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}
				else if(isset($data['save_close'])){
					$db->addMeasure($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_CLOSE);
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$this->view->status = $db->getStatus();
	
	}
	public function editAction(){
		$db=new Items_Model_DbTable_DbMeasure();
		$id=$this->getRequest()->getParam('id');
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			try{
				$data['id']=$id;
					$db->updateMeasure($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("EDIT_SUCCESS"),self::REDIRECT_URL_CLOSE);
			}catch (Exception $e){
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$row=$db->getMeasureByID($id);
		$this->view->row=$row;
		$this->view->status = $db->getStatus();
	}
}

