<?php

class CarbookingController extends Zend_Controller_Action
{

	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    	
    }

    function indexAction(){
    	$dbbooking = new Application_Model_DbTable_DbCarRental();
    	$db_globle= new Application_Model_DbTable_DbGlobal();
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		if(isset($post["term_condiction"])){
    			$dbbooking->createSessionGuideBooking($post,4);
    			//print_r("test");exit();
    			$this->_redirect("carbooking/index");
    		}elseif (isset($post["confirm_book"])){
    			$dbbooking->createSessionGuideBooking($post,5);
    			$this->_redirect("carbooking/index");
    		}
    	}
    	
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$user_session = $customer_session->customer_session;
    	if(!empty($user_session)){
    		$customer_user_session = $user_session;
    		$customer_user = $customer_session->customer_id;
    		$this->view->user_name = $customer_session->customer_name;
    		$this->view->user_info = $customer_session->user_info;
    	}else{
    		$customer_user_session=0;
    	}
    	 
    	$db = new Application_Model_DbTable_DbCarRental();
    	$session =new Zend_Session_Namespace('carbooking');
    	$this->view->bookinginfo = $session;
    	
    	$this->view->producttermcomdiction=$db_globle->getAllParameter('producttermcomdiction');
    	
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    	$this->view->user_session = $customer_user_session;
    }
    function checkvaliabltimeAction(){//used
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		$dbbooking = new Application_Model_DbTable_DbCarRental();
    		$dbbooking->createSessionGuideBooking($post,1);
    		$this->_redirect("carbooking/index");
    	}
    }
   
    function getvehicleselectedAction(){//action step2
    	$vehicle_id = $this->getRequest()->getParam("id");
    	$dbbooking = new Application_Model_DbTable_DbCarRental();
    	if($vehicle_id){
    		
    		$dbbooking->createSessionGuideBooking($vehicle_id,2);
    		$this->_redirect("carbooking/index");
    	}
    	//Application_Form_FrmMessage::redirectUrl("/index/testing");
    }
    function getGuideSelectedAction(){
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		
    		$dbbooking = new Application_Model_DbTable_DbCarRental();
    		$dbbooking->createSessionGuideBooking($post,3);
    		$this->_redirect("carbooking/index");
    	}
    }
    function signupAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbCustomer();
    		$id = $db->signUp($data);
    		$user_name = $db->getUserById($id, 2);
    		$customer_session  = new Zend_Session_Namespace('customer');
    		$customer_session->customer_session = 1;
    		$customer_session->customer_id = $id;
    		$customer_session->customer_name = $user_name;
    		$user_id=$db->getCustomerId($user_name);
    		$user_info = $db->getUserById($id, 1);
    		$customer_session->last_name= $user_info['last_name'];
    		$customer_session->first_name= $user_info['first_name'];
    		$customer_session->user_info = $user_info;
    		$this->_redirect("/carbooking");
    	}
        else{
        	$this->_redirect("/carbooking");
        }
    }
    
    function getavailabledriverAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbCarRental();
    		$row = $db->getAllGuideFilter($data);
    		print_r(Zend_Json::encode($row));
    		exit();
    	}
    }
    
}





