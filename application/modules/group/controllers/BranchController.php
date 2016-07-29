<?php
class Group_BranchController extends Zend_Controller_Action {
	//const REDIRECT_URL = '/group/index';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbBranch();
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
			
			$rs_rows= $db->getAllBranchInfo($search);
			$list = new Application_Form_Frmtable();
			$collumns = array("Company Name","Branch","Phone","Email","Fax","Commune","Distric","Province","Status");
			$link=array(
					'module'=>'group','controller'=>'Branch','action'=>'edit',
			);
			
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('company'=>$link,'branch'=>$link,'phone'=>$link,'email'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
	}
	public function addAction(){
		$db = new Group_Model_DbTable_DbBranch();
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				try{
					
				 if(isset($data['save_new'])){
				 	//print_r($data);exit();
					$id= $db->addBranch($data);
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
				else{
					$id= $db->addBranch($data);
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS", "/group/branch");
				}
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->company = $db->getCompanyName();
		$this->view->branch = $db->getNewCustomerId();
		$this->view->province = $db->getAllProvince();
		$this->view->status = $db->getStatus();
	}
	public function editAction(){
		$db = new Group_Model_DbTable_DbBranch();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try{
					$db->updateBranch($data);
					Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS", "/group/branch");
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->branchinfo = $db->getBranchByID($id);
		$this->view->company = $db->getCompanyName();
		//$this->view->branch = $db->getNewCustomerId();
		$this->view->province = $db->getAllProvince();
		$this->view->status = $db->getStatus();
	}
	function viewAction(){
		$id = $this->getRequest()->getParam("id");
		$db = new Group_Model_DbTable_DbClient();
		$this->view->client_list = $db->getClientDetailInfo($id);
	}
	public function addNewclientAction(){//ajax
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$_data['status']=1;
			$id = $db->addClient($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function getgroupcodeAction(){
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getGroupCodeBYId($data);
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function getclientcodeAction(){
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getClientCode($data);
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function getclientinfoAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getClientDetailInfo($data);
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function getclientcollateralAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getClientCallateralBYId($data['client_id']);
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function insertDistrictAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_district = new Other_Model_DbTable_DbDistrict();
			$district=$db_district->addDistrictByAjax($data);
			print_r(Zend_Json::encode($district));
			exit();
		}
	}
	function insertcommuneAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_commune = new Other_Model_DbTable_DbCommune();
			$commune=$db_commune->addCommunebyAJAX($data);
			print_r(Zend_Json::encode($commune));
			exit();
		}
	}
	function addVillageAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_village = new Other_Model_DbTable_DbVillage();
			$village=$db_village->addVillage($data);
			print_r(Zend_Json::encode($village));
			exit();
		}
	}
	function insertDocumentTypeAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['status']=1;
			$data['display_by']=1;
			//$data['type']=24;
			
			$db = new Other_Model_DbTable_DbLoanType();
			$id = $db->addViewType($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function insertClientAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db=new Group_Model_DbTable_DbClient();
			$row=$db->addIndividaulClient($data);
			print_r(Zend_Json::encode($row));
			exit();
		}
	
	}
	function getclientnumberbybranchAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$dataclient=$db->getAllClientNumber($data['branch_id']);
			//array_unshift($dataclient, array('id' => "-1",'branch_id'=>$data['branch_id'],'name'=>'---Add New Client---') );
			print_r(Zend_Json::encode($dataclient));
			exit();
		}
	}
	function getclientbybranchAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			 $data = $this->getRequest()->getPost();
			 $db = new Application_Model_DbTable_DbGlobal();
             $dataclient=$db->getAllClient($data['branch_id']);
             array_unshift($dataclient, array('id' => "-1",'branch_id'=>$data['branch_id'],'name'=>'---Add New Client---') );
			 print_r(Zend_Json::encode($dataclient));
			exit();
		}
	
	}
	function getGroupclientbybranchAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$dataclient=$db->getAllClientGroup($data['branch_id']);
			array_unshift($dataclient, array('id' => "-1",'branch_id'=>$data['branch_id'],'name'=>'---Add New Client---') );
			print_r(Zend_Json::encode($dataclient));
			exit();
		}
	}
	function getGoupCodebybranchAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$dataclient=$db->getAllClientGroupCode($data['branch_id']);
			array_unshift($dataclient, array('id' => "-1",'branch_id'=>$data['branch_id'],'name'=>'---Add New Client---') );
			print_r(Zend_Json::encode($dataclient));
			exit();
		}
	}
	
}

