<?php

class Food_Model_DbTable_DbCategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_food_cat';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    
    function addFoodCategory($data){
    	$adapter = new Zend_File_Transfer_Adapter_Http();
//     	$part= PUBLIC_PATH.'/images/product';
//     	$adapter->setDestination($part);
//     	$adapter->receive();
//     	$photo = $adapter->getFileInfo();
//     	if(!empty($photo['front']['name'])){
//     		$data['front']=$photo['front']['name'];
//     	}else{
//     		$data['front']='';
//     	}
		try{
	    	 $arr =array(
	    	 		'name_kh'=>$data['name_kh'],
	    	 		'name_en'=>$data['name_en'],
	    	 		'user_id'=>$this->getUserId(),
	    	 		'date'=>date("d-m-Y"),
	    	 		'status'=>$data['status'],
	    	 		);
	    		return $this->insert($arr);
		}catch (Exception $e){
			echo $e->getMessage();
		}
    }
    function updateFoodCategory($data){
    	$adapter = new Zend_File_Transfer_Adapter_Http();
//     	$part= PUBLIC_PATH.'/images/product';
//     	$adapter->setDestination($part);
//     	$adapter->receive();
//     	$photo = $adapter->getFileInfo();
//     	if(!empty($photo['front']['name'])){
//     		$data['front']=$photo['front']['name'];
//     	}else{
//     		$data['front']=$data['old_front'];
//     	}
    	$arr =array(
    			'name_kh'=>$data['name_kh'],
    	 		'name_en'=>$data['name_en'],
    	 		'user_id'=>$this->getUserId(),
    	 		'date'=>date("d-m-Y"),
    	 		'status'=>$data['status'],
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
    function getFoodCategoryByid($id){
    	$db = $this->getAdapter();
    	$sql="select * FROM ldc_food_cat WHERE id=".$id;
    	return $db->fetchRow($sql);
    }
   
 function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
    function getAllCategory($search=null){
    	$db=$this->getAdapter();
    	$sql="SELECT id,name_kh,name_en,(SELECT name_en FROM ldc_view WHERE `type`=2 AND key_code=ldc_food_cat.status) AS  `status` 
      			 FROM ldc_food_cat WHERE  1 ";
    	$where ="";
    	if($search['status_search']>-1){
    		$where.= " AND status = ".$search['status_search'];
    	}
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[]= "name_kh LIKE '%{$s_search}%'";
    		$s_where[]= " name_en LIKE '%{$s_search}%'";
    		 
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	$order=" ORDER BY id DESC";
    	//echo $sql.$where;
   		 return $db->fetchAll($sql.$where.$order);
    }

}  
	  

