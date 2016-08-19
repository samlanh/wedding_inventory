<?php
class Group_indexController extends Zend_Controller_Action {
	const REDIRECT_URL = '/group/index';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		try{
			$db = new Group_Model_DbTable_DbClient();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
					'title' 		=> '',
					'status_search' => -1,
					'start_date'	=>	date("Y-m-01"),
					'end_date'		=>	date("Y-m-d"),
				);
			}
			
			$rs_rows= $db->getAllCustomer($search);
			//print_r($rs_rows);exit();
			$row=new Application_Model_GlobalClass();
			$rs_rows=$row->getImgActive($rs_rows, BASE_URL);
			$list = new Application_Form_Frmtable();
			$collumns = array("CUSTOMER_NAME","Tel","EMAIL","CUSTOMER_ADDRESS","CEREMONY_DATE","ADDRESS_ONE","ADDRESS_TWO","ADDRESS_THREE","METTING","STATUS");
			$link=array(
					'module'=>'group','controller'=>'index','action'=>'edit',
			);
			
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('first_name'=>$link,'phone'=>$link,'email'=>$link,));
			$db_branch = new Group_Model_DbTable_DbBranch();
			$this->view->company = $db_branch->getCompanyName();
			
			$this->view->search = $search;
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		$form=new Items_Form_FrmSearchInfo();
		$form=$form->FrmDepartment();
		Application_Model_Decorator::removeAllDecorator($form);
		$this->view->form_search=$form;
	}
	public function addAction(){
		$db = new Group_Model_DbTable_DbClient();
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				//print_r($data);exit();
				try{
				 if(isset($data['save_new'])){
					$id= $db->addCustomer($data);
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
				else{
					$id= $db->addCustomer($data);
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS", "/group");
				}
				
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$db_cus = new Application_Model_DbTable_DbGlobal();
		$this->view->cus_no = $db_cus->getNewCustomerId();
		$db_branch = new Group_Model_DbTable_DbBranch();
		$this->view->company = $db_branch->getCompanyName();
		$this->view->province = $db->getProvince();
		
		$this->view->cu_name = $db->getCustomer();
		
	}
	public function editAction(){
		$db = new Group_Model_DbTable_DbClient();
		$id = $this->getRequest()->getParam("id");
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['id']=$id;
			try{
					$db->updateCustomer($data);
					Application_Form_FrmMessage::Sucessfull("EDIT_SUCCESS", "/group");
		
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Application Error");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$this->view->cusbyID = $db->getCustomerByCId($id);
		$this->view->c_add = $db->getCeremonyAddr($id);
		$db_branch = new Group_Model_DbTable_DbBranch();
		$this->view->company = $db_branch->getCompanyName();
		$db_branch = new Group_Model_DbTable_DbBranch();
		$this->view->company = $db_branch->getCompanyName();
		$this->view->province = $db->getProvince();
		
		$this->view->cu_name = $db->getCustomer();
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
	function getDistrictAction(){
		if($this->getRequest()->isPost()){
			$db = new Group_Model_DbTable_DbClient();
			$data = $this->getRequest()->getPost();
			$code = $db->getDistrict($data['province_id']);
			array_unshift($code, array ( 'id' => -1,'name' => 'បន្ថែមថ្មី'));
			print_r(Zend_Json::encode($code));
			exit();
		}
	}
	function insertDistrictAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db_district = new Group_Model_DbTable_DbClient();
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
	
	function getCustomerAction(){//At callecteral when click client
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Group_Model_DbTable_DbClient();
			$customer=$db->getCustomerById($data['id']);
			print_r(Zend_Json::encode($customer));
			exit();
		}
	}
}

