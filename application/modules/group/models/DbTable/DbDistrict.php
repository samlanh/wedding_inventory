<?php

class Group_Model_DbTable_DbDistrict extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_district';
//     public function getUserId(){
//     	$session_user=new Zend_Session_Namespace('auth');
//     	return $session_user->user_id;
    	 
//     }
    public function addDistrict($data){
    	$db = $this->getAdapter();
    	
    	$arr = array(
    			'province_id'=>$data['province_id'],
    			'district_name'=>$data['district_name'],
    			'status'=>$data['status'],
    			);
    	return $this->insert($arr);
    }
    public function updateDistrict($data){
    	$db = $this->getAdapter();
    	$arr = array(
    			'province_id'=>$data['province_id'],
    			'district_name'=>$data['district_name'],
    			'status'=>$data['status'],
    			);
    	$where = 'id='.$data['id'];
    	return $this->update($arr,$where);
    }
    public function getAlldistrict($search){
    	$db = $this->getAdapter();
    	$sql = 'SELECT p.id,p.`district_name`,(SELECT k.province_name FROM `ldc_province` AS k WHERE k.id=p.`province_id`)AS province_name,(SELECT s.name_en FROM ldc_view AS s WHERE s.type=2 AND s.key_code = p.`status`) AS STATUS FROM `ldc_district` AS p WHERE 1' ;
    	$where ="";
    	if(!empty($search['title'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['title']));
    		$s_where[] = " p.`district_name` LIKE '%{$s_search}%'";
    		$s_where[] = "(SELECT k.province_name FROM `ldc_province` AS k WHERE k.id=p.`province_id`) LIKE '%{$s_search}%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	if($search['status_search']>-1){
    		$where.= " AND p.status = ".$search['status_search'];
    	}
    	if($search['province_id']>-1){
    		$where.= " AND p.province_id = ".$search['province_id'];
    	}
    	$order ="";
    	return $db->fetchAll($sql.$where.$order);
    }
    public function getAllProvince(){
    	$db = $this->getAdapter();
    	$sql='SELECT p.id,p.`province_name`FROM `ldc_province` AS p WHERE p.status=1';
    	return $db->fetchAll($sql);
    }
    public function getDistrictByID($id){
    	$db = $this->getAdapter();
    	$sql= 'SELECT * FROM `ldc_district` AS p WHERE id ='.$id;
    	return $db->fetchRow($sql);
    }
  
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
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
    
    	function getAllDistrictByID($id){
    		$db= $this->getAdapter();
    		$sql="SELECT id,district_name FROM ldc_district WHERE status=1 and province_id =".$id;
    		$rows=$db->fetchAll($sql);
    		$options = '';
    		if(!empty($rows))foreach($rows as $value){
    			$options .= '<option value="'.$value['id'].'" >'.htmlspecialchars($value['district_name'], ENT_QUOTES).'</option>';
    		}
    		return $options;
    	}
}

