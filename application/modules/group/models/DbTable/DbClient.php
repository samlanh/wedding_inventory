<?php

class Group_Model_DbTable_DbClient extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_customer';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function getAllCustomer($search){
    	$db = $this->getAdapter();
    	$start_date = $search["start_date"];
    	$end_date = $search["end_date"];
    	$sql ="SELECT 
				  cc.`id`,
				  c.`first_name`,
				  c.`phone`,
    			  c.`email`,
				  c.`address`,
				  cc.`ceremony_date`,
				  cc.`address_1` AS ceremony_address,
				  cc.`is_meeting`,
    			  cc.status 
				FROM
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE c.id = cc.`cu_id` AND cc.ceremony_date >='$start_date' AND cc.ceremony_date <='$end_date' ";
    	$where = "";
    	if(!empty($search['title'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[] = " c.customer_code LIKE '%{$s_search}%'";
    		$s_where[] = " c.first_name LIKE '%{$s_search}%'";
    		$s_where[] = " c.last_name LIKE '%{$s_search}%'";
    		$s_where[] = " c.phone LIKE '%{$s_search}%'";
    		//$s_where[] = " c.pob LIKE '%{$s_search}%'";
    		$s_where[] = " c.email LIKE '%{$s_search}%'";
    		$s_where[] = " c.address LIKE '%{$s_search}%'";
    		$s_where[] = " cc.address LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	if($search['status_search']>-1){
    		$where.= " AND cc.status = ".$search['status_search'];
    	}
//     	if($search['company']>-1){
//     		$where.= " AND company_id = ".$search['company'];
//     	}
    	$order=" ORDER BY first_name DESC";
    	//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);	
    }
//     public function getCustomerById($id){
//     	$db = $this->getAdapter();
//     	$sql = "SELECT * FROM ldc_customers WHERE id = ".$db->quote($id);
//     	$sql.=" LIMIT 1 ";
//     	$row=$db->fetchRow($sql);
//     	return $row;
//     }
    public function addCustomer($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		
    		if(@$_data["is_new_cu"]){
    			$arr = array(
		    			'title'	  			=> $_data['title'],
		    			//'customer_code'	  	=> $_data['client_no'],
		    			'first_name'	  	=> $_data['first_name'],
		    			//'last_name' 		=> $_data['last_name'],
		    			//'note'	      		=> $_data['desc'],
		    			'phone'        		=>$_data['phone'],
		    			'email'				=>$_data['email'],
		    			'address'			=>$_data['address'],
		    			//'house_num'			=>$_data['house_no'],
		    			//'street'			=>$_data['street'],
		    			//'district' 			=> $_data["district"],
		    			//'province_id'	  	=> $_data['province'],
		    			'status'  			=> 1,
		    			//'is_meeting'  		=> $_data['meeting'],
		    			//'ceremony_date'  	=> $_data['ceremony_date'],
		    			//'ceremony_address'  => $_data['ceremony_add'],
		    			//'discount_login'  => $_data['discount_login'],
		    			);
    			$this->_name ='ldc_customers';
			    		$where = 'id = '.$_data['cu_id'];
			    		$this->update($arr, $where);
			    		$id = $_data['cu_id'];
    		}else{
    			$arr = array(
		    			'title'	  			=> $_data['title'],
		    			'customer_code'	  	=> $_data['client_no'],
		    			'first_name'	  	=> $_data['first_name'],
		    			//'last_name' 		=> $_data['last_name'],
		    			//'note'	      		=> $_data['desc'],
		    			'phone'        		=>$_data['phone'],
		    			'email'				=>$_data['email'],
		    			'address'			=>$_data['address'],
		    			//'house_num'			=>$_data['house_no'],
		    			//'street'			=>$_data['street'],
		    			//'district' 			=> $_data["district"],
		    			//'province_id'	  	=> $_data['province'],
		    			'status'  			=> 1,
		    			//'is_meeting'  		=> $_data['meeting'],
		    			//'ceremony_date'  	=> $_data['ceremony_date'],
		    			//'ceremony_address'  => $_data['ceremony_add'],
		    			//'discount_login'  => $_data['discount_login'],
		    			);
    				$this->_name ='ldc_customers';
			    	$id = $this->insert($arr);
    		}
		    
    		$array = array(
    			'cu_id'				=>	$id,
    			'ceremony_date'		=>	$_data["ceremony_date"],
    			'address_1'			=>	$_data["ceremony_add1"],
    			'address_2'			=>	$_data["ceremony_add2"],
    			'address_3'			=>	$_data["ceremony_add3"],
    			'is_meeting'		=>	$_data["meeting"],
    			'status'			=>	$_data["status"],
    		);
    		$this->_name="ldc_customer_ceremony";
    		$cc_id = $this->insert($array);
    		
    		if(!empty($_data["ceremony_add1"])){
    			$arr_cc = array(
    				'cc_id'		=>	$cc_id,
    				'addr_name'	=>	$_data["ceremony_add1"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}

    		if(!empty($_data["ceremony_add2"])){
    			$arr_cc = array(
    					'cc_id'		=>	$cc_id,
    					'addr_name'	=>	$_data["ceremony_add2"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}
    		if(!empty($_data["ceremony_add3"])){
    			$arr_cc = array(
    					'cc_id'		=>	$cc_id,
    					'addr_name'	=>	$_data["ceremony_add3"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}
// 			    	exit();
    		$db->commit();
    	}catch(Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    }
    
    public function updateCustomer($_data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    
    		if(@$_data["is_new_cu"]){
    			$arr = array(
    					'title'	  			=> $_data['title'],
    					//'customer_code'	  	=> $_data['client_no'],
    					'first_name'	  	=> $_data['first_name'],
    					//'last_name' 		=> $_data['last_name'],
    					//'note'	      		=> $_data['desc'],
    					'phone'        		=>$_data['phone'],
    					'email'				=>$_data['email'],
    					'address'			=>$_data['address'],
    					//'house_num'			=>$_data['house_no'],
    					//'street'			=>$_data['street'],
    					//'district' 			=> $_data["district"],
    					//'province_id'	  	=> $_data['province'],
    					'status'  			=> 1,
    					//'is_meeting'  		=> $_data['meeting'],
    					//'ceremony_date'  	=> $_data['ceremony_date'],
    					//'ceremony_address'  => $_data['ceremony_add'],
    					//'discount_login'  => $_data['discount_login'],
    			);
    			$this->_name ='ldc_customers';
    			$where = 'id = '.$_data['cu_id'];
    			$this->update($arr, $where);
    			$id = $_data['cu_id'];
    		}else{
    			$arr = array(
    					'title'	  			=> $_data['title'],
    					'customer_code'	  	=> $_data['client_no'],
    					'first_name'	  	=> $_data['first_name'],
    					//'last_name' 		=> $_data['last_name'],
    					//'note'	      		=> $_data['desc'],
    					'phone'        		=>$_data['phone'],
    					'email'				=>$_data['email'],
    					'address'			=>$_data['address'],
    					//'house_num'			=>$_data['house_no'],
    					//'street'			=>$_data['street'],
    					//'district' 			=> $_data["district"],
    					//'province_id'	  	=> $_data['province'],
    					'status'  			=> 1,
    					//'is_meeting'  		=> $_data['meeting'],
    					//'ceremony_date'  	=> $_data['ceremony_date'],
    					//'ceremony_address'  => $_data['ceremony_add'],
    					//'discount_login'  => $_data['discount_login'],
    			);
    			$this->_name ='ldc_customers';
    			$id = $this->insert($arr);
    		}
    		$sql= "DELETE FROM ldc_customer_ceremony WHERE id =".$_data["id"];
    		$db->query($sql);
    		$array = array(
    				'cu_id'				=>	$id,
    				'ceremony_date'		=>	$_data["ceremony_date"],
    				'address_1'			=>	$_data["ceremony_add1"],
	    			'address_2'			=>	$_data["ceremony_add2"],
	    			'address_3'			=>	$_data["ceremony_add3"],
    				'is_meeting'		=>	$_data["meeting"],
    				'status'			=>	$_data["status"],
    		);
    		$this->_name="ldc_customer_ceremony";
    		$cc_id = $this->insert($array);
    		
    		$sqls= "DELETE FROM ldc_ceremony_addr WHERE id =".$_data["id"];
    		$db->query($sqls);
    		
    		if(!empty($_data["ceremony_add1"])){
    			$arr_cc = array(
    					'cc_id'		=>	$cc_id,
    					'addr_name'	=>	$_data["ceremony_add1"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}
    		
    		if(!empty($_data["ceremony_add2"])){
    			$arr_cc = array(
    					'cc_id'		=>	$cc_id,
    					'addr_name'	=>	$_data["ceremony_add2"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}
    		if(!empty($_data["ceremony_add3"])){
    			$arr_cc = array(
    					'cc_id'		=>	$cc_id,
    					'addr_name'	=>	$_data["ceremony_add3"]
    			);
    			$this->_name = "ldc_ceremony_addr";
    			$this->insert($arr_cc);
    		}
    		 
    		// 			    	exit();
    		$db->commit();
    	}catch(Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    	}
    }
	function getProvince(){
		$db= $this->getAdapter();
		$sql="SELECT p.id,p.`province_name` AS `name` FROM `ldc_province` AS p WHERE p.`status`=1";
		return $db->fetchAll($sql);
	}
	function getDistrict($province_id){
		$db= $this->getAdapter();
		$sql="SELECT d.`id`,d.`district_name` AS `name` FROM `ldc_district` AS d WHERE d.`status`=1 AND d.`province_id`=".$province_id;
		return $db->fetchAll($sql);
	}
	public function addDistrictByAjax($_data){
		$_arr=array(
				'province_id'	  		=> $_data['province_id'],
				'district_name'	  	=> $_data['district_name'],
				'status'	  		=> 1,
				'date' 		=> Zend_Date::now(),
		);
		$this->_name='ldc_district';
		return  $this->insert($_arr);
	}
	
	public function addSupplier($_data){
		//  	print_r($_data);exit();
		//     	$photoname = str_replace(" ", "_", $_data['name_en'].'-pro') . '.jpg';
		//     	$upload = new Zend_File_Transfer();
		//     	$upload->addFilter('Rename',
		//     			array('target' => PUBLIC_PATH . '/images/'. $photoname, 'overwrite' => true) ,'photo');
		//     	$receive = $upload->receive();
		//     	if($receive)
			//     	{
			//     		$_data['photo'] = $photoname;
			//     	}
			//     	else{
			//     		$_data['photo']=!empty($_data['id'])?$_data['old_photo']:"";
			//     	}
		try{
			//if ($_data['cus_type']==1){
			$this->_name ='ldc_supplier';
			$arr = array(
					'title'	  			=> $_data['title'],
					'su_code'	  		=> $_data['su_no'],
					'first_name'	  	=>$_data['first_name'],
					'last_name' 		=> $_data['last_name'],
					//'note'	      		=> $_data['desc'],
					'p_phone'        	=>$_data['p_phone'],
					'p_email'			=>$_data['p_email'],
					'c_phone'        	=>$_data['c_phone'],
					'c_email'			=>$_data['c_email'],
					'house_num'			=>$_data['house_no'],
					'street'			=>$_data['street'],
					'district' 			=> $_data["district"],
					'province_id'	  	=> $_data['province'],
					'status'  			=> $_data['status'],
					'balance'  			=> $_data['balance'],
					'company_name'  	=> $_data['company_name'],
					'position'  		=> $_data['position'],
					//'discount_login'  => $_data['discount_login'],
			);
			if(!empty($_data['id'])){
				$where = 'id = '.$_data['id'];
				$this->update($arr, $where);
				return $_data['id'];
	
			}else{
				
				echo "true";
				return  $this->insert($arr);
			}
	
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			echo $e->getMessage();
		}
	}
	public function getSupplierById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM ldc_supplier WHERE id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getAllSupplier($search){
		$db = $this->getAdapter();
		$sql ="SELECT p.id,p.`su_code`,p.`first_name`,
    	p.`last_name`,
    	p.`p_phone`,p.`p_email`,p.`company_name`,p.note,
    	(SELECT name_en FROM `ldc_view` WHERE key_code = p.`status` AND `type`=2) AS `status` FROM `ldc_supplier` AS p WHERE 1";
		$where = " ";
		if(!empty($search['title'])){
			$s_where = array();
			$s_search = addslashes(trim($search['title']));
			$s_where[] = "p.`su_code` LIKE '%{$s_search}%'";
			$s_where[] = " first_name LIKE '%{$s_search}%'";
			$s_where[] = "p.p_phone LIKE '%{$s_search}%'";
			$s_where[] = "p.p_email LIKE '%{$s_search}%'";
			$s_where[] = " group_num LIKE '%{$s_search}%'";
			$s_where[] = " house_num LIKE '%{$s_search}%'";
			$s_where[] = " commune LIKE '%{$s_search}%'";
			$s_where[] = " district LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search['status_search']>-1){
			$where.= " AND status = ".$search['status_search'];
		}
		$order=" ORDER BY id DESC";
		return $db->fetchAll($sql.$where.$order);
	}
	
	function getCustomer(){
		$db = $this->getAdapter();
		$sql = "SELECT c.`id`,c.`first_name` FROM `ldc_customers` AS c";
		return $db->fetchAll($sql);
	}
	function getCustomerById($id){
		$db = $this->getAdapter();
		$sql = "SELECT c.`id`,c.`first_name`,c.`customer_code`,c.`phone`,c.`email`,c.address,c.`title` FROM `ldc_customers` AS c WHERE c.`id`=$id";
		return $db->fetchRow($sql);
	}
	
	function getCustomerByCId($id){
		$db = $this->getAdapter();
		$sql ="SELECT c.`id`,c.`address`,c.`phone`,c.`email`,c.`title`,c.`customer_code`,c.`first_name`,cc.`address_1`,cc.`address_2`,cc.`address_3`,cc.`ceremony_date`,cc.`is_meeting`,cc.`status` FROM `ldc_customers` AS c, `ldc_customer_ceremony` AS cc WHERE c.`id`=cc.`cu_id` AND cc.`id` = $id";
		return $db->fetchRow($sql);
	}
	function getCeremonyAddr($id){
		$db = $this->getAdapter();
		$sql ="SELECT ca.`addr_name` FROM `ldc_ceremony_addr` AS ca WHERE ca.`cc_id`= $id";
		return $db->fetchAll($sql);
	}
	
}

