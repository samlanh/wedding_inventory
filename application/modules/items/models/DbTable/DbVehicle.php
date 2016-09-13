<?php

class Items_Model_DbTable_DbVehicle extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_product';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getProID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM ldc_product ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "PR-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
   public function getUnitOption(){
    	$db = $this->getAdapter();
    	$sql="SELECT p.id,CONCAT(p.`measure_name_kh`,' ',p.`measure_name_en`) as name FROM `ldc_measure` AS p WHERE p.`status`=1 ORDER BY p.`id`DESC";
    	return $db->fetchAll($sql);
    }
    function addProduct($data){
    	$adapter = new Zend_File_Transfer_Adapter_Http();
    	$part= PUBLIC_PATH.'/images/product';
    	$adapter->setDestination($part);
    	$adapter->receive();
    	$photo = $adapter->getFileInfo();
    	if(!empty($photo['front']['name'])){
    		$data['front']=$photo['front']['name'];
    	}else{
    		$data['front']='';
    	}
    	 $arr =array(
    	 		'pro_no'		=>	$data['product_no'],
    	 		'pro_name_kh'	=>	$data['product_name_kh'],
    	 		//'pro_name_en'=>$data['product_name_en'],
    	 		'category_id'	=>	$data['make'],
    	 		'price'			=>	$data['price'],
    	 		'unit'			=>	$data['unit'],
    	 		'bar_code'		=>	$data['product_no'],
    	 		'img_front'		=>	$data['front'],
    	 		'user_id'		=>	$this->getUserId(),
    	 		'date'			=>	date("d-m-Y"),
    	 		'status'		=>	$data['status'],
    	 		'description'	=>	$data['note']
    	 		);
    	$id =  $this->insert($arr);
    	$ids = explode(',', $data["identity"]);
    	
    	if($data["identity"]){
    		foreach ($ids as $i){
    			$arr_in = array(
    					'item_id'	=>	$id,
    					'su_id'		=>	$data['supplier_name_'.$i],
    			);
    			$this->_name ='ldc_item_supplier';
    			$this->insert($arr_in);
    		}
    	}
    	
    }
    function updateProduct($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
	    	$adapter = new Zend_File_Transfer_Adapter_Http();
	    	$part= PUBLIC_PATH.'/images/product';
	    	$adapter->setDestination($part);
	    	$adapter->receive();
	    	$photo = $adapter->getFileInfo();
	    	if(!empty($photo['front']['name'])){
	    		$data['front']=$photo['front']['name'];
	    	}else{
	    		$data['front']=$data['old_front'];
	    	}
	    	$arr =array(
	    			//'pro_no'=>$data['product_no'],
	    			'pro_name_kh'=>$data['product_name_kh'],
	    	 		//'pro_name_en'=>$data['product_name_en'],
	    			'category_id'=>$data['make'],
	    			'price'=>$data['price'],
	    			'unit'=>$data['unit'],
	    			//'bar_code'=>$data['bar_code'],
	    			'img_front'=>$data['front'],
	    			'user_id'=>$this->getUserId(),
	    			'date'=>date("d-m-Y"),
	    			'status'=>$data['status'],
	    			'description'=>$data['note']
	    	);
	    		$where ='id = '.$data['id'];
	    		$this->update($arr, $where);
	    		$sql ="DELETE FROM ldc_item_supplier WHERE item_id=".$data['id'];
	    		$db->query($sql);
	    		$ids = explode(',', $data["identity"]);
	    		 
	    		if($data["identity"]){
	    			foreach ($ids as $i){
	    				$arr_in = array(
	    						'item_id'	=>	$data['id'],
	    						'su_id'		=>	$data['supplier_name_'.$i],
	    				);
	    				$this->_name ='ldc_item_supplier';
	    				$this->insert($arr_in);
	    			}
	    		}
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage(); exit();
    	} 
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    
    function getAllproduct($search){
    	$db = $this->getAdapter();
    	$sql ="SELECT id,`pro_no`,`pro_name_kh`,`price`,(SELECT CONCAT(name_kh,' ',name_en) FROM `ldc_item_cat` WHERE id = category_id ) AS `cate`,`status` FROM `ldc_product` WHERE 1";
    	$where ="";
    	if($search['status_search']>-1){
			$where.= " AND status = ".$search['status_search'];
		}
		if($search['make']>0){
			$where.=" AND category_id = ".$search['make'];
		}
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search = addslashes(trim($search['adv_search']));
			$s_where[]= " pro_no LIKE '%{$s_search}%'";
			$s_where[]=" pro_name_kh LIKE '%{$s_search}%'";
			$s_where[]=" pro_name_en LIKE '%{$s_search}%'";
			$s_where[]= " bar_code LIKE '%{$s_search}%'";
			$s_where[]= " price LIKE '%{$s_search}%'";
			//$s_where[]= " cate LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		$order = " ORDER BY id DESC";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$order);	
    }
    function getProductByid($id){
    	$db = $this->getAdapter();
    	$sql="select * FROM ldc_product WHERE id=".$id;
    	return $db->fetchRow($sql);
    }
   
 	function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
    function getAllMake(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title FROM ldc_item_cat WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }
    
	function getSupplier(){
		$db = $this->getAdapter();
		$sql ="SELECT s.id As id,s.`company_name` As name FROM `ldc_supplier` AS s WHERE s.`status`=1";
		return $db->fetchAll($sql);
	}
	
	function getItemSupplier($id){
		$db = $this->getAdapter();
		$sql ="SELECT i.`item_id`,i.`su_id` FROM `ldc_item_supplier` AS i WHERE i.`item_id`=$id";
		
		return $db->fetchAll($sql);
	}
	function getItem($name,$cat_id,$mea_id){
		$db=$this->getAdapter();
		$sql="SELECT  p.id,p.pro_name_kh,ip.su_id
					FROM ldc_product AS p,
					  ldc_item_supplier AS ip
					WHERE p.id = ip.item_id
					    AND p.category_id =$cat_id
					    AND p.unit = $mea_id
						AND p.pro_name_kh='$name'";
		return $db->fetchRow($sql);
	}
	
}  
	  

