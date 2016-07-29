<?php

class Items_Model_DbTable_DbMake extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_item_cat';
    function addMake($data){
    	try{
    $adapter = new Zend_File_Transfer_Adapter_Http();
    	$part= PUBLIC_PATH.'/images/product';
    	$adapter->setDestination($part);
    	$adapter->receive();
    	$photo = $adapter->getFileInfo();
    	if(!empty($photo['photo']['name'])){
    		$data['photo']=$photo['photo']['name'];
    	}else{
    		$data['photo']='';
    	}
    	$_arr = array(
    			'name_en'		=>$data['name_en'],
    			'name_kh'		=>$data['name_kh'],
    			'img_feature'	=>$data['photo'],
    			'status'		=>1,
    			);
    	$this->insert($_arr);//insert data
    	}catch (Exception $e){
    		echo $e->getMessage();
    	}
    }
    public function updateMake($data){
    	$db = $this->getAdapter();
    	$adapter = new Zend_File_Transfer_Adapter_Http();
    	$part= PUBLIC_PATH.'/images/product';
    	$adapter->setDestination($part);
    	$adapter->receive();
    	$photo = $adapter->getFileInfo();
    	if(!empty($photo['photo']['name'])){
    		$data['photo']=$photo['photo']['name'];
    	}else{
    		$data['photo']=$data['old_photo'];
    	}
   	$_arr = array(
    			'name_en'		=>$data['name_en'],
    			'name_kh'		=>$data['name_kh'],
    			'img_feature'	=>$data['photo'],
    			'status'=>1,
    	);
    	$where=$db->quoteInto("id=?",$data['id']);
    	$this->update($_arr, $where);
    }
    	
    function getAllMake($search=null){
    	$db = $this->getAdapter();
    	$sql='SELECT id,name_en,name_kh,img_feature,(SELECT name_en FROM ldc_view WHERE TYPE=2 AND key_code=status) AS status
    	FROM ldc_item_cat WHERE status=1 ';
    	$order=' ORDER BY id DESC';
        return $db->fetchAll($sql.$order);
    }
    
 function getMakeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh ,status,img_feature FROM  $this->_name WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
//     public static function getBranchCode(){
//     	$db = new Application_Model_DbTable_DbGlobal();
//     	$sql = "SELECT COUNT(br_id) AS amount FROM `ln_branch`";
//     	$acc_no= $db->getGlobalDbRow($sql);
//     	$acc_no=$acc_no['amount'];
//     	$new_acc_no= (int)$acc_no+1;
//     	$acc_no= strlen((int)$acc_no+1);
//     	$pre = "";
//     	for($i = $acc_no;$i<3;$i++){
//     		$pre.='0';
//     	}
//     	return "C-".$pre.$new_acc_no;
//     }
  
}  
	  

