<?php

class Items_Model_DbTable_DbMeasure extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_measure';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function addMeasure($data){
    	$db =$this->getAdapter();
    	$arr = array(
    			'measure_name_en'=>$data['measure_name_en'],
    			'measure_name_kh'=>$data['measure_name_kh'],
    			'status'=>$data['status'],
    			'description'=>$data['description'],
    			'user_id'=>$this->getUserId(),
    			);
    	$this->insert($arr);
    }
    public function getMeasure($search=null){
    	$db=$this->getAdapter();
    	$sql = 'SELECT p.id,p.`measure_name_kh`,p.`measure_name_en`,p.`description`,( SELECT c.name_en FROM ldc_view AS c WHERE c.`type`=2 AND c.key_code = p.`status` LIMIT 1) AS `status` FROM `ldc_measure` AS p WHERE 1';
    	$where ="";
    	if($search['search_status']>-1){
    		$where.= " AND status = ".$search['search_status'];
    	}
    	if(!empty($search['adv_search'])){
    		$s_where=array();
    		$s_search = addslashes(trim($search['adv_search']));
    		$s_where[]= " p.`measure_name_kh` LIKE '%{$s_search}%'";
    		$s_where[]= " p.`measure_name_en` LIKE '%{$s_search}%'";
    		$s_where[]= " p.`description` LIKE '%{$s_search}%'";
    		 
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	$order = " ORDER BY p.id DESC";
    	return $db->fetchAll($sql.$where.$order);
    }
    public function getMeasureByID($id){
    	$db = $this->getAdapter();
    	$sql ='Select * From ldc_measure Where id='.$id;
    	return $db->fetchRow($sql);
    	
    }
    public function updateMeasure($data){
    	$db= $this->getAdapter();
    	$arr= array(
    			'measure_name_en'=>$data['measure_name_en'],
    			'measure_name_kh'=>$data['measure_name_kh'],
    			'status'=>$data['status'],
    			'description'=>$data['description'],
    			'user_id'=>$this->getUserId(),
    			);
    	$where = 'id = '.$data['id'];
    	$this->update($arr, $where);
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
   public function getUnitOption(){
    	$db = $this->getAdapter();
    	$sql="SELECT id,name_en,name_kh,key_code FROM `ldc_view` WHERE `type`=9";
    	return $db->fetchAll($sql);
    }
   
    
    
}  
	  

