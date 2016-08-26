<?php
class Report_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{
	function getAllOrder(){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  o.id,
				  o.`order_code`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  cc.`address_1`,
				  cc.`ceremony_date` ,
 				  o.`total_pay`
				FROM
				  ldc_order AS o,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE o.`customer_id` = c.`id` 
				  AND o.`ceremony_id` = cc.id ";
	
		return $db->fetchAll($sql);
	}
	
	function getOrderById($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  oc.`title`,
				  oc.`num_table`,
				  oc.`type`,
				  oc.`allocate_num`,
				  oc.`address`,
				  oc.`date_do`,
				  f.`name_kh`,
				  od.`note` ,
  				  l.`name` as l_name
				FROM
				  `ldc_order_connection` AS oc,
				  `ldc_order_detail` AS od,
				  `ldc_food` AS f ,
				  `ldc_label` AS l
				WHERE oc.id = od.`oc_id` 
				  AND od.`food_id` = f.`id` 
				  AND oc.`order_id` = $id 
				  AND oc.`label`=l.id
				 ORDER BY oc.`type`,oc.`address`,oc.`date_do`";
		return $db->fetchAll($sql);
	}
	function getCustomerOrderById($id){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  o.`order_code`,
				  c.`first_name`,
				  c.`phone`,
				  c.`email`,
				  C.`address`,
				  cc.`address_1`,
				  cc.`ceremony_date` 
				FROM
				  `ldc_order` AS o,
				  `ldc_customers` AS c,
				  `ldc_customer_ceremony` AS cc 
				WHERE o.`customer_id` = c.id 
				  AND o.`ceremony_id` = cc.id 
				  AND o.id=$id";
		return $db->fetchRow($sql);
	}
	
	function getOrderConnect($id,$type){
	$db = $this->getAdapter();
	$sql ="SELECT qc.* FROM `ldc_quotation_connection` AS qc WHERE qc.`Order_id`=$id AND qc.`type`=$type";
	return $db->fetchAll($sql);
		}
		function getOrderConnects($id){
				$db = $this->getAdapter();
				$sql ="SELECT qc.*,l.`name` AS l_name FROM `ldc_quotation_connection` AS qc,`ldc_label` AS l WHERE qc.`label`=l.`id` AND qc.`Order_id`=$id";
				return $db->fetchAll($sql);
		}
	
		function getOrderDetail($id){
		$db = $this->getAdapter();
		$sql ="SELECT f.`name_kh` FROM `ldc_quotation_detail` AS qd,`ldc_food` AS f WHERE qd.`food_id`=f.`id` AND qd.`qc_id`=$id";
		return $db->fetchAll($sql);
		}
	
		function getOrderByMergeId($id){
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
	
		function getOrderShortForm($id){
		$db = $this->getAdapter();
		$sql = "SELECT
		q.`quot_code`,
		qd.`address`,
		qd.`date_do`,
		qd.`num_table`,
		qd.`price`,
		qd.`total_pay` ,
		qd.`type`
		FROM
		`ldc_quotation` AS q,
		`ldc_quotation_connection` AS qd
		WHERE q.id = qd.`Order_id`
		AND q.id=$id";
		return $db->fetchAll($sql);
		}
 }

