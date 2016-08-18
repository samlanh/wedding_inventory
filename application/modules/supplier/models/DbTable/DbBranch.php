<?php

class Group_Model_DbTable_DbBranch extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_customers';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function addBranch($data){
    	$db = $this->getAdapter();
    	$sql ='SELECT p.`title` FROM `ldc_partner` AS p WHERE p.`id`= '.$data['company_name'];
    	$com_name = $db->fetchOne($sql);
    	$arr = array(
    			'customer_code'=>$data['branch_id'],
    			'first_name'=>$com_name,
    			'last_name'=>$data['branch_name'],
    			'note'=>$data['branch_not'],
    			'status'=>$data['status'],
    			'group_num'=>$data['group_no'],
    			'house_num'=>$data['home_no'],
    			'street'=>$data['street_no'],
    			'commune'=>$data['commune'],
    			'district'=>$data['district'],
    			'province_id'=>$data['province'],
    			'phone'=>$data['phone'],
    			'email'=>$data['email'],
    			'fax'=>$data['fax'],
    			'cus_type'=>2,
    			'company_id'=>$data['company_name'],
    			);
    	return $this->insert($arr);
    }
    public function getAllBranchInfo($search){
    	$db = $this->getAdapter();
    	$sql = 'SELECT p.`id`,(SELECT a.title FROM `ldc_partner` AS a WHERE a.id =p.`company_id` ) AS company,CONCAT (p.`first_name`," ",p.`last_name`) AS `branch`,p.`phone`,p.`email`,p.`fax`,p.`commune`,p.`district`,(SELECT b.province_name FROM `ldc_province`AS b WHERE b.id = p.`province_id` LIMIT 1) AS province,(SELECT c.name_en FROM `ldc_view` AS c WHERE c.key_code = p.status AND c.type = 2 LIMIT 1) AS `status` FROM `ldc_customers` AS p WHERE p.`cus_type`= 2 ' ;
    	$where ="";
    	if(!empty($search['title'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[] = "(SELECT a.title FROM `ldc_partner` AS a WHERE a.id =p.`company_id` ) LIKE '%{$s_search}%'";
    		$s_where[] = " CONCAT (p.`first_name`,' ',p.`last_name`) LIKE '%{$s_search}%'";
    		$s_where[] = "phone LIKE '%{$s_search}%'";
    		$s_where[] = "email LIKE '%{$s_search}%'";
    		$s_where[] = " house_num LIKE '%{$s_search}%'";
    		$s_where[] = " commune LIKE '%{$s_search}%'";
    		$s_where[] = " district LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	if($search['status_search']>-1){
    		$where.= " AND status = ".$search['status_search'];
    	}
    	$order ="";
    	return $db->fetchAll($sql.$where.$order);
    }
    public function getBranchByID($id){
    	$db = $this->getAdapter();
    	$sql= 'SELECT * FROM `ldc_customers` AS p WHERE p.`cus_type`= 2 AND p.`id`='.$id;
    	return $db->fetchRow($sql);
    }
    public function updateBranch($data){
    	$db = $this->getAdapter();
    	
    	$sql ='SELECT p.`title` FROM `ldc_partner` AS p WHERE p.`id`= '.$data['company_name'];
    	$com_name = $db->fetchOne($sql);
    	
    	$arr = array(
    			'customer_code'=>$data['branch_id'],
    			'first_name'=>$com_name,
    			'last_name'=>$data['branch_name'],
    			'note'=>$data['branch_not'],
    			'status'=>$data['status'],
    			'group_num'=>$data['group_no'],
    			'house_num'=>$data['home_no'],
    			'street'=>$data['street_no'],
    			'commune'=>$data['commune'],
    			'district'=>$data['district'],
    			'province_id'=>$data['province'],
    			'phone'=>$data['phone'],
    			'email'=>$data['email'],
    			'fax'=>$data['fax'],
    			'cus_type'=>2,
    			'company_id'=>$data['company_name'],
    	);
    	//print_r($arr);exit();
    	$where = 'id='.$data['id'];
    	return $this->update($arr, $where);
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    public function getCompanyName(){
    	$db = $this->getAdapter();
    	$sql='SELECT p.`id`,p.`title` FROM `ldc_partner` AS p WHERE `status`=1';
    	return $db->fetchAll($sql);
    }
    
    public function getNewCustomerId(){
     	$this->_name='ldc_customers';
   	$db = $this->getAdapter();
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = "CUS-";
   	for($i = $acc_no;$i<4;$i++){
   		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
    public function getAllProvince(){
    	$this->_name='ldc_province';
    	$sql = " SELECT id,province_name FROM $this->_name WHERE status=1 AND province_name!='' ORDER BY id DESC";
    	$db = $this->getAdapter();
    	return $db->fetchAll($sql);
    }
}

