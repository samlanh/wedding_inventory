<?php

class Order_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_Order';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getOrderID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM ldc_order GROUP BY order_code ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "OR-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
   
    function addOrder($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		if($data['identity_wedding']!=""){
    			$arr =array(
    					'order_code'	=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_wedding'],
    					'type'			=>	1,
    					'price'			=>	$data['t_price_wedding'],
    					'address'		=>	$data['address_wedding'],
    					'date_do'		=>	$data["date_wedding"],
    					'time_do'		=>	$data["time_wedding"],
    					'total_pay'		=>	$data["amount_wedding"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$id = $this->insert($arr);
    			
    			$ids = explode(',', $data['identity_wedding']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'	=>	$id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
    				$this->insert($arr_in);
    			}
    		}
    		
    		if($data['identity_breakfast']!=""){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_breakfast'],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$id = $this->insert($arr);
    			 
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$id,
    						'food_id'=>$data['item_name_breakfast_'.$i],
    						'qty'=>$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
    				$this->insert($arr_in);
    			}
    		}
    		
    		if($data['identity_lunch']!=""){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_lunch'],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$id = $this->insert($arr);
    		
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$id,
    						'food_id'=>$data['item_name_lunch_'.$i],
    						'qty'=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
    				$this->insert($arr_in);
    			}
    		}
	    	 
    		if($data['identity_dinner']){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$id = $this->insert($arr);
    		
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$id,
    						'food_id'=>$data['item_name_dinner_'.$i],
    						'qty'=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
    				$this->insert($arr_in);
    			}
    		}
	    		
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	}
    }
    function updateOrder($data){
    	print_r($data);
    $db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		if($data['identity_wedding']!=""){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_wedding'],
    					'type'			=>	1,
    					'price'			=>	$data['t_price_wedding'],
    					'address'		=>	$data['address_wedding'],
    					'date_do'		=>	$data["date_wedding"],
    					'time_do'		=>	$data["time_wedding"],
    					'total_pay'		=>	$data["amount_wedding"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$where = $db->OrderInto("id=?", $data["id_wedding"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			if($data["id_wedding"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_wedding"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			
    			if($data["id_wedding"]!=""){
	    			$sql_w ="DELETE FROM ldc_order_detail WHERE Order_id=".$data["id_wedding"];
	    			$db->query($sql_w);
    			}
    			$ids = explode(',', $data['identity_wedding']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'	=>	$_id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    		
    		if($data['identity_breakfast']!=""){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_breakfast'],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$where = $db->OrderInto("id=?", $data["id_breakfast"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			if($data["id_breakfast"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_breakfast"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			
    			if($data["id_breakfast"]!=""){
	    			$sql_b ="DELETE FROM ldc_order_detail WHERE Order_id=".$data["id_breakfast"];
	    			$db->query($sql_b);
    			}
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$_id,
    						'food_id'=>$data['item_name_breakfast_'.$i],
    						'qty'=>$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    		
    		if($data['identity_lunch']!=""){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_lunch'],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			
//     			$db->getProfiler()->setEnabled(true);
    			$where = $db->OrderInto("id=?", $data["id_lunch"]);
    			
    			if($data["id_lunch"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_lunch"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			if($data["id_lunch"]!=""){
    			$sql_l ="DELETE FROM ldc_order_detail WHERE Order_id=".$data["id_lunch"];
    			$db->query($sql_l);
    			}
    			
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$_id,
    						'food_id'=>$data['item_name_lunch_'.$i],
    						'qty'=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
	    	 
    		if($data['identity_dinner']){
    			$arr =array(
    					'order_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_order";
    			$where = $db->OrderInto("id=?", $data["id_dinner"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			
    			if($data["id_dinner"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_dinner"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			$this->update($arr, $where);
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			if($data["id_dinner"]!=""){
	    			$sql_d ="DELETE FROM ldc_order_detail WHERE Order_id=".$data["id_dinner"];
	    			$db->query($sql_d);
    			}
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'Order_id'=>$_id,
    						'food_id'=>$data['item_name_dinner_'.$i],
    						'qty'=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_order_detail';
    				
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
	    		
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	} 
    }
    
    function convertToOrder($id){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	$row_Order = $this->getOrder($id);
    	//print_r($row_Order);exit();
    	$order_code = $this->getOrderID();
    	try {
    	if(!empty($row_Order)){
    		foreach ($row_Order as $rs_Order){
    			$arr = array(
    					'order_code'	=>	$order_code,
    					'customer_id'	=>	$rs_Order['customer_id'],
    					'num_table'		=>	$rs_Order['num_table'],
    					'type'			=>	$rs_Order['type'],
    					'price'			=>	$rs_Order['price'],
    					'address'		=>	$rs_Order['address'],
    					'date_do'		=>	$rs_Order["date_do"],
    					'time_do'		=>	$rs_Order["time_do"],
    					'total_pay'		=>	$rs_Order["total_pay"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$rs_Order['status'],
    					//'desc'		=>	$data['note']	
    			);
    			$this->_name = "ldc_order";
//     			$db->getProfiler()->setEnabled(true);
    			$_id = $this->insert($arr);
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			$row_Order_detail = $this->getOrderDetail($rs_Order['id']);
    			
    			foreach ($row_Order_detail as $rs_Order_detail){
    				$arr_detail = array(
    						'order_id'	=>	$_id,
    						'food_id'	=>	$rs_Order_detail['food_id'],
    						'qty'		=>	$rs_Order_detail['qty'],
    				);
    				$this->_name = "ldc_order_detail";
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_detail);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    	}
    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    	}
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    
    function getAllOrder($search){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
				  q.`order_code` AS id,
				  q.`order_code`,
				  
				  (SELECT CONCAT(c.`first_name`,' ',c.`last_name`) FROM `ldc_customers` AS c WHERE c.id=q.`customer_id`) AS customer,
				  (SELECT c.`ceremony_date` FROM `ldc_customers` AS c WHERE c.id=q.`customer_id`) AS date_ceremony,
				  (SELECT qs.`num_table` FROM `ldc_order` AS qs WHERE qs.`order_code`=q.`order_code` AND qs.`type`=1) AS wedding_food_table,
    			 
				  (SELECT qs.`num_table` FROM `ldc_order` AS qs WHERE qs.`order_code`=q.`order_code` AND qs.`type`=2) AS breakfast_food_table,
				  (SELECT qs.`num_table` FROM `ldc_order` AS qs WHERE qs.`order_code`=q.`order_code` AND qs.`type`=3) AS lunch_food_table,
				  (SELECT qs.`num_table` FROM `ldc_order` AS qs WHERE qs.`order_code`=q.`order_code` AND qs.`type`=4) AS dinner_food_table,
    			 q.`status`
				FROM
				  `ldc_order` AS q WHERE 1
				  ";
    	$where ="";
    	if($search['search_status']>-1){
			$where.= " AND status = ".$search['search_status'];
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
		$group_by ="GROUP BY q.`order_code`";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$group_by.$order);	
    }
    function getOrderDetailByid($id,$type){
    	$db = $this->getAdapter();
    	$sql="SELECT 
				  qs.*,
				  q.`id`,
				  q.`num_table`,
				  q.`price`,
				  q.`time_do`,
				  q.`date_do`,
				  q.`address`,
				  q.`total_pay`
				FROM
				  `ldc_order_detail` AS qs,
				  `ldc_order` AS q 
				WHERE q.`id` = qs.`Order_id` 
				  AND q.`order_code` = '$id'
				  AND q.type = $type ";
    	return $db->fetchAll($sql);
    }
    
    function getOrderDetail($ids){
    	$db = $this->getAdapter();
    	$sql="SELECT
		    	qs.*
		    FROM
		    	`ldc_order_detail` AS qs
		    WHERE qs.`Order_id` = $ids";
    	echo $sql;
    	return $db->fetchAll($sql);
    }
    function getOrderByid($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  q.*,
				  c.`phone`,
				  c.`email`,
				  c.`house_num`,
				  c.`street`,
				  (SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=c.`province_id`) AS province,
				  (SELECT p.`province_id` FROM `ldc_district` AS p WHERE p.`id`=c.`district`) AS district
				FROM
				  `ldc_order` AS q,
				  `ldc_customers` AS c 
				WHERE q.`order_code` = '$id' 
				  AND q.`customer_id` = c.`id`";
    	return $db->fetchRow($sql);
    }
    function getOrder($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT
			    	q.*
			    FROM
			    	`ldc_order` AS q,
			    	`ldc_customers` AS c
			    WHERE q.`order_code` = '$id'
			    	AND q.`customer_id` = c.`id`";
			   return $db->fetchAll($sql);
    }
 	function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
    
    function getQuoteNo(){
    	$db = $this->getAdapter();
    	$sql = "SELECT q.`quot_code` FROM `ldc_quotation` AS q WHERE q.`status`=1 GROUP BY q.`quot_code`";
    	return $db->fetchAll($sql);
    }
    
    function getCustomerByQuoteCode($code){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  c.id,
				  c.`phone`,
				  c.`email`,
				  c.`ceremony_date`,
				  c.`house_num`,
				  c.`street`,
				  (SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=c.`province_id`) AS province,
				  (SELECT p.`province_id` FROM `ldc_district` AS p WHERE p.`id`=c.`district`) AS district
				FROM
				  `ldc_customers` AS c,
				  `ldc_quotation` AS q 
				WHERE c.`id` = q.`customer_id` 
				  AND q.`quot_code` ='$code' 
				GROUP BY q.`quot_code`";
    	return $db->fetchRow($sql);
    }

}  
	  

