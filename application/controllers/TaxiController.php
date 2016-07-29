<?php

class TaxiController extends Zend_Controller_Action
{

	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    	
    }
   
    function indexAction(){
    	$db_globle = new Application_Model_DbTable_DbGlobal();
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		if(isset($post["search_vehicle"])){
    			$row = $db->getAvailableVehicle($post);
    			if($row){
    				$session_booking=new Zend_Session_Namespace('booking');
    				$session_booking->step_one=1;
    				$session_booking->pickup_date=$post["pickup_date"];
    				$session_booking->return_date=$post["return_date"];
    				$session_booking->pickup_time=$post["pickup_time"];
    				$session_booking->return_time=$post["return_time"];
    				$session_booking->pickup_location=$post["pickup_location"];
    				$session_booking->return_location=$post["return_location"];
    				$diff=date_diff($post["pickup_date"],$post["return_date"]);
    				$session_booking->total_day=$diff->format("%a%");
    				Application_Form_FrmMessage::redirectUrl("/index/booking");
    			}else{
    				Application_Form_FrmMessage::message("No Vehicle Available!");
    			}
    		}elseif(isset($post["confirm_book"])){
    			$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
    			$dbbooking->createSessionTaxiBooking($post,4);
    		}elseif(isset($post["term_condiction"])){
    			$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
    			$dbbooking->createSessionTaxiBooking($post,3);
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
    	
    	$this->view->termcondiction = $db_globle->getAllParameter('taxitermcondiction');
    	$this->view->user_session = $customer_user_session;
    	$db = new Application_Model_DbTable_Dbbookingtaxi();
    	$this->view->alllocation=$db->getAllLocation();
    	
    	$session =new Zend_Session_Namespace('taxibooking');
    	$this->view->bookinginfo = $session;
    	
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
    }
    function getvehicletaxiAction(){
    	$vehicle_id = $this->getRequest()->getParam("id");
    	if($vehicle_id){
    		$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
    		$dbbooking->createSessionTaxiBooking($vehicle_id,2);
    		$this->_redirect("taxi/index");
    	}
    	//Application_Form_FrmMessage::redirectUrl("/index/testing");
    }
    function getvehicleAction(){//action step2
    	if($this->getRequest()->isPost()){
    		$post = $this->getRequest()->getPost();
    		$reservation['trip_way']=$post['trip_way'];
    		$reservation['pickup_date']=$post['pickup_date'];
    		$reservation['pickup_time']=$post['pickup_time'];
    		$reservation['pickup_mins']=$post['pickup_mins'];
    		$reservation['from_location']=$post['from_location'];
    		$reservation['to_location']=$post['to_location'];
    		$post['return_time']=$post['pickup_time'].":".$post['pickup_mins'];
    		$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
    		$dbbooking->createSessionTaxiBooking($reservation,1);
    	}
    	$this->_redirect("taxi/index");
    }
    public function customerloginAction()
    {
    	if($this->getRequest()->isPost())
    	{
    		 
    		$formdata=$this->getRequest()->getPost();
    		$user_name=$formdata['user_name'];
    		$password=$formdata['password'];
    		$db_user=new Application_Model_DbTable_DbCustomer();
    		if($db_user->customerAuthenticate($user_name,$password)){
    			$step_four  = new Zend_Session_Namespace('step_four');
    			$step_four->step4 = 1;
    			$session_user=new Zend_Session_Namespace('customer');
    			$user_id=$db_user->getCustomerId($user_name);
    			$user_info = $db_user->getUserById($user_id, 1);
    
    			$session_user->customer_session = 1;
    			$session_user->customer_id = $user_id;
    			$session_user->customer_name = $user_name;
    
    			$session_user->pwd=$password;
    			$session_user->last_name= $user_info['last_name'];
    			$session_user->first_name= $user_info['first_name'];
    
    			$dbbooking = new Application_Model_DbTable_Dbbookingtaxi();
    			$dbbooking->createSessionTaxiBooking($formdata,3);
    		}
    		else{
    			Application_Form_FrmMessage::message("ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ");
    			$this->view->msg = 'ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ';
    		}
    		Application_Form_FrmMessage::redirectUrl("/taxi/index");
    		// 				}
    	}
    	$frmbooks = new Application_Form_FrmBooking();
    	$frmbooking = $frmbooks->FromBooking();
    	Application_Model_Decorator::removeAllDecorator($frmbooking);
    	$this->view->frmbooking = $frmbooking;
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
    		$customer_session->pwd=$password;
    		$customer_session->last_name= $user_info['last_name'];
    		$customer_session->first_name= $user_info['first_name'];
    		$customer_session->user_info = $user_info;
    		$this->_redirect("/taxi/index");
    		//Application_Form_FrmMessage::redirectUrl("/taxi/index");
    	}
    	else{
    	}
    }
//     function getcitytourpackageidAction(){//action step 1
//     	$package_id = $this->getRequest()->getParam("package_id");
//     	if($package_id){
//     		$dbbooking = new Application_Model_DbTable_Dbbookingcitytour();
//     		$dbbooking->createSessionBookingCityTour($package_id,1);
//     		$this->_redirect("index/citytourbooking");
    		
//     	}else{
//     		$this->_redirect("index/citytour");
//     	}
//     }

    public function paymentAction(){
    	$db_mail = new Application_Model_DbTable_DbSendEmail();
    	$db = new Application_Model_DbTable_Dbbookingtaxi();
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$booking_id = $db->addTaxiBooking($data);
    		$db_mail->sendInvoiceEmail($booking_id);
    		$session =new Zend_Session_Namespace('taxibooking');
    		$session->unsetAll();
    		Application_Form_FrmMessage::Sucessfull("Thank You For Support. Please Check Your E-mail Address! If don't see please contact administrator.", "/index");
    	}
    }
    
}





