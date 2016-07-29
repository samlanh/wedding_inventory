<?php

class IndexController extends Zend_Controller_Action
{

	const REDIRECT_URL = '/transfer';
	
    public function init()
    {
        /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');  
    	
    }
    public function indexAction()
    {
    	$db=new Application_Model_DbTable_DbProduct();
    	$this->view->newproduct = $db->getAllproduc();
    	$this->view->cate = $db->getAllCate();
    	$dbs = new Application_Model_DbTable_DbSlide();
    	$this->view->slide = $dbs->getSlideImage();
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$this->view->customer_session = $customer_session;
    	
    	$product_session = new Zend_Session_Namespace('product_add_cart');
    	$this->view->session = $product_session->cart_item;
    	
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=1;
    }
    public function searchAction()
    {
    		$cate_search=$this->getRequest()->getParam('cate_search');
			$txt_search=$this->getRequest()->getParam('txtsearch');
    	$db=new Application_Model_DbTable_DbProduct();
		
		$searchproduct= $db->getSearchProduc($cate_search,$txt_search);
		$this->view->newproduct = $db->getAllproduc();
    	$this->view->search =$searchproduct;
    	$this->view->namesearch = @$txt_search;
    	$this->view->catesearch = @$cate_search;
	  	$db=new Application_Model_DbTable_DbProduct();
	  	$this->view->product = $db->getAllproduc();
		$this->view->cate = $db->getCateById($cate_search);

		
    	$paginator = Zend_Paginator::factory($searchproduct);
    	$paginator->setDefaultItemCountPerPage(8);
    	$allItems = $paginator->getTotalItemCount();
    	$countPages= $paginator->count();
    	$p = $this->getRequest()->getParam('page');
    	 
    	if(isset($p))
    	{
    		$paginator->setCurrentPageNumber($p);
    	} else $paginator->setCurrentPageNumber(1);
    	 
    	$currentPage = $paginator->getCurrentPageNumber();
    	 
    	$this->view->product = $paginator;
    	$this->view->countItems = $allItems;
    	$this->view->countPages = $countPages;
    	$this->view->currentPage = $currentPage;
    	 
    	if($currentPage == $countPages)
    	{
    		$this->view->nextPage = $countPages;
    		$this->view->previousPage = $currentPage-1;
    	}
    	else if($currentPage == 1)
    	{
    		$this->view->nextPage = $currentPage+1;
    		$this->view->previousPage = 1;
    	}
    	else {
    		$this->view->nextPage = $currentPage+1;
    		$this->view->previousPage = $currentPage-1;
    	}
		$active_session = new Zend_Session_Namespace('active_menu');
		$active_session->unsetAll();
    }
    public function categoryAction()
    {
    	$id=$this->getRequest()->getParam('id');
    	$db=new Application_Model_DbTable_DbProduct();
    	
    	$product= $db->getAllProByID($id);
    	$paginator = Zend_Paginator::factory($product);
    	$paginator->setDefaultItemCountPerPage(8);
    	$allItems = $paginator->getTotalItemCount();
    	$countPages= $paginator->count();
    	$p = $this->getRequest()->getParam('page');
    	 
    	if(isset($p))
    	{
    		$paginator->setCurrentPageNumber($p);
    	} else $paginator->setCurrentPageNumber(1);
    	 
    	$currentPage = $paginator->getCurrentPageNumber();
    	 
    	$this->view->product = $paginator;
    	$this->view->countItems = $allItems;
    	$this->view->countPages = $countPages;
    	$this->view->currentPage = $currentPage;
    	 
    	if($currentPage == $countPages)
    	{
    		$this->view->nextPage = $countPages;
    		$this->view->previousPage = $currentPage-1;
    	}
    	else if($currentPage == 1)
    	{
    		$this->view->nextPage = $currentPage+1;
    		$this->view->previousPage = 1;
    	}
    	else {
    		$this->view->nextPage = $currentPage+1;
    		$this->view->previousPage = $currentPage-1;
    	}
    	$this->view->id = $id;
    	//$this->view->cate = $db->getAllCate();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    public function profileAction(){
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$cus_id = $customer_session->customer_id;
    	if (!empty($cus_id)){
    		$db =new Application_Model_DbTable_DbOrder();
    		$this->view->orderhistory = $db->getOrderhistory($cus_id);
    	}
    	else{
    		$this->_redirect('/index/register');
    	}
    	$session =new Zend_Session_Namespace('ordering');
    	$session->unsetAll();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    public function registerAction()
    {
    	$login=new Zend_Session_Namespace('login');
    	
    	$this->view->code = $login->code;
    	$login->unsetAll();
    	$this->view->msg = 'Invalid email and password. Please check and try again. ';
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    public function findAddressAction(){
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$verify = $customer_session->verify_acc;
    	$veryfy_address = $customer_session->verify_address;
    	$user_id = $customer_session->customer_id;
    	if ($verify>0 || $verify==null || $veryfy_address >0 || $veryfy_address==null){
    		$this->_redirect('/index');
    		 
    	}
    	else if ($veryfy_address==0){
    		if($this->getRequest()->isPost()){
    				$data=$this->getRequest()->getPost();
    				$data['id']=$user_id;
    				if (!empty($user_id)){
    					$db = new Application_Model_DbTable_DbCustomer();
    						
    					$update_cusInfo = $db->updateCusAddress($data);
    						
    					$customer_session  = new Zend_Session_Namespace('customer');
    					$customer_session->verify_address = $update_cusInfo;
    					//print_r($customer_session->verify_address);exit();
    					$this->_redirect('/index/complete-register');
    				}
    				else{
    					$this->_redirect('/index/find-address');
    				}
    		}
    	}
    	$db_province = new Group_Model_DbTable_DbProvince();
    	$this->view->province = $db_province->getAllprovincetoFront();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    function completeRegisterAction(){
    	$customer_session  = new Zend_Session_Namespace('customer');
    	$veryfy_address= $customer_session->verify_address;
    	//print_r($veryfy_address);exit();
    	if (!empty($veryfy_address)){
    		$customer_session  = new Zend_Session_Namespace('customer');
    		$user_id = $customer_session->customer_id;
    		$verify = $customer_session->verify_acc;
    		
    		if ($verify>0 || $verify==null){
    			$this->_redirect('/index');
    	
    		}
    		else if ($verify==0){
    			if($this->getRequest()->isPost()){
    				$data=$this->getRequest()->getPost();
    				$data['id']=$user_id;
    				if ($data['first_name']!=null || $data['last_name']!=null){
	    				if (!empty($user_id)){
	    					$db = new Application_Model_DbTable_DbCustomer();
	    					
	    					$db_cus=new Application_Model_DbTable_DbCustomer();
	    					$cus_info = $db_cus->getCustomerInfo($user_id);
	    					
	    					$update_cusInfo = $db->updateCusInfomation($data);
	    						
	    					$customer_session  = new Zend_Session_Namespace('customer');
	    					$customer_session->verify_acc = $update_cusInfo;
	    					$customer_session->customer_name = $cus_info['name'];
	    					$customer_session->cus_firstname = $cus_info['first_name'];
	    					$customer_session->cus_lasttname = $cus_info['last_name'];
	    					$customer_session->cus_phone = $cus_info['phone'];
	    					$customer_session->cus_photo = $cus_info['photo'];
	    					$customer_session->houseno = $cus_info['house_num'];
	    					$customer_session->street = $cus_info['street'];
	    					$customer_session->districts = $cus_info['districts'];
	    					$customer_session->province = $cus_info['province'];
	    					$customer_session->cus_remark = $cus_info['note'];
	    					$customer_session->company = $cus_info['company'];
	    					$customer_session->branch_name = $cus_info['branch_name'];
	    					$customer_session->cus_type = $cus_info['cus_type'];
	    					$customer_session->cus_discount = $cus_info['discount_login'];
	    					$this->_redirect('/index/profile');
	    				}
	    				else{
	    					$this->_redirect('/index/complete-register');
	    				}
    				}else 
    				{
    					$this->_redirect('/index/complete-register');
    				}
    			}
    	
    		}
    	}else{
    		$this->_redirect('/index/find-address');
    	}
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    function getDistrictAction(){
    		if($this->getRequest()->isPost()){
    			$data=$this->getRequest()->getPost();
    			$db = new Group_Model_DbTable_DbDistrict();
    			$province = $db->getAllDistrictByID($data['province_id']);
    			print_r(Zend_Json::encode($province));
    			exit();
    		}
    }
    public function detailAction()
    {
    	$id=$this->getRequest()->getParam('id');
    	$db=new Application_Model_DbTable_DbProduct();
    	$this->view->product = $db->getAllproduc();
    	$this->view->id = $id;
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    public function aboutUsAction()
    {
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenuabout();
    	$this->view->showdata= $data;
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=4;
    }
    public function contactUsAction()
    {
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenucontact();
    	$this->view->showdata= $data;
    	$title = 'Google Map';
    	$this->view->googlemap = $db->getParameter($title);
    	
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=3;
    }
    public function loginAction(){
    	if($this->getRequest()->isPost())
    	{
    		$formdata=$this->getRequest()->getPost();
    		$user_email=$formdata['email_login'];
    		$password=$formdata['password_login'];
    		$db_cus=new Application_Model_DbTable_DbCustomer();
    		if($db_cus->cusAuthenticate($user_email,$password)){
    			
    			$customer_session=new Zend_Session_Namespace('customer');
    			$cus_id=$db_cus->getCusID($user_email);
    			$cus_info = $db_cus->getCustomerInfo($cus_id);
    			$db_cus->insertCusLogin($cus_id);
    			//print_r($cus_info);exit();
    			$customer_session->customer_id = $cus_id;
    			$customer_session->pwd = $password;
    			$customer_session->cus_email = $user_email;
    			$customer_session->customer_name = $cus_info['name'];
    			$customer_session->cus_firstname = $cus_info['first_name'];
    			$customer_session->cus_lasttname = $cus_info['last_name'];
    			$customer_session->cus_phone = $cus_info['phone'];
    			$customer_session->cus_photo = $cus_info['photo'];
    			$customer_session->houseno = $cus_info['house_num'];
    			$customer_session->street = $cus_info['street'];
    			$customer_session->districts = $cus_info['districts'];
    			$customer_session->province = $cus_info['province'];
    			$customer_session->verify_acc = $cus_info['verify_acc'];
    			$customer_session->verify_address = $cus_info['verify_address'];
    			$customer_session->cus_type = $cus_info['cus_type'];
    			$customer_session->company = $cus_info['company'];
    			$customer_session->branch_name = $cus_info['branch_name'];
    			$customer_session->cus_remark = $cus_info['note'];
    			$customer_session->cus_discount = $cus_info['discount_login'];
    			
    			$login_cart =new Zend_Session_Namespace('login_cart');
    			if ($login_cart->code==1){
    				$login_cart->unsetAll();
    				$this->_redirect("/index/order");
    			}
    			$active_session = new Zend_Session_Namespace('active_menu');
    			$active_session->unsetAll();
    			if ($customer_session->verify_address == 0){
    				$this->_redirect("/index/find-address");
    			}else if($customer_session->verify_address == 1 && $customer_session->verify_acc == 0 ){
    				$this->_redirect("/index/complete-register");
    			}
    			else{
    				$this->_redirect("/index/profile");
    			}
    		}
    		else{
    			$login=new Zend_Session_Namespace('login');
    			$login_cart =new Zend_Session_Namespace('login_cart');
    			if ($login_cart->code ==1){
    				$login->code = 1;
    				$login_cart->unsetAll();
    				$this->_redirect("/index/order");
    			}
    			$login->code = 1;
    			//Application_Form_FrmMessage::message("ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ ");
    			$this->view->msg = 'Invalid email and password. Please check and try again.';
    			$this->_redirect("/index/register");
    		}
    		
    	}
    }
    public function adminloginAction()
    {
        // action body
    	$this->_helper->layout()->disableLayout();
    	
		$form=new Application_Form_FrmLogin();				
		$form->setAction('index');		
		$form->setMethod('post');
		$form->setAttrib('accept-charset', 'utf-8');
		$this->view->form=$form;
		$key = new Application_Model_DbTable_DbKeycode();
		//print_r($key->getKeyCodeMiniInv(TRUE));exit();
		$dd=$this->view->data=$key->getKeyCodeMiniInv(TRUE);	
			//print_r($dd);exit();
		
		if($this->getRequest()->isPost())		
		{
			$formdata=$this->getRequest()->getPost();
			if($form->isValid($formdata))
			{
				$session_lang=new Zend_Session_Namespace('lang');
				$session_lang->lang_id=$formdata["lang"];//for creat session
				Application_Form_FrmLanguages::getCurrentlanguage($session_lang->lang_id);//for choose lang for when login
				$user_name=$form->getValue('txt_user_name');
				$password=$form->getValue('txt_password');
				$db_user=new Application_Model_DbTable_DbUsers();
				if($db_user->userAuthenticate($user_name,$password)){					
// 					$this->view->msg = 'Authentication Sucessful!';
// 					$this->view->err="0";
					
					$session_user=new Zend_Session_Namespace('auth');
					$user_id=$db_user->getUserID($user_name);
					$user_info = $db_user->getUserInfo($user_id);
					
					$arr_acl=$db_user->getArrAcl($user_info['user_type']);
					
					
					$session_user->user_id=$user_id;
					$session_user->user_name=$user_name;
					$session_user->pwd=$password;		
					$session_user->level= $user_info['user_type'];
					$session_user->last_name= $user_info['last_name'];
					$session_user->first_name= $user_info['first_name'];
					$session_user->theme_style=$db_user->getThemeByUserId($user_id);
					
					
					$a_i = 0;
					$arr_actin = array();	
// 					print_r($arr_acl);
					for($i=0; $i<count($arr_acl);$i++){
						$arr_module[$i]=$arr_acl[$i]['module'];
// 						if($arr_acl[$i]['module'] == 'exchange'){
// 							if($arr_acl[$i]['action'] == "index" || $arr_acl[$i]['action'] == "add" || $arr_acl[$i]['action'] == "edit" ) {
// 								continue;
// 							}
							$arr_actin[$a_i++] = $arr_acl[$i]['module'].'/'.$arr_acl[$i]['controller'].'/'.$arr_acl[$i]['action'];
// 						}
					}	
// 					print_r($arr_actin);exit();
					$arr_module=(array_unique($arr_module));
					$arr_actin=(array_unique($arr_actin));
// 					print_r($arr_module);	echo "<br />============<br />";
					$arr_module=$this->sortMenu($arr_module);
// 					print_r($arr_module);exit();
// 					print_r($arr_module); exit;
					$session_user->arr_acl = $arr_acl;
					$session_user->arr_module = $arr_module;
					$session_user->arr_actin = $arr_actin;
						
					$session_user->lock();
					
					$log=new Application_Model_DbTable_DbUserLog();
					$log->insertLogin($user_id);
					foreach ($arr_module AS $i => $d){
						if($d !== 'user'){
							$url = '/' . @$arr_module[2];
						}
						else{
							$url = self::REDIRECT_URL;
							break;
						}
					}	
					Application_Form_FrmMessage::redirectUrl("/home");	
				}
				else{					
					$this->view->msg = 'ឈ្មោះ​អ្នក​ប្រើ​ប្រាស់ និង ពាក្យ​​សំងាត់ មិន​ត្រឺម​ត្រូវ​ទេ';
				}
					
			}
			else{				
				$this->view->msg = '';
			}			
		}		
    }
    
    protected function sortMenu($menus){
    	$menus_order = Array ( 'home','other','group','loan','tellerandexchange','accounting','report','setting','backup','rsvAcl');
    	$temp_menu = Array();
    	$menus=array_unique($menus);
    	foreach ($menus_order as $i => $val){
    		foreach ($menus as $k => $v){
    			if($val == $v){
    				$temp_menu[] = $val;
    				unset($menus[$k]);
    				break;
    			}
    		}
    	}
    	return $temp_menu;    	
    }
	
    public function customerlogoutAction()
    {
    	// action body
    	
    		$aut=Zend_Auth::getInstance();
    		$aut->clearIdentity();
    		$session_user=new Zend_Session_Namespace('customer');
    		 
    		$log=new Application_Model_DbTable_DbCustomer();
    		$log->insertCusLogout($session_user->customer_id);
    			
    		$session_user->unsetAll();
    		$this->_redirect("/index");
    		Application_Form_FrmMessage::redirectUrl("/");
    		exit();
    }
    public function logoutAction()
    {
        // action body
        $this->_redirect("/index");
        if($this->getRequest()->getParam('value')==1){        	
        	$aut=Zend_Auth::getInstance();
        	$aut->clearIdentity();        	
        	$session_user=new Zend_Session_Namespace('auth');
        	
        	$log=new Application_Model_DbTable_DbUserLog();
			$log->insertLogout($session_user->user_id);
			
        	$session_user->unsetAll();       	
	           	         	 
        	Application_Form_FrmMessage::redirectUrl("/");
        	exit();
        } 
    }

    public function changepasswordAction()
    {
        // action body
        if ($this->getRequest()->isPost()){ 
			$session_user=new Zend_Session_Namespace('auth');    		
    		$pass_data=$this->getRequest()->getPost();
    		if ($pass_data['password'] == $session_user->pwd){
    			    			 
				$db_user = new Application_Model_DbTable_DbUsers();				
				try {
					$db_user->changePassword($pass_data['new_password'], $session_user->user_id);
					$session_user->unlock();	
					$session_user->pwd=$pass_data['new_password'];
					$session_user->lock();
					Application_Form_FrmMessage::Sucessfull('áž€áž¶ážšáž•áŸ’áž›áž¶ážŸáŸ‹áž”áŸ’áž�áž¼ážšážŠáŸ„áž™áž‡áŸ„áž‚áž‡áŸ�áž™', self::REDIRECT_URL);
				} catch (Exception $e) {
					Application_Form_FrmMessage::message('áž€áž¶ážšáž•áŸ’áž›áž¶ážŸáŸ‹áž”áŸ’áž�áž¼ážšáž�áŸ’ážšáž¼ážœáž”ážšáž¶áž‡áŸ�áž™');
				}				
    		}
    		else{
    			Application_Form_FrmMessage::message('áž€áž¶ážšáž•áŸ’áž›áž¶ážŸáŸ‹áž”áŸ’áž�áž¼ážšáž�áŸ’ážšáž¼ážœáž”ážšáž¶áž‡áŸ�áž™');
    		}
        }   
    }

    public function errorAction()
    {
        // action body
        
    }
    public function  dashboardAction(){
    	$this->_helper->layout()->disableLayout();
    }
    public static function start(){
    	return ($this->getRequest()->getParam('limit_satrt',0));
    }
    function changelangeAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$session_lang=new Zend_Session_Namespace('lang');
    		$session_lang->lang_id=$data['lange'];
    		Application_Form_FrmLanguages::getCurrentlanguage($data['lange']);
    		print_r(Zend_Json::encode(2));
    		exit();
    	}
    }
    
   
    public function pageAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenufornt();
    	$this->view->showdata= $data;
    }
    public function serviceAction(){
//     	$this->_helper->layout()->disableLayout();
    	$db = new Application_Model_DbTable_DbService();
    	$data = $db->getAllServiceList();
    	$this->view->showdata= $data;
    }
    public function ourteamAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$data = $db->getmenufornt();
    	$this->view->showdata= $data;
    }
    public function jobAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$id= $this->getRequest()->getParam("id");
    	$data_id = $this->view->id = $id;
    	if(!empty($id)){
    		$data = $db->getJobById($id);
    		$this->view->showdata= $data;
    	}else{
    		$data = $db->getAllJob();
    		$this->view->showdata= $data;
    	}
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=2;
    }
    public function portfolioAction(){
    	$db = new Application_Model_DbTable_DbFrontend();
    	$this->view->rowsphoto = $db->getAllPhotoGalllery();
    }
    function faqUsAction(){
    	$db = new Other_Model_DbTable_Dbfaq();
    	$this->view->rows=$db->getAllFAQInfrontend();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=4;
    }
    function policyAction(){
    	$db = new Other_Model_DbTable_Dbprivacy();
    	$this->view->rows=$db->getAllPrivacy();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=4;
    }
    function sendemailAction(){
    	$post=$this->getRequest()->getPost();
		$db = new Application_Model_DbTable_DbSendEmail();
		$sms = $db->sendContactEmail($post);
		echo Zend_Json::encode($sms);
		exit();
    }
   
    
    function checkUsernameAvailableAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->checkAvailableUserName($data);
    		print_r(Zend_Json::encode($row));
    		exit();
    	}
    }
    function getstuffidAction(){
    	$id = $this->getRequest()->getParam("id");
    	if($id){
    		$dbbooking = new Application_Model_DbTable_Dbstuffrental();
    		$dbbooking->createSessionStuffBooking($id,1);
    		$this->_redirect("stuffbooking/index");
    		 
    	}else{
    		$this->_redirect("stuffbooking/index");
    	}
    }
    function termconditionAction(){
    	$db = new Other_Model_DbTable_DbHelp();
    	$this->view->rows= $db->getTermCodition();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=4;
    }
    function deliveryAction(){
    	$db = new Other_Model_DbTable_DbHelp();
    	$this->view->rows= $db->getDeliveryinfo();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=4;
    }
  
   
    function submitcommentAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$row = $db->sumitComment($data);
    		$this->_redirect("/index/guestbook");
    	}
    }
  
  
 
	function ajaxAction(){
    	if ($this->getRequest()->isPost()){
    	$data = $this->getRequest()->getPost();
    	$db = new Application_Model_DbTable_DbProduct();
    	$product =  $db->addCardById($data);
    	//print_r($product);
    	print_r(Zend_Json::encode($product));
    	exit();
    	}
    }
    function orderAction(){
    	
    	$session =new Zend_Session_Namespace('ordering');
    	$this->view->ordering_step = $session;
    	
    	$login=new Zend_Session_Namespace('login');
    	$login_cart =new Zend_Session_Namespace('login_cart');
    	$this->view->code = $login->code;
    	$login->unsetAll();
    	$login_cart->unsetAll();
    	$this->view->msg = 'Invalid email and password. Please check and try again.';
    	
    	
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    }
    function steporderAction(){
    	$product_session = new Zend_Session_Namespace('product_add_cart');
    	$product_session->cart_item;
    	if (!empty($product_session->cart_item)){
	    	$db= new Application_Model_DbTable_DbSteporder();
	    	$db->createSessionStepOrdering(1);
	    	$this->_redirect("index/order");
    	}
    	else {
    		$message = Application_Form_FrmMessage::message("No product in your list. please add products to your list !");
    		$this->_redirect("/index/order");
    	}
    }
    function updatecartAction(){
    	if ($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbProduct();
    		$product =  $db->updateCart($data);
    		//print_r($product);
    		print_r(Zend_Json::encode($product));
    		exit();
    	}
    }
    function invoiceAction(){
    	$id=$this->getRequest()->getParam('no');
    	$db=new Application_Model_DbTable_DbOrder();
    	$this->view->orderhistory = $db->getOrderhistorydetail($id);
    }
    function promotionAction(){
    	
    	$db = new Application_Model_DbTable_DbGlobal();
    	$this->view->promotion = $db->getAllPromotion();
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=5;
    	
    }
    function howToOrderAction(){
    	$db = new Application_Model_DbTable_DbGlobal();
    	$title = 'How to order';
    	$this->view->howtoorder = $db->getParameter($title);
    	
    	$active_session = new Zend_Session_Namespace('active_menu');
    	$active_session->unsetAll();
    	$active = $active_session->active=6;
    }
    function contactFormSendMailAction(){
    	$db = new Application_Model_DbTable_DbSendEmail();
    	if ($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		
    		$db->ContactFormSendmail($data);
    		Application_Form_FrmMessage::Sucessfull("Email has been updated!", "/index");
    	}
    	
    }
}




