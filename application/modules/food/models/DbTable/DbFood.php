<?php

class Food_Model_DbTable_DbFood extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_food';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getFoodID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "FO-";
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
    function addFood($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
	    	$adapter = new Zend_File_Transfer_Adapter_Http();
	    	$part= PUBLIC_PATH.'/images/food';
	    	$adapter->setDestination($part);
	    	$adapter->receive();
	    	$photo = $adapter->getFileInfo();
	    	if(!empty($photo['front']['name'])){
	    		$data['front']=$photo['front']['name'];
	    	}else{
	    		$data['front']='';
	    	}
	    	 $arr =array(
	    	 		'food_code'=>$data['food_no'],
	    	 		'name_kh'=>$data['name_kh'],
	    	 		//'name_en'=>$data['name_en'],
	    	 		'cat_id'=>$data['make'],
	    	 		'price'=>$data['price'],
	    	 		'img'=>$data['front'],
	    	 		'user_id'=>$this->getUserId(),
	    	 		'date'=>date("d-m-Y"),
	    	 		'status'=>$data['status'],
	    	 		'desc'=>$data['note']
	    	 		);
	    		$id = $this->insert($arr);
	
	    		$ids = explode(',', $data['identity']);
	    		foreach ($ids as $i){
	    			$arr_in = array(
	    					'food_id'		=>	$id,
	    					'item_id'		=>	$data['item_name_'.$i],
	    					'price'			=>	$data['price'.$i],
	    					'qty'			=>	$data['qty'.$i],
	    					//'amount'=>$data['amount'.$i],
	    					'su_id'			=>	$data['supplier_name_'.$i],
	    					'is_allocate'	=>	$data['al_food_'.$i],
	    					'deliver_day'	=>	$data['deliver_day_'.$i]
	    			);
	    			$this->_name ='ldc_food_ingredients';
	    			$this->insert($arr_in);
	    		}
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();exit();
    	}
    }
    function updateFood($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
	    	$adapter = new Zend_File_Transfer_Adapter_Http();
	    	$part= PUBLIC_PATH.'/images/food';
	    	$adapter->setDestination($part);
	    	$adapter->receive();
	    	$photo = $adapter->getFileInfo();
	    	if(!empty($photo['front']['name'])){
	    		$data['front']=$photo['front']['name'];
	    	}else{
	    		$data['front']=$data['old_front'];
	    	}
	    	 $arr =array(
	    	 		'food_code'		=>		$data['food_no'],
	    	 		'name_kh'		=>		$data['name_kh'],
	    	 		//'name_en'		=>		$data['name_en'],
	    	 		'cat_id'		=>		$data['make'],
	    	 		//'price'			=>		$data['price'],
	    	 		'img'			=>		$data['front'],
	    	 		'user_id'		=>		$this->getUserId(),
	    	 		'date'			=>		date("d-m-Y"),
	    	 		'status'		=>		$data['status'],
	    	 		'desc'			=>		$data['note']
	    	 	);
	    	 $where = $db->quoteInto("id=?", $data['id']);
	    	 $this->update($arr, $where);
			
	    	 $sql_d ="DELETE FROM `ldc_food_ingredients` WHERE food_id=".$data['id'];
	    	 $db->query($sql_d);
	    	 
	    		$ids = explode(',', $data['identity']);
	    		foreach ($ids as $i){
	    			$arr_in = array(
	    					'food_id'		=>	$data['id'],
	    					'item_id'		=>	$data['item_name_'.$i],
	    					'price'			=>	$data['price'.$i],
	    					'qty'			=>	$data['qty'.$i],
	    					//'amount'	=>	$data['amount'.$i],
	    					'su_id'			=>	$data['supplier_name_'.$i],
	    					'is_allocate'	=>	$data['al_food_'.$i],
	    					'deliver_day'	=>	$data['deliver_day_'.$i]
	    			);
	    			$this->_name ='ldc_food_ingredients';
	    			$this->insert($arr_in);
	    		}
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();exit();
    	} 
    }
    function copyFood($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$adapter = new Zend_File_Transfer_Adapter_Http();
	    	$part= PUBLIC_PATH.'/images/food';
	    	$adapter->setDestination($part);
	    	$adapter->receive();
	    	$photo = $adapter->getFileInfo();
	    	if(!empty($photo['front']['name'])){
	    		$data['front']=$photo['front']['name'];
	    	}else{
	    		$data['front']=$data['old_front'];
	    	}
    		$arr =array(
    				'food_code'=>$data['food_no'],
    				'name_kh'=>$data['name_kh'],
    				'name_en'=>$data['name_en'],
    				'cat_id'=>$data['make'],
    				'price'=>$data['price'],
    				'img'=>$data['front'],
    				'user_id'=>$this->getUserId(),
    				'date'=>date("d-m-Y"),
    				'status'=>$data['status'],
    				'desc'=>$data['note']
    		);
    		$id = $this->insert($arr);
    
    		$ids = explode(',', $data['identity']);
    		foreach ($ids as $i){
    			$arr_in = array(
    					'food_id'	=>	$id,
    					'item_id'	=>	$data['item_name_'.$i],
    					'price'		=>	$data['price'.$i],
    					'qty'		=>	$data['qty'.$i],
    					//'amount'=>$data['amount'.$i],
    					'su_id'		=>	$data['supplier_name_'.$i],
    					'is_allocate'	=>	$data['al_food_'.$i]
    			);
    			$this->_name ='ldc_food_ingredients';
    			$this->insert($arr_in);
    		}
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();exit();
    	}
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    
    function getAllFood($search){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
				  id,
				  `food_code`,
				  `name_kh`,
				  (SELECT 
				    CONCAT(name_kh, ' ', name_en) 
				  FROM
				    `ldc_food_cat` 
				  WHERE id = f.`cat_id`) AS `cate`,
				  `status` 
				FROM
				  `ldc_food` AS f 
				WHERE 1 ";
    	$where ="";
    	if($search['status_search']>-1){
			$where.= " AND status = ".$search['status_search'];
		}
		if($search['make']>0){
			$where.=" AND cat_id = ".$search['make'];
		}
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search = addslashes(trim($search['adv_search']));
			$s_where[]= " name_en LIKE '%{$s_search}%'";
			$s_where[]=" name_kh LIKE '%{$s_search}%'";
			$s_where[]= " food_code LIKE '%{$s_search}%'";
			$s_where[]= " price LIKE '%{$s_search}%'";
			//$s_where[]= " cate LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		$order = " ORDER BY id DESC";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$order);	
    }
    function getFoodByid($id){
    	$db = $this->getAdapter();
    	$sql="select * FROM ldc_food WHERE id=".$id;
    	return $db->fetchRow($sql);
    }
   
 	function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
	function getAllMake(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title FROM ldc_food_cat WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }
    
    function getFoodIngredients($id){
    	$db=$this->getAdapter();
    	$sql="SELECT f.*,m.`measure_name_kh` FROM `ldc_food_ingredients` AS f,`ldc_product` AS p, `ldc_measure` AS m WHERE p.`unit`=m.`id` AND f.`item_id`=p.`id` AND f.`food_id` =$id";
    	return $db->fetchAll($sql);
    }
    
    function getSupplierByItemId($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT s.id,s.`company_name` as name FROM `ldc_supplier` AS s,`ldc_item_supplier` AS ts WHERE s.`id`=ts.`su_id` AND s.`status`=1 AND ts.`item_id`= $id";
    	return $db->fetchAll($sql);
    }
    
    function getSupplier(){
    	$db = $this->getAdapter();
    	$sql = "SELECT s.id,s.`company_name` FROM `ldc_supplier` AS s WHERE s.`status`=1";
    	return $db->fetchAll($sql);
    }

}  
	  

