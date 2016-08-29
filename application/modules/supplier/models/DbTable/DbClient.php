<?php

class Supplier_Model_DbTable_DbClient extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_customer';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
    public function getAllCustomer($search){
    	$db = $this->getAdapter();
    	$sql ="SELECT p.id,p.`customer_code`,p.`first_name`,
    	p.`last_name`,
    	p.`phone`,p.`email`,p.`street`,
    	p.`house_num`,(SELECT d.district_name FROM `ldc_district` AS d WHERE d.id = p.`district` ) AS district,(SELECT k.province_name FROM `ldc_province` AS k WHERE k.id = p.`province_id`)AS province,
    	(SELECT name_en FROM `ldc_view` WHERE key_code = p.`status` AND `type`=2)AS`status` FROM `ldc_customers` AS p WHERE 1";
    	$where = "";
    	if(!empty($search['title'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[] = "customer_code LIKE '%{$s_search}%'";
    		$s_where[] = " first_name LIKE '%{$s_search}%'";
    		$s_where[] = " last_name LIKE '%{$s_search}%'";
    		$s_where[] = "phone LIKE '%{$s_search}%'";
    		$s_where[] = "pob LIKE '%{$s_search}%'";
    		$s_where[] = "email LIKE '%{$s_search}%'";
    		$s_where[] = " nationality LIKE '%{$s_search}%'";
    		$s_where[] = " group_num LIKE '%{$s_search}%'";
    		$s_where[] = " house_num LIKE '%{$s_search}%'";
    		$s_where[] = " commune LIKE '%{$s_search}%'";
    		$s_where[] = " district LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	if($search['status_search']>-1){
    		$where.= " AND status = ".$search['status_search'];
    	}
    	if($search['company']>-1){
    		$where.= " AND company_id = ".$search['company'];
    	}
    	$order=" ORDER BY id DESC";
    	//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);	
    }
    public function getCustomerById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM ldc_customers WHERE id = ".$db->quote($id);
    	$sql.=" LIMIT 1 ";
    	$row=$db->fetchRow($sql);
    	return $row;
    }
    public function addCustomer($_data){
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
		    	$this->_name ='ldc_customers';
		    	$arr = array(
		    			'title'	  			=> $_data['title'],
		    			'customer_code'	  	=> $_data['client_no'],
		    			'first_name'	  	=>$_data['first_name'],
		    			'last_name' 		=> $_data['last_name'],
		    			//'note'	      		=> $_data['desc'],
		    			'phone'        		=>$_data['phone'],
		    			'email'				=>$_data['email'],
		    			'house_num'			=>$_data['house_no'],
		    			'street'			=>$_data['street'],
		    			'district' 			=> $_data["district"],
		    			'province_id'	  	=> $_data['province'],
		    			'status'  			=> $_data['status'],
		    			'is_meeting'  		=> $_data['meeting'],
		    			'ceremony_date'  	=> $_data['ceremony_date'],
		    			'ceremony_address'  => $_data['ceremony_add'],
		    			//'discount_login'  => $_data['discount_login'],
		    			);
			    	if(!empty($_data['id'])){
			    		$where = 'id = '.$_data['id'];
			    		$this->update($arr, $where);
			    		return $_data['id'];
			    	
			    	}else{
			    		return  $this->insert($arr);
			    	}
    		
    	}catch(Exception $e){
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
				//	'last_name' 		=> $_data['last_name'],
					//'note'	      		=> $_data['desc'],
					'p_phone'        	=>$_data['p_phone'],
					'p_email'			=>$_data['p_email'],
					'c_phone'        	=>$_data['c_phone'],
					'c_email'			=>$_data['c_email'],
				//	'house_num'			=>$_data['house_no'],
				//	'street'			=>$_data['street'],
				//	'district' 			=> $_data["district"],
			        'date'				=> date('Y-m-d'),
					'note'	  			=> $_data['com_addres'],
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
    	p.`p_phone`,p.`p_email`,p.`company_name`,p.`street`,
    	p.`house_num`,(SELECT d.district_name FROM `ldc_district` AS d WHERE d.id = p.`district` ) AS district,(SELECT k.province_name FROM `ldc_province` AS k WHERE k.id = p.`province_id`)AS province,
    	(SELECT name_en FROM `ldc_view` WHERE key_code = p.`status` AND `type`=2) AS `status` FROM `ldc_supplier` AS p WHERE 1";
		$where = " ";
		if(!empty($search['title'])){
			$s_where = array();
			$s_search = addslashes(trim($search['title']));
			$s_where[] = "p.`su_code` LIKE '%{$s_search}%'";
			$s_where[] = " first_name LIKE '%{$s_search}%'";
			$s_where[] = " last_name LIKE '%{$s_search}%'";
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
	public function addSupplierAjax($_data){
		try{
			//if ($_data['cus_type']==1){
			$this->_name ='ldc_supplier';
			$arr = array(
					'title'	  			=> $_data['title'],
					'su_code'	  		=> $_data['su_no'],
					'first_name'	  	=>$_data['first_name'],
					//	'last_name' 		=> $_data['last_name'],
					//'note'	      		=> $_data['desc'],
					'p_phone'        	=>$_data['p_phone'],
					'p_email'			=>$_data['p_email'],
					'c_phone'        	=>$_data['c_phone'],
					'c_email'			=>$_data['c_email'],
					//	'house_num'			=>$_data['house_no'],
					//	'street'			=>$_data['street'],
					//	'district' 			=> $_data["district"],
					'date'				=> date('Y-m-d'),
					'note'	  			=> $_data['com_addres'],
					'status'  			=> $_data['statuss'],
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
				return  $this->insert($arr);
			}
	
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			echo $e->getMessage();
		}
	}
}

