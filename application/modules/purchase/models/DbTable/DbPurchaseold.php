<?php

class Purchase_Model_DbTable_DbPurchase extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_quotation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getPurchaseID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM ldc_purchase_order ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "PU-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
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
				  fi.`deliver_day` 
				FROM
				  `ldc_quotation` AS q,
				  `ldc_quotation_connection` AS qc,
				  `ldc_quote_item` AS qt,
				  `ldc_product` AS p,
				  `ldc_food_ingredients` AS fi 
				WHERE q.id = qc.`quote_id` 
				  AND qc.id = qt.`qc_id` 
				  AND qt.`item_id` = p.`id` 
				  AND q.`id` = $id 
				  AND qt.`food_id` = fi.`food_id` 
				GROUP BY qc.`address`,
				  qt.`item_id`,qt.`type`
				ORDER BY qc.`address`,
				  qt.`item_id`,
				  qc.`date_do`,
				  qt.`su_id` ASC ";
    	 
    	return $db->fetchAll($sql);
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
				  cc.`address`,
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
				  cc.`address`,
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
				  cc.`address`,
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
    				//'measure_id'		=>	$data["measure_id_".$i],
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
}  
	  

