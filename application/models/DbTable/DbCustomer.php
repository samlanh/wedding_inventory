<?php
class Application_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	protected $_name = 'ldc_customer';
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function getCountProOrder($id){
		$db= $this->getAdapter();
		$sql = 'SELECT p.`hot_sale` FROM `ldc_product` AS p WHERE p.`id`='.$id;
		return $db->fetchOne($sql);
	}
	public function checkOut(){
		try{
					$db= $this->getAdapter();
					$product_session = new Zend_Session_Namespace('product_add_cart');
					$product_session->cart_item;
					
					$customer_session  = new Zend_Session_Namespace('customer');
					$discount = $customer_session->cus_discount;
					$cus_type = $customer_session->cus_type;
					
					$db = new Booking_Model_DbTable_DbOrder();
					$invoiceId = $db->getNewIvoiceID();
					$total = 0;
					if(!empty($product_session->cart_item)) foreach ($product_session->cart_item as $rows){
						$total=$total+($rows['quantity']*$rows['price']);
						$hotsale = $this->getCountProOrder($rows['id']);
						$cal = $hotsale+1;
						$_arrr = array(
								'hot_sale'=>$cal
								);
						$this->_name='ldc_product';
						$where =$this->getAdapter()->quoteInto("id=?", $rows['id']);
						$this->update($_arrr, $where);
					}
					$total_after_disc = $total - (($total*$discount)/100);
					$arr = array(
							'invoice_no'=>$invoiceId,
							'cus_id'=>$customer_session->customer_id,
							'discount_login'=>$discount,
							'grand_total'=>$total_after_disc,
							'balance_due'=>$total_after_disc,
							'order_date'=>date("Y-m-d"),
							'cus_type'=>$cus_type,
							);
					$this->_name='ldc_order';
					$id=$this->insert($arr);
					
					if(!empty($product_session->cart_item)) foreach ($product_session->cart_item as $row){
					$_arr = array(
							'order_id'=>$id,
							'order_date'=>date("Y-m-d"),
							'pro_id'=>$row['id'],
							'price'=>$row['price'],
							'qty'=>$row['quantity'],
							'amount'=>$row['quantity']*$row['price'],
							);
					$this->_name='ldc_order_detail';
					$this->insert($_arr);
					}		
					return $id;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
				
		}
	}
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function customerAuthenticate($username,$password)
	{
	
		$db_adapter = Application_Model_DbTable_DbUsers::getDefaultAdapter();
		$auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
	
		$auth_adapter->setTableName($this->_name) // table where users are stored
		->setIdentityColumn('user_account') // field name of user in the table
		->setCredentialColumn('password') // field name of password in the table
		->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
			
		$auth_adapter->setIdentity($username); // set value of username field
		$auth_adapter->setCredential($password);// set value of password field
	
		//instantiate Zend_Auth class
		$auth = Zend_Auth::getInstance();
	
	
		$result = $auth->authenticate($auth_adapter);
	
	
		if($result->isValid()){
			return true;
		}else{
			return false;
		}
	}
	public function insertCusLogout($user_id)
	{
		$data=array(
				'log_date'=>date("Y/m/d , H:i:s"),
				'log_type'=>'out',
				'cus_id'=>$user_id
		);
		$this->_name = "rms_customer_log";
		$this->insert($data);
	}
	public function insertCusLogin($user_id)
	{
		$data=array(
				'log_date'=>date("Y/m/d , H:i:s"),
				'log_type'=>'in',
				'cus_id'=>$user_id
		);
		$this->_name = "rms_customer_log";
		$this->insert($data);
	}
	public function signUp($data){
		$db = $this->getAdapter();
		try {
			$db_globle = new Application_Model_DbTable_DbGlobal();
			$client_code = $db_globle->getNewClientId();
			$arr = array(
				'customer_code'	=>	$client_code,
				'first_name'	=>	$data["first_name"],
				'last_name'		=>	$data["last_name"],
				'email'			=>	$data["email"],
				'phone'			=>	$data["phone"],
				'user_account'	=>	$data["s_user_name"],
				'password'		=>	md5($data["s_password"]),
				'status'		=>	1,
'dob'			=>	$data["dob"],
				'sex'			=>	$data["gender"],
			);
			$this->_name = "ldc_customer";
			$row = $this->insert($arr);
			return $row;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
			
		}
	}
	public function checkUserEmail($email){
		$db= $this->getAdapter();
		$sql = 'SELECT p.`email` FROM `ldc_customers` AS p WHERE p.`email`='."'$email'";
		$check =  $db->fetchAll($sql);
		if (empty($check)){return 0;}
		else{return 1;}
	}
	public function userSignUp($data){
		$db = $this->getAdapter();
		$db_user=new Application_Model_DbTable_DbUsers();
		try {
			$db_globle = new Application_Model_DbTable_DbGlobal();
			$client_code = $db_globle->getNewCustomerId();
			$arr = array(
					'customer_code'	=>	$client_code,
					'email'			=>	$data["emmail_register"],
					'password'		=>	md5($data["password"]),
					'status'		=>	1,
			);
			$this->_name = "ldc_customers";
			$row = $this->insert($arr);
			return $row;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
				
		}
	}
	public function updateCusAddress($data){
		$db = $this->getAdapter();
		try {
			$arr = array(
					'province_id'	=>	$data['province_hidden'],
					'district'			=>	$data["district_hidden"],
					'house_num'		=>	$data["house_hidden"],
					'street'		=>	$data["road_hidden"],
					'phone'=>$data['phone_no'],
					'address_nickname'=>$data['address_nickname'],
					'verify_address'		=>	1,
					
			);
			$this->_name = "ldc_customers";
			$where=' id='.$data['id'];
			$this->update($arr, $where);
			return $row=1;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
		
		}
	}
	public function updateCusInfomation($data){
		$db = $this->getAdapter();
		try {
			$arr = array(
					'first_name'	=>	$data['first_name'],
					'last_name'			=>	$data["last_name"],
					'title'		=>	$data["title"],
					'verify_acc'		=>	1,
						
			);
			$this->_name = "ldc_customers";
			$where=' id='.$data['id'];
			$this->update($arr, $where);
			return $row =1;
		} catch (Exception $e) {
			echo$e->getMessage();
			exit();
	
		}
	}
	public function getAllCusEmail($email){
		$db = $this->getAdapter();
		$sql='SELECT p.id FROM `ldc_customers` AS p WHERE p.`status`=1 AND p.`email`='."'$email'";
		$row= $db->fetchRow($sql);
		if (!empty($row)){
			return 1;
		}
		else{
			return 0;
		}
		//return $row;
	}
	public function cusAuthenticate($user_email,$password)
	{
	
		$db_adapter = Application_Model_DbTable_DbUsers::getDefaultAdapter();
		$auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
	
		$auth_adapter->setTableName($this->_name = 'ldc_customers') // table where users are stored
		->setIdentityColumn('email') // field name of user in the table
		->setCredentialColumn('password') // field name of password in the table
		->setCredentialTreatment('MD5(?) AND status=1'); // optional if password has been hashed
			
		$auth_adapter->setIdentity($user_email); // set value of username field
		$auth_adapter->setCredential($password);// set value of password field
	
		//instantiate Zend_Auth class
		$auth = Zend_Auth::getInstance();
	
	
		$result = $auth->authenticate($auth_adapter);
	
	
		if($result->isValid()){
			return true;
		}else{
			return false;
		}
	}
	public function getCusID($email){
		$db= $this->getAdapter();
		$sql="SELECT p.id FROM ldc_customers AS p WHERE p.`email` ="."'$email'";
		$row=$db->fetchOne($sql);
		return $row;
	}
	public function getCustomerInfo($id){
		$db= $this->getAdapter();
		$sql="SELECT p.id,CONCAT(p.`first_name`,' ',p.`last_name`)AS `name`,p.`first_name`,p.`last_name`,p.discount_login,p.`customer_code`,p.`district`,(SELECT d.district_name FROM ldc_district AS d WHERE d.id=p.`district`)AS districts,p.`province_id`,(SELECT v.province_name FROM ldc_province AS v WHERE v.id=p.`province_id`) AS province,p.`house_num`,p.`street`,p.`email`,p.`phone`,p.`photo`,p.`note`,(SELECT e.title FROM `ldc_partner` AS e WHERE e.id = p.`company_id`) AS company,p.`branch_name`,p.`verify_acc`,p.verify_address,p.cus_type FROM ldc_customers AS p WHERE p.`id` ="."'$id'";
		return $db->fetchRow($sql);
	}
	public function updateCusInfor($data){
		$photoname = str_replace(" ", "_",$data['last_name'].'-pro') . '.jpg';
		$upload = new Zend_File_Transfer();
		$upload->addFilter('Rename',
				array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
		$receive = $upload->receive();
		if($receive)
		{
			$data['photo'] = $photoname;
		}
		else{
			$data['photo']=$data['old_photo'];
		}
		try{
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$client_code = $db_globle->getNewCustomerId();
		$arr= array(
				'customer_code'=>$client_code,
				'first_name' =>$data['first_name'],
				'last_name' => $data['last_name'],
				'sex' => $data['gender'],
				'company_name' => $data['company'],
				'photo' => $data['photo'],
				'note' => $data['remark'],
				'phone' => $data['phone_number'],
				'email' => $data['email_up'],
				'fax' => $data['fax'],
				'website' => $data['website'],
			);
		$this->_name ='ldc_customers';
		$where = 'id = '.$data['id'];
		$this->update($arr, $where);
		
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function getCusByID($id){
		$db =$this->getAdapter();
		$sql = 'SELECT p.email, p.verify_address,p.verify_acc FROM ldc_customers AS p WHERE p.`id`= '.$id;
		return $db->fetchRow($sql);
	}
	public function getUserById($id,$type){
		$db =$this->getAdapter();
		if($type==1){
			$sql = "SELECT *,(SELECT country_name from ldc_country where ldc_country.id = c.pob) AS pob,
			(SELECT country_name from ldc_country where ldc_country.id = c.country) AS country
			 FROM ldc_customer AS c WHERE c.`id`=$id";
			$row = $db->fetchRow($sql);
		}else{
			$sql = "SELECT CONCAT(c.`last_name`,' ',c.`first_name`) AS customer_name FROM ldc_customer AS c WHERE c.`id`=$id";
			$row = $db->fetchOne($sql);
		}
		
		if(!empty($row)){
			return $row;
		}
	}
	public function getCustomerId($user_name){
		$db =$this->getAdapter();
		$sql = "SELECT c.id FROM ldc_customer AS c WHERE c.`user_account`='$user_name'";
		$row = $db->fetchOne($sql);
		return $row;
	}

	public function updatecustomer($_data){//update customer
		$photoname = str_replace(" ", "_",$_data['name_en'].'-AGNF') . '.jpg';
		$upload = new Zend_File_Transfer();
		$upload->addFilter('Rename',
				array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
		$receive = $upload->receive();
		if($receive)
		{
			$_data['photo'] = $photoname;
		}
		else{
			$_data['photo']=$_data['old_photo'];
		}
		
	
		try{
// 			$db = new Application_Model_DbTable_DbGlobal();
// 			$client_code = $db->getNewClientId();
				
			$_arr=array(
					'title'	 => $_data['title'],
					'first_name' =>$_data['name_kh'],
					'last_name' => $_data['name_en'],
					'sex' => $_data['sex'],
					'dob' => $_data['dob_client'],
					'photo'	=> $_data['photo'],
					'occupation' => $_data['occupation'],
					'pob' => $_data['country'],
					'company_name'=> $_data['company_name'],
					'nationality' => $_data['national_id'],
					'phone' => $_data['phone'],
					'email' => $_data['email'],
					'group_num' => $_data['group_num'],
					'house_num' =>$_data['home'],
					'street' =>$_data['street'],
					'commune' => $_data['commune'],
					'district' => $_data["district"],
					'province_id' => $_data['province'],
					'address1'      => $_data['address1'],
					'address2'      => $_data['address2'],
					'i_city'      => $_data['city'],
					'i_zipcode'      => $_data['i_zipcode'],
					'i_state'      => $_data['state'],
					'country'      => $_data['country'],
					'i_phone'      => $_data['i_phone'],
					'fax'      => $_data['fax'],
					
					'date'  => date("Y-m-d"),
			);
			$session_user=new Zend_Session_Namespace('customer');
			$user_id= $session_user->customer_id;
			
			$where = 'id = '.$user_id;
			$this->update($_arr, $where);
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
public function passLink($email){
		$db = $this->getAdapter();
		
		$sql = "SELECT c.`email`,c.`pass_link` FROM `ldc_customer` AS c WHERE c.`email`='"."$email"."' LIMIT 1";
		$row = $db->fetchRow($sql);
		if($row){
			$rand = rand();
			$arr = array(
				'pass_link'		=>	md5($rand),
			);
			$this->_name="ldc_customer";
			$where = $db->quoteInto("email=?", $email);
			$this->update($arr, $where);
                $result = $db->fetchRow($sql);
                $db_mail = new Application_Model_DbTable_DbSendEmail();
		$db_mail->resetPassEmail($result );
		}
		
	}
	public function updatePassword($data,$pass_link){
		$db = $this->getAdapter();
		$arr = array(
			'password'	=> md5($data["password"])
		);
		$where = $db->quoteInto("pass_link=?", $pass_link);
		$this->update($arr, $where);
	}
	
	public function passLinkChangePass($email){
		$db = $this->getAdapter();
	
		$sql = "SELECT c.`email`,c.`pass_link` FROM `ldc_customers` AS c WHERE c.`email`='"."$email"."' LIMIT 1";
		$row = $db->fetchRow($sql);
		if($row){
			$rand = rand();
			$arr = array(
					'pass_link'		=>	md5($rand),
			);
			$this->_name="ldc_customers";
			$where = $db->quoteInto("email=?", $email);
			$this->update($arr, $where);
			$result = $db->fetchRow($sql);
			$db_mail = new Application_Model_DbTable_DbSendEmail();
			$db_mail->resetPasswordEmail($result );
		}
	
	}
	public function updatePasswordUser($data,$pass_link){
		$db = $this->getAdapter();
		$arr = array(
				'password'	=> md5($data["new_password"])
		);
		$this->_name="ldc_customers";
		$where = $db->quoteInto("pass_link=?", $pass_link);
		$this->update($arr, $where);
	}
}