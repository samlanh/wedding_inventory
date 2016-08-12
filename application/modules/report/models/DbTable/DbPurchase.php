<?php
class Report_Model_DbTable_DbPurchase extends Zend_Db_Table_Abstract
{
	function getPurchase(){
		$db = $this->getAdapter();
		$sql = "SELECT 
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
				  AND p.`ce_id` = cc.`id` ";
		
		return $db->fetchAll($sql);
	}
	
	function getPurchaseById($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  q.`id`,
				  q.`quot_code`,
				  q.`total_pay`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  c.`address` 
				FROM
				  `ldc_quotation` AS q,
				  `ldc_customers` AS c 
				WHERE q.`customer_id` = c.id 
				  AND q.id = $id";
		return $db->fetchRow($sql);
	}
	
	function getPurchaseConnect($id,$type){
		$db = $this->getAdapter();
		$sql ="SELECT qc.* FROM `ldc_quotation_connection` AS qc WHERE qc.`quote_id`=$id AND qc.`type`=$type";
		return $db->fetchAll($sql);
	}
	function getPurchaseConnects($id){
		$db = $this->getAdapter();
		$sql ="SELECT qc.* FROM `ldc_quotation_connection` AS qc WHERE qc.`quote_id`=$id";
		return $db->fetchAll($sql);
	}
	
	function getPurchaseDetail($id){
		$db = $this->getAdapter();
		$sql ="SELECT f.`name_kh` FROM `ldc_quotation_detail` AS qd,`ldc_food` AS f WHERE qd.`food_id`=f.`id` AND qd.`qc_id`=$id";
		return $db->fetchAll($sql);
	}
	
	function getPurchaseByMergeId($id){
		$db = $this->getAdapter();
		$sql ="SELECT 
				  q.`id`,
				  q.`quot_code`,
				  q.`merge_id`,
				  q.`total_pay`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  c.`address` 
				FROM
				  `ldc_quotation` AS q,
				  `ldc_customers` AS c 
				WHERE q.`customer_id` = c.id 
				  AND q.`merge_id`=$id";
		return $db->fetchAll($sql);
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
		
		$order = " ORDER BY po.`su_id`,po.`deliver_date`,po.`deliver_address`,po.`item_id`";
		//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);
	}
 }

