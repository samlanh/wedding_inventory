<?php
class Report_Model_DbTable_DbQuote extends Zend_Db_Table_Abstract
{
	function getQuote(){
		$db = $this->getAdapter();
		$sql = "SELECT 
				  q.id,
				  q.`quot_code`,
				  q.`merge_id`,
				  (SELECT c.`first_name` FROM `ldc_customers` AS c WHERE c.id=q.`customer_id`) AS customer_name,
				  q.`total_pay`,
				  qc.`date_do`,
				  qc.`address` 
				FROM
				  `ldc_quotation` AS q,
				  `ldc_quotation_connection` AS qc 
				WHERE q.`id` = qc.`quote_id` 
				   GROUP BY qc.`quote_id`";
		
		return $db->fetchAll($sql);
	}
	
	function getQuoteById($id){
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
	
	function getQuoteConnect($id,$type){
		$db = $this->getAdapter();
		$sql ="SELECT qc.* FROM `ldc_quotation_connection` AS qc WHERE qc.`quote_id`=$id AND qc.`type`=$type";
		return $db->fetchAll($sql);
	}
	function getQuoteConnects($id){
		$db = $this->getAdapter();
		$sql ="SELECT qc.* FROM `ldc_quotation_connection` AS qc WHERE qc.`quote_id`=$id";
		return $db->fetchAll($sql);
	}
	
	function getQuoteDetail($id){
		$db = $this->getAdapter();
		$sql ="SELECT f.`name_kh` FROM `ldc_quotation_detail` AS qd,`ldc_food` AS f WHERE qd.`food_id`=f.`id` AND qd.`qc_id`=$id";
		return $db->fetchAll($sql);
	}
	
	function getQuoteByMergeId($id){
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
 }

