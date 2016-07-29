<?php
class Group_districtController extends Zend_Controller_Action {
	const REDIRECT_URL = '/group/province';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbDistrict();
			if($this->getRequest()->isPost()){
				$formdata=$this->getRequest()->getPost();
				$search = array(
						'title' => $formdata['title'],
						'status_search'=>$formdata['status_search'],
						'province_id'=>$formdata['province_id'],
						);
			}
			else{
				$search = array(
					'title' => '',
					'status_search' => -1,
					'province_id'=>-1,
				);
			}
			
			$rs_rows= $db->getAlldistrict($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("District Name","Province Name","Status");
			$link=array(
					'module'=>'group','controller'=>'district','action'=>'edit',
			);
			
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('district_name'=>$link,'province_name'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		$this->view->province = $db->getAllProvince();
	}
	public function addAction(){
		$db = new Group_Model_DbTable_DbDistrict();
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
			
				try{
				 if(isset($data['save_new'])){
					$id= $db->addDistrict($data);
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
				else{
					$id= $db->addDistrict($data);
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS", "/group/district");
				}
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->province = $db->getAllProvince();
		$this->view->status = $db->getStatus();

		
	}
	public function editAction(){
		$db = new Group_Model_DbTable_DbDistrict();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try{
					$db->updateDistrict($data);
					Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS", "/group/district");
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->district = $db->getDistrictByID($id);
		$this->view->province = $db->getAllProvince();
	$this->view->status = $db->getStatus();
		
	}
	
	
}

