<?php

class Category_Model_DbTable_DbCategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_category';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    public function getAllcategory(){
    	$db = $this->getAdapter();
    	$sql ="SELECT id,category_name,(SELECT category_name FROM ldc_category as p WHERE p.id = a.parent)as parent,description,create_date,(SELECT name_en FROM ldc_view WHERE key_code= a.status AND `type`= 2)as status,user_id FROM ldc_category as a WHERE status=1 ";
    	return $db->fetchAll($sql);
    }
    public function getAllcategoryByID($id){
    	$db = $this->getAdapter();
    	$sql ="SELECT id,category_name,parent,description,status FROM ldc_category WHERE id=".$id;
    	return $db->fetchRow($sql);
    }
    public function getCategory(){
    	$db = $this->getAdapter();
    	$sql ="SELECT id,category_name FROM ldc_category WHERE status=1 ";
    	return $db->fetchAll($sql);
    }
   	public function getStatus(){
   		$db = $this->getAdapter();
   		$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
   		return $db->fetchAll($sql);
   	}
    public function addCategory($data){
    	$db = $this->getAdapter();
    	$arr = array(
    			'category_name'=>$data['category_name'],
    			'parent'=>$data['category'],
    			'description'=>$data['description'],
    			'create_date'=>date("Y-m-d"),
    			'status'=>$data['status'],
    			'user_id'=> $this->getUserId()
    			);
    	$this->insert($arr);
    }
    public function editCategory($data,$id){
    	
    	$db= $this->getAdapter();
    	$arr = array(
    			'category_name'=>$data['category_name'],
    			'parent'=>$data['category'],
    			'description'=>$data['description'],
    			'create_date'=>date("Y-m-d"),
    			'status'=>$data['status'],
    			'user_id'=> $this->getUserId()
    	);
    	$where = $this->getAdapter()->quoteInto("id=?",$id);
    	$this->update($arr, $where);
    }
}  
	  

