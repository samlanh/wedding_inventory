<?php
class Other_PartnerController extends Zend_Controller_Action {
	const REDIRECT_URL='/other';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			if($this->getRequest()->isPost()){
				$_data=$this->getRequest()->getPost();
			}else{
				$search= array(
						'title'=>''
						);
			}
				$db = new Other_Model_DbTable_DbPartner();
				$rs= $db->getAllPartner($search);
				$list = new Application_Form_Frmtable();
				$collumns = array("Title","Photo","Show on Footer","Status");
				$link=array(
						'module'=>'other','controller'=>'partner','action'=>'edit',
				);
				$this->view->list=$list->getCheckList(0, $collumns, $rs,array('title'=>$link));
				$this->view->rows = $rs;
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
				
		}
	function addAction()
	{
		if($this->getRequest()->isPost()){//check condition return true click submit button
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Other_Model_DbTable_DbPartner();
			try {
				$_dbmodel->addPartner($_data);
				if(!empty($_data['save_new'])){
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}else{
					Application_Form_FrmMessage::Sucessfull(("INSERT_SUCCESS"),self::REDIRECT_URL . "/partner/index");
				}
				}catch (Exception $e) {
						Application_Form_FrmMessage::message("INSERT_FAIL");
						$err =$e->getMessage();
						Application_Model_DbTable_DbUserLog::writeMessageError($err);
					}
				}
	}
	function editAction()
	{
		$_db = new Other_Model_DbTable_DbPartner();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){//check condition return true click submit button
			$_data = $this->getRequest()->getPost();
			try {
				$_data['id']=$id;
				$_db->updatePartner($_data);
				Application_Form_FrmMessage::Sucessfull(("EDIT_SUCCESS"),self::REDIRECT_URL . "/partner");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("EDIT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$this->view->row = $_db->getPartnerById($id);
	}
}

