<?php

class Service_Model_DbTable_DbService extends Zend_Db_Table_Abstract
{
    protected $_name = 'ldc_service';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addService($data){
		try{
	    	 $arr =array(
	    	 		'title'		=>	$data['name_kh'],
	    	 		'user_id'	=>	$this->getUserId(),
	    	 		'date'		=>	new Zend_Date(),
	    	 		'status'	=>	$data['status'],
	    	 		);
	    		return $this->insert($arr);
		}catch (Exception $e){
			echo $e->getMessage();
		}
    }
    function updateService($data){
    	$arr =array(
    			'title'		=>	$data['name_kh'],
    	 		'user_id'	=>	$this->getUserId(),
    	 		'date'		=>	new Zend_Date(),
    	 		'status'	=>	$data['status'],
    	);
    	$where ='id = '.$data['id'];
    	$this->update($arr, $where);
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    
    function getAllproduct($search){
    	$db = $this->getAdapter();
    	$sql ="SELECT id,`pro_no`,`pro_name_kh`,`pro_name_en`,`bar_code`,`price`,(SELECT CONCAT(name_kh,' ',name_en) FROM `ldc_item_cat` WHERE id = category_id ) AS `cate`,`status` FROM `ldc_product` WHERE 1";
    	$where ="";
    	if($search['search_status']>-1){
			$where.= " AND status = ".$search['search_status'];
		}
		if($search['make']>0){
			$where.=" AND category_id = ".$search['make'];
		}
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search=$search['adv_search'];
			$s_where[]= " pro_no LIKE '%{$s_search}%'";
			$s_where[]=" pro_name LIKE '%{$s_search}%'";
			$s_where[]= " bar_code LIKE '%{$s_search}%'";
			$s_where[]= " price LIKE '%{$s_search}%'";
			//$s_where[]= " cate LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		$order = " ORDER BY id DESC";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$order);	
    }
    function getServiceByid($id){
    	$db = $this->getAdapter();
    	$sql="select * FROM $this->_name WHERE id=".$id;
    	return $db->fetchRow($sql);
    }
   
 function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
    
    function getAllService(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title,date,status FROM $this->_name WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }

}  
	  

