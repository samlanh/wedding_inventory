<?php
class Group_provinceController extends Zend_Controller_Action {
	const REDIRECT_URL = '/group/province';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbProvince();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				$search = array(
						'title' => $formdata['title'],
						'status_search'=>$formdata['status_search'],
						);
			}
			else{
				$search = array(
					'title' => '',
					'status_search' => -1,
				);
			}
			
			$rs_rows= $db->getAllProvince($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("Province Name","Status");
			$link=array(
					'module'=>'group','controller'=>'province','action'=>'edit',
			);
			
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('province_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
// 		$frm = new Location_Form_FrmSearch();
// 		$frm =$frm->search();
// 		Application_Model_Decorator::removeAllDecorator($frm);
// 		$this->view->frm_search = $frm;
	}
	public function addAction(){
		$db = new Group_Model_DbTable_DbProvince();
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
			
				try{
				 if(isset($data['save_new'])){
					$id= $db->addProvince($data);
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
				else{
					$id= $db->addProvince($data);
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS", "/group/province");
				}
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->status = $db->getStatus();

		
	}
	public function editAction(){
		$db = new Group_Model_DbTable_DbProvince();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try{
					$db->updateProvince($data);
					Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS", "/group/province");
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->province = $db->getProvinceByID($id);
	$this->view->status = $db->getStatus();
		
	}
	
	
}

