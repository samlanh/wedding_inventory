<?php

class Purchase_Model_DbTable_DbPurchase extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_quotation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getPurchaseID($id){
    	$db =$this->getAdapter();
    	$old_pu = $this->getPuCode($id);
    	$sql=" SELECT id FROM ldc_purchase_order ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "PU-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	if(empty($old_pu)){
    		return $pre.$new_acc_no;
    	}else {
    		return $old_pu;
    	}
    	
    }
    
    function getPuCode($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT p.`pu_code` FROM `ldc_purchase_order` AS p WHERE p.`order_id` = $id LIMIT 1";
    	return $db->fetchOne($sql);
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
    
    function getItemPurchaseByQuoteId(){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  q.id,
				  q.`quot_code`,
				  qc.`num_table`,
				  qc.`date_do`,
				  qc.`address`,
				  p.`pro_name_kh`,
				  qt.* 
				FROM
				  `ldc_quotation` AS q,
				  `ldc_quotation_connection` AS qc,
				  `ldc_quote_item` AS qt,
				  `ldc_product` AS p 
				WHERE q.id = qc.`quote_id` 
				  AND qc.id = qt.`qc_id` 
				  AND qt.`item_id` = p.`id` 
				GROUP BY qt.`item_id`, qc.`address`,qt.`type`
				ORDER BY qc.`date_do`,
				  qt.`type`,
				  qc.`address`,
				  qt.`su_id`";
    	
    	return $db->fetchAll($sql);
    }
    
    function getQuoteCode(){
    	$db = $this->getAdapter();
    	$sql ="SELECT q.id,q.`quot_code` FROM `ldc_quotation` AS q WHERE q.`status`=1";
    	return $db->fetchAll($sql);
    }
    
    function getSupplier(){
    	$db = $this->getAdapter();
    	$sql ="SELECT s.id,s.`company_name` FROM `ldc_supplier` AS s WHERE s.`status`=1";
    	return $db->fetchAll($sql);
    }
    function getFood(){
    	$db = $this->getAdapter();
    	$sql = "SELECT f.`id`,f.`name_kh`,f.`name_en` FROM `ldc_food` AS f WHERE f.`status`=1";
    	return $db->fetchAll($sql);
    }
    function getItem(){ // Intergrediant of food
    	$db= $this->getAdapter();
    	$sql = "SELECT p.id,p.`pro_name_kh`,p.`pro_name_en` FROM `ldc_product` AS p WHERE p.`status`=1";
    	return $db->fetchAll($sql);
    }
    
    function getMeasureNameByItemId($id){
    	$db= $this->getAdapter();
    	$sql = "SELECT m.`id`,m.`measure_name_kh` FROM `ldc_measure` AS m,`ldc_product` AS p WHERE m.id=p.`unit` AND p.`id`=$id";
    	return $db->fetchRow($sql);
    }
    
    function getItemsPurchaseByQuoteId($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  q.id,
				  q.`quot_code`,
				  qc.`num_table`,
				  qc.`date_do`,
				  qc.`address`,
				  p.`pro_name_kh`,
				  qt.`item_id`,
				  qt.`qty`,
				  qt.`su_id`,
				  qt.`type`,
				  qt.`deliver_day`,
				  (SELECT m.id FROM `ldc_measure` AS m WHERE m.`id`=qt.`measure_id`) AS measure_id,
  				  (SELECT m.`measure_name_kh` FROM `ldc_measure` AS m WHERE m.`id`=qt.`measure_id`) AS measure_name_kh
				FROM
				  `ldc_quotation` AS q,
				  `ldc_quotation_connection` AS qc,
				  `ldc_quote_item` AS qt,
				  `ldc_product` AS p
				WHERE q.id = qc.`quote_id` 
				  AND qc.id = qt.`qc_id` 
				  AND qt.`item_id` = p.`id` 
				  AND q.`id` = $id 
				
				ORDER BY qc.`address`,
				  qt.`item_id`,
				  qc.`date_do`,
				  qt.`su_id` ,
  				  qt.`deliver_day`";
    	 
    	return $db->fetchAll($sql);
    }
    
    function getItemsPurchaseByQuoteIdShortBySu($id,$su_id){
    	$db = $this->getAdapter();
    	$sql = "SELECT
			    	q.id,
			    	q.`quot_code`,
			    	qc.`num_table`,
			    	qc.`date_do`,
			    	qc.`address`,
			    	f.`name_kh`,
			    	f.id as food_id,
			    	p.`pro_name_kh`,
			    	qt.`item_id`,
			    	qt.`qty`,
			    	qt.`su_id`,
			    	qt.`type`,
			    	qt.`deliver_day`,
			    	(SELECT m.id FROM `ldc_measure` AS m WHERE m.`id`=qt.`measure_id`) AS measure_id,
			    	(SELECT m.`measure_name_kh` FROM `ldc_measure` AS m WHERE m.`id`=qt.`measure_id`) AS measure_name_kh
			    FROM
			    	`ldc_quotation` AS q,
			    	`ldc_quotation_connection` AS qc,
			    	`ldc_quote_item` AS qt,
			    	`ldc_product` AS p,
			    	`ldc_food` AS f
			    WHERE q.id = qc.`quote_id`
			    	AND qc.id = qt.`qc_id`
			    	AND qt.`item_id` = p.`id`
			    	AND qt.`food_id`=f.`id`
			    	AND q.`id` = $id
			    ";
    
    	$where = '';
    	if($su_id>0){
    		$where.=" AND qt.`su_id`=$su_id";
    	}
    	
    	$order =" ORDER BY qc.`address`,
			    	qt.`item_id`,
			    	qc.`date_do`,
			    	qt.`su_id` ,
			    	qt.`deliver_day`";
    	//echo $sql.$where.$order;
    	return $db->fetchAll($sql.$where.$order);
    }
    function getQuoteById($id){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
				  q.`quot_code`,
				  c.`id` AS cu_id,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  cc.`id` AS ce_id,
				  cc.`address_1` AS address,
				  cc.`ceremony_date`
				FROM
				  `ldc_quotation` AS q,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc
				WHERE c.`id` = q.`customer_id`
				AND q.`ceremony_id`=cc.`id`
				  AND q.`id` =$id";
    	return ($db->fetchRow($sql));
    }
    
    function getPurchaseOrder($id){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
				  p.`pu_code`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  cc.`address`,
				  cc.`ceremony_date` 
				FROM
				  `ldc_purchase_order` AS p,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE p.`cu_id` = c.`id` 
				  AND p.`ce_id` = cc.`id` 
				  AND p.`id`=$id";
    	return ($db->fetchRow($sql));
    }
    
    function getPurchaseOrderDetail($id){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
				  po.*,
				  p.`pro_name_kh`,
				  m.`measure_name_kh`,
				  m.`id` AS measure_id 
				FROM
				  `ldc_purchase_order_item` AS po,
				  `ldc_measure` AS m,
				  `ldc_product` AS p 
				WHERE po.`pu_id` = $id 
				  AND po.`measure_id` = m.`id` 
				  AND p.`id` = po.`item_id` 
				ORDER BY po.`address_do`,
				  po.`item_id`,
				  po.`deliver_date`,
				  po.`su_id` 
    	";
    	return ($db->fetchAll($sql));
    }
    
    function getAllPurchase($search){
    	$db = $this->getAdapter();
    	$start_date = $search["start_date"];
    	$end_date = $search["end_date"];
    	$sql ="SELECT 
				  p.`id`,
    			  p.`pu_code`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  cc.`address_1`,
				  cc.`ceremony_date`,
				  p.`status`
				FROM
				  `ldc_purchase_order` AS p,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE p.`cu_id` = c.`id`
				  AND p.`ce_id` = cc.`id`
    			  AND cc.`ceremony_date` >= '$start_date' AND cc.`ceremony_date` <= '$end_date'
    			";

//     	$sql = "SELECT 
//     			  pc.`id`,
// 				  p.`pu_code` ,
// 				  c.`first_name`,
// 				  c.`phone`,
// 				  c.`email`,
// 				  cc.`address_1`,
// 				  cc.`ceremony_date`,
// 				  s.`company_name`,
//     			  p.`status`
// 				FROM
// 				  `ldc_purchase_order` AS p,
// 				  `ldc_purchase_connection` AS pc,
// 				  `ldc_purchase_order_item` AS poi ,
// 				  `ldc_supplier` AS s,
// 				  `ldc_customers` AS c,
// 				  `ldc_customer_ceremony` AS cc
// 				WHERE p.`id` = pc.`pu_id` 
// 				  AND pc.`id` = poi.`puc_id` 
// 				  AND poi.`su_id`=s.`id`
// 				  AND p.`cu_id`=c.`id`
// 				  AND p.`ce_id`=cc.`id`
//     			  AND cc.`ceremony_date` >= '$start_date' AND cc.`ceremony_date` <= '$end_date'
// 				  ";
    	$where='';
    	if($search['status_search']>-1){
    		$where.= " AND status = ".$search['status_search'];
    	}
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=$search['title'];
    		$s_where[]= " q.`quot_code` LIKE '%{$s_search}%'";
    		$s_where[]= " c.first_name LIKE '%{$s_search}%'";
    		$s_where[]= " c.phone LIKE '%{$s_search}%'";
    		$s_where[]= " c.email LIKE '%{$s_search}%'";
    		$s_where[]= " cc.ceremony_date LIKE '%{$s_search}%'";
    		$s_where[]= " cc.address LIKE '%{$s_search}%'";
    		//$s_where[]= " cate LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	$order = " ORDER BY id DESC";
    	//$group_by =" GROUP BY poi.`su_id`";
    	return $db->fetchAll($sql.$where.$order);
    }
    
    function getPurchaseDetail($id,$su_id){
    	$db = $this->getAdapter();
    	$sql ="SELECT 
    	          p.`pro_name_kh`,
				  f.`name_kh`,
				  f.`id` AS food_id,
				  m.`measure_name_kh`,
				  poi.* 
				FROM
				  `ldc_purchase_order_item` AS poi,
				  `ldc_product` AS p,
				  `ldc_food` AS f ,
				  `ldc_measure` AS m
				WHERE poi.`pu_id` = $id 
				  AND poi.`item_id` = p.`id` 
				  AND poi.`food_id` = f.id 
				  AND poi.`measure_id`= m.`id`
				";
    	$where = '';
    	if($su_id>0){
    		$where.=" AND poi.`su_id`=$su_id";
    	}
    	$order = " ORDER BY poi.`address_do` ,poi.`food_id`";
    	//echo $sql.$where.$order;
    	return $db->fetchAll($sql.$where.$order);
    }
    function getQuoteForPurchase($search){
    	$db = $this->getAdapter();
    	$start_date = $search["start_date"];
    	$end_date = $search["end_date"];
    	$sql ="SELECT 
				  q.id,
				  q.`quot_code`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  cc.`address_1`,
				  cc.`ceremony_date` ,
    			  q.`status`
				FROM
				  `ldc_quotation` AS q,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE c.`id` = q.`customer_id` 
				  AND q.`ceremony_id` = cc.`id` 
    			  AND cc.`ceremony_date` >= '$start_date' AND cc.`ceremony_date` <= '$end_date'
    			";
    	$where='';
    	if($search['status_search']>-1){
    		$where.= " AND status = ".$search['status_search'];
    	}
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=$search['title'];
    		$s_where[]= " q.`quot_code` LIKE '%{$s_search}%'";
    		$s_where[]= " c.first_name LIKE '%{$s_search}%'";
    		$s_where[]= " c.phone LIKE '%{$s_search}%'";
    		$s_where[]= " c.email LIKE '%{$s_search}%'";
    		$s_where[]= " cc.ceremony_date LIKE '%{$s_search}%'";
    		$s_where[]= " cc.address LIKE '%{$s_search}%'";
    		//$s_where[]= " cate LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ', $s_where).')';
    	}
    	$order = " ORDER BY id DESC";
    	//$group_by ="GROUP BY q.`quot_code`";
    	return $db->fetchAll($sql.$where.$order);
    }
    function addPurchase($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
    		
    		$arr = array(
    			'pu_code'	=>	$data["pu_code"],
    			'order_id'	=>	$data["id"],
    			'cu_id'		=>	$data["cu_id"],
    			'ce_id'		=>	$data["ceremony_id"],
    			'status'	=>	1
    		);
    		$this->_name="ldc_purchase_order";
    		$id = $this->insert($arr);
    		
    		$identity = $data["identity"];
    		$ids = explode(',', $identity);
    		foreach ($ids as $i){
    			$arr_p = array(
    				'pu_id'				=>	$id,
    				'su_id'				=>	$data["supplier_name_".$i],
    				'item_id'			=>	$data["item_name_".$i],
    				'qty'				=>	$data["item_qty_".$i],
    				'measure_id'		=>	$data["measure_id_".$i],
    				'address_do'		=>	$data["addr_".$i],
    				'deliver_address'	=>	$data["location_".$i],
    				'date_do'			=>	$data["date_do_".$i],
    				'deliver_date'		=>	$data["daliver_date_".$i],
    				'note'				=>	$data["note_".$i],
    			);
    			
    			$this->_name = "ldc_purchase_order_item";
    			$this->insert($arr_p);
    		}
    		
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();
    	}
    }
    
    function editPurchase($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
    
//     		$arr = array(
//     				'pu_code'	=>	$data["pu_code"],
//     				'order_id'	=>	$data["id"],
//     				'cu_id'		=>	$data["cu_id"],
//     				'ce_id'		=>	$data["ceremony_id"],
//     				'status'	=>	1
//     		);
//     		$this->_name="ldc_purchase_order";
//     		$where = $db->quoteInto("id=?", $data["id"]);
//     		$this->update($arr, $where);
    
    		$sql = "DELETE FROM ldc_purchase_order_item WHERE pu_id=".$data["id"];
    		$db->query($sql);
    		
    		$identity = $data["identity"];
    		$ids = explode(',', $identity);
    		foreach ($ids as $i){
    			$arr_p = array(
    					'pu_id'				=>	$data["id"],
    					'su_id'				=>	$data["supplier_name_".$i],
    					'item_id'			=>	$data["item_name_".$i],
    					'qty'				=>	$data["item_qty_".$i],
    					'measure_id'		=>	$data["measure_id_".$i],
    					'address_do'		=>	$data["addr_".$i],
    					'deliver_address'	=>	$data["location_".$i],
    					'date_do'			=>	$data["date_do_".$i],
    					'deliver_date'		=>	$data["daliver_date_".$i],
    					'note'				=>	$data["note_".$i],
    			);
    			 
    			$this->_name = "ldc_purchase_order_item";
    			$this->insert($arr_p);
    		}
    
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();
    	}
    }
    
    
    // New Purchase By SU and Not group item
    
    function addPurchaseBySuLong($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
    		$old_pu = $this->getPuCode($data["id"]);
    		if(empty($old_pu)){
	    		$arr = array(
	    				'pu_code'	=>	$data["pu_code"],
	    				'order_id'	=>	$data["id"],
	    				'cu_id'		=>	$data["cu_id"],
	    				'ce_id'		=>	$data["ceremony_id"],
	    				'status'	=>	1
	    		);
	    		$this->_name="ldc_purchase_order";
	    		$id = $this->insert($arr);
    		}else {
    			$id = $data["id"];
    		}
    		$arr_cc = array(
    			'pu_id'		=>	$id,
    			'su_id'		=>	$data["supplier_name"],
    			
    		);
    		$this->_name= "ldc_purchase_connection";
    		$pc_id = $this->insert($arr_cc);
    		
    		$identity = $data["identity"];
    		$ids = explode(',', $identity);
    		foreach ($ids as $i){
    			$arr_p = array(
    					'pu_id'				=>	$id,
    					'puc_id'			=>	$pc_id,
    					'su_id'				=>	$data["supplier_name_".$i],
    					'item_id'			=>	$data["item_name_".$i],
    					'food_id'			=>	$data["food_name_".$i],
    					'qty'				=>	$data["item_qty_".$i],
    					'measure_id'		=>	$data["measure_id_".$i],
    					'address_do'		=>	$data["addr_".$i],
    					'deliver_address'	=>	$data["location_".$i],
    					'date_do'			=>	$data["date_do_".$i],
    					'deliver_date'		=>	$data["daliver_date_".$i],
    					'note'				=>	$data["note_".$i],
    			);
    			 
    			$this->_name = "ldc_purchase_order_item";
    			$this->insert($arr_p);
    		}
    
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();
    	}
    }
    
    function updatePurchaseBySuLong($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try {
// //     		$old_pu = $this->getPuCode($data["id"]);
// //     		if(empty($old_pu)){
//     			$arr = array(
//     					'pu_code'	=>	$data["pu_code"],
//     					'order_id'	=>	$data["id"],
//     					'cu_id'		=>	$data["cu_id"],
//     					'ce_id'		=>	$data["ceremony_id"],
//     					'status'	=>	1
//     			);
//     			$this->_name="ldc_purchase_order";
//     			$where = $db->quoteInto("id=?", $data["id"]);
//     			$this->update($arr, $where);
//     		}else {
//     			$id = $data["id"];
//     		}
			if($data["supplier_name"] > -1){
				$sql = "DELETE FROM ldc_purchase_connection WHERE pu_id=".$data["id"]." AND su_id=".$data["supplier_name"];
				$db->query($sql);
					
				$sqls = "DELETE FROM ldc_purchase_order_item WHERE pu_id=".$data["id"]." AND su_id=".$data["supplier_name"];
				$db->query($sqls);
			}else {
				$sql = "DELETE FROM ldc_purchase_connection WHERE pu_id=".$data["id"];
				$db->query($sql);
					
				$sqls = "DELETE FROM ldc_purchase_order_item WHERE pu_id=".$data["id"];
				$db->query($sqls);
			}
			
    		$arr_cc = array(
    				'pu_id'		=>	$data["id"],
    				'su_id'		=>	$data["supplier_name"],
    				 
    		);
    		$this->_name= "ldc_purchase_connection";
    		$pc_id = $this->insert($arr_cc);
    
    		$identity = $data["identity"];
    		$ids = explode(',', $identity);
    		foreach ($ids as $i){
    			$arr_p = array(
    					'pu_id'				=>	$data["id"],
    					'puc_id'			=>	$pc_id,
    					'su_id'				=>	$data["supplier_name_".$i],
    					'item_id'			=>	$data["item_name_".$i],
    					'food_id'			=>	$data["food_name_".$i],
    					'qty'				=>	$data["item_qty_".$i],
    					'measure_id'		=>	$data["measure_id_".$i],
    					'address_do'		=>	$data["addr_".$i],
    					'deliver_address'	=>	$data["location_".$i],
    					'date_do'			=>	$data["date_do_".$i],
    					'deliver_date'		=>	$data["daliver_date_".$i],
    					'note'				=>	$data["note_".$i],
    			);
    
    			$this->_name = "ldc_purchase_order_item";
    			$this->insert($arr_p);
    		}
    
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();
    	}
    }
    
    function getPurchaseDetailById($id,$su_id){
    	$db = $this->getAdapter();
    	$sql ="SELECT
		    	p.`pu_code`,
		    	i.`pro_name_kh`,
		    	po.`item_id`,
		    	po.`su_id`,
		    	po.`qty`,
		    	po.`address_do`,
		    	po.`date_do`,
		    	po.`deliver_address`,
		    	po.`deliver_date`,
		    	po.`measure_id`,
		    	po.`note`,
		    	m.`measure_name_kh`,
		    	(SELECT m.`measure_name_kh` FROM `ldc_measure`AS m WHERE m.`id`=po.`measure_id`) AS measure_name ,
		    	(SELECT s.`company_name` FROM `ldc_supplier` AS s WHERE s.`id`=po.`su_id`) AS supplier_name
		    FROM
		    	`ldc_purchase_order` AS p,
		    	`ldc_purchase_order_item` AS po ,
		    	`ldc_product` AS i,
		    	`ldc_measure` AS m
		    WHERE p.`id` = po.`pu_id`
		    	AND po.`item_id`=i.`id`
		    	AND po.`measure_id`=m.`id`
		    	AND p.`id` = $id";
    	$where = '';
    	if($su_id >-1){
    	$where .= " AND po.`su_id`=".$su_id;
    }
    
    $order = " ORDER BY po.`su_id`,po.`deliver_date`,po.`deliver_address`";
    echo $sql.$where.$order;
    		return $db->fetchAll($sql.$where.$order);
	}
	
	function getFoodByItem($id){
		$db = $this->getAdapter();
		$sql ="SELECT 
				  f.`id`,
				  f.`name_kh` as name 
				FROM
				  `ldc_food` AS f,
				  `ldc_food_ingredients` AS fd 
				WHERE f.id = fd.`food_id` 
				  AND fd.`item_id` = $id";
		
		return $db->fetchAll($sql);
	}
}  
	  

