<?php

class Application_Model_DbTable_DbService extends Zend_Db_Table_Abstract
{
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
  public function getAllServiceList($limit =null,$where=null){
  	$db = $this->getAdapter();
  	$sql="SELECT *  FROM `ldc_serviceprice`  WHERE status=1 ";
  	if($where!=null){
  		$sql.=" AND id != $where ";
  	}
  	if($limit!=null){
  		$sql.=" LIMIT $limit";
  	}
  	
  	return $db->fetchAll($sql);
  }
  public function getServiceDetailId($id){
  	$db = $this->getAdapter();
  	$sql="SELECT *  FROM `ldc_serviceprice`  WHERE status=1 AND id=$id LIMIT 1 ";
  	return $db->fetchRow($sql);
  }
}
?>