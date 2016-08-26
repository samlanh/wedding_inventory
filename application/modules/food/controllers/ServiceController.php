<?php
class Food_ServiceController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL_ADD ='/food/service/add';
	const REDIRECT_URL_ADD_CLOSE ='/food/service';
	protected $tr;
    public function init()
    {    	
     /* Initialize action controller here */
    	$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function indexAction(){
		$db_make = new Food_Model_DbTable_DbService();
        try{
        	if($this->getRequest()->isPost()){
        		$search=$this->getRequest()->getPost();
        	}
        	else{
        		$search = array(
        				'title' => '',
        				'status_search' =>-1,
        		);
        	}
        $rows=$db_make->getAllService($search);
        $list = new Application_Form_Frmtable();
        $collumns = array("NAME","DATE","STATUS");
        $link=array(
        		'module'=>'food','controller'=>'service','action'=>'edit',
        );
        $this->view->rows = $rows;
        $this->view->list=$list->getCheckList(0, $collumns, $rows,array('name'=>$link,'date'=>$link));
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
		if($this->getRequest()->isPost()){
			$data= $this->getRequest()->getPost();
// 			print_r($data);exit();    
			try {
				$db_make = new Food_Model_DbTable_DbService();
				if(!empty($data['save_new'])){
					$db_make->addService($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD);
				}else{
					$db_make->addService($data);
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
		$db_make = new Food_Model_DbTable_DbService();
		$id=$this->getRequest()->getParam('id');
		if($this->getRequest()->isPost()){
			$data= $this->getRequest()->getPost();
			$data["id"] = $id;
				//print_r($data);exit();
			try {
				
				if(!empty($data['save_close'])){
					$db_make->updateService($data);
					Application_Form_FrmMessage::Sucessfull($this->tr->translate("INSERT_SUCCESS"),self::REDIRECT_URL_ADD_CLOSE);
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message($this->tr->translate("INSERT_FAIL"));
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$rows=$db_make->getServiceByid($id);
        $this->view->row=$rows;
        $db = new Application_Model_DbTable_DbGlobal();
        $status=$db->getViews(2);
        $this->view->status_view=$status;
	}
}

