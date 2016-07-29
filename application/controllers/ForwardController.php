<?php

class ForwardController extends Zend_Controller_Action
{
	public function addAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbSendEmail();
			$rs = $db->forwardEmail($data);
			Application_Form_FrmMessage::message($rs);
		}
	}
public function forwardAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbSendEmail();
			$rs = $db->forwardEmail($data);
			print_r(Zend_Json::encode($rs));
			exit();
		}
	}
	public function indexAction(){
	
	}
}

