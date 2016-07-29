<?php
class Other_SlideshowController extends Zend_Controller_Action {
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
				$db = new Other_Model_DbTable_DbSlideshow();
				$rs= $db->getAllSLIDE($search);
				$list = new Application_Form_Frmtable();
				$collumns = array("Title","Ordering","Photo","Status",);
				$link=array(
						'module'=>'other','controller'=>'slideshow','action'=>'edit',
				);
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
			$this->view->list=$list->getCheckList(0, $collumns, $rs,array('title'=>$link,'caption'=>$link));
			$this->view->rows = $rs;	
		}
	function addAction()
	{
		if($this->getRequest()->isPost()){//check condition return true click submit button
			$_data = $this->getRequest()->getPost();
			//print_r($_data);exit();
			$_dbmodel = new Other_Model_DbTable_DbSlideshow();
			try {
				$_dbmodel->addsledeshow($_data);
				if(!empty($_data['save_new'])){
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}else{
					Application_Form_FrmMessage::Sucessfull(("INSERT_SUCCESS"),self::REDIRECT_URL . "/job/index");
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
		$_db = new Other_Model_DbTable_DbSlideshow();
		if($this->getRequest()->isPost()){//check condition return true click submit button
			$_data = $this->getRequest()->getPost();
			try {
				$_db->updateSlideshow($_data);
				Application_Form_FrmMessage::Sucessfull(("EDIT_SUCCESS"),self::REDIRECT_URL . "/slideshow");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("EDIT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$id = $this->getRequest()->getParam("id");
		$this->view->row = $_db->getSlidshowById($id);
	}
}

