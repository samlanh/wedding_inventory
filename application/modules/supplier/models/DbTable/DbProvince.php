<?php

class Group_Model_DbTable_DbProvince extends Zend_Db_Table_Abstract
{

  protected $_name = 'ldc_province';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function addProvince($data){
    	$db = $this->getAdapter();
    	
    	$arr = array(
    			'province_name'=>$data['province_name'],
    			'status'=>$data['status'],
    			);
    	return $this->insert($arr);
    }
    public function updateProvince($data){
    	$db = $this->getAdapter();
    	$arr = array(
    			'province_name'=>$data['province_name'],
    			'status'=>$data['status'],
    	);
    	$where = 'id='.$data['id'];
    	return $this->update($arr,$where);
    }
    public function getAllProvince($search){
    	$db = $this->getAdapter();
    	$sql = 'SELECT p.id,p.`province_name`,(SELECT s.name_en FROM ldc_view AS s WHERE s.type=2 AND s.key_code = p.`status`) AS STATUS FROM `ldc_province` AS p WHERE 1' ;
    	$where ="";
    	if(!empty($search['title'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[] = " p.`province_name` LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	if($search['status_search']>-1){
    		$where.= " AND p.status = ".$search['status_search'];
    	}
    	$order ="";
    	return $db->fetchAll($sql.$where.$order);
    }
    public function getProvinceByID($id){
    	$db = $this->getAdapter();
    	$sql= 'SELECT * FROM `ldc_province` AS p WHERE id ='.$id;
    	return $db->fetchRow($sql);
    }
    
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    public function getAllprovincetoFront(){
    	$db = $this->getAdapter();
    	$sql = 'SELECT p.id,p.`province_name` FROM `ldc_province` AS p WHERE p.`status` =1' ;
    	return $db->fetchAll($sql);
    }
   
}

