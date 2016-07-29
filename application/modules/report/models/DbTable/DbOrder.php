<?php
class Report_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{
	function getCusOrder($search){
		$db= $this->getAdapter();
		$date = date_create($search["from_date"]);
		$from_date = date_format($date, "Y-m-d");
		//print_r($from_date);
	
		$todate = date_create($search["end_date"]);
		$to_date = date_format($todate, "Y-m-d");
		$where = " AND p.order_date BETWEEN '$from_date' AND '$to_date'";
		//$where="";
		$sql= 'SELECT p.`id`,p.`invoice_no`,(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = a.`title` LIMIT 1)," ", a.`first_name`," ",a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `name`,p.`order_date`,(SELECT (SELECT d.title FROM `ldc_partner` AS d WHERE d.id = a.company_id LIMIT 1) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `company`,(SELECT a.branch_name FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `branch`,p.`grand_total`,p.`book`,p.`balance_due`,p.`return`,(SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=11 AND v.key_code =p.`pending`) AS pending  FROM `ldc_order` AS p WHERE p.`status`=1 ';
		$order =" ORDER BY p.id DESC";
		if(empty($search)){
			return $db->fetchAll($sql.$where.$order);
			 
		}
		if ($search['company']>-1){
			$where .=' AND (SELECT a.company_id FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) = '.$search['company'];
		}
		if ($search['pending']>-1){
			$where .=' AND p.`pending` = '.$search['pending'];
		}
		if(!empty($search['search'])){
			$s_where = array();
			$s_search = addslashes(trim($search['search']));
			$s_where[] = "(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = a.`title` LIMIT 1),' ',a.`first_name`,' ',a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) LIKE '%$s_search%'";
			$s_where[] = "(SELECT a.branch_name FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) LIKE '%$s_search%'";
			$s_where[] = "p.`invoice_no` LIKE '%$s_search%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		//echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);
	}
	public function getOrderReport($search){
     	$db = $this->getAdapter();
     	
     	$date = date_create($search["from_date"]);
    	$from_date = date_format($date, "Y-m-d");
    	 
    	$todate = date_create($search["end_date"]);
    	$to_date = date_format($todate, "Y-m-d");
    	$where = " AND c.order_date BETWEEN '$from_date' AND '$to_date'";
     	$sql='SELECT c.`id`,c.`order_date`,c.`cus_id`,(SELECT CONCAT(a.first_name," ",a.last_name) FROM ldc_customers AS a WHERE a.id = c.`cus_id`) AS `name` ,c.`invoice_no`,c.`grand_total`,c.`balance_due` FROM `ldc_order`AS c WHERE 1';
     	$order =' ORDER BY c.id DESC';
     	if(empty($search)){
     		return $db->fetchAll($sql.$where.$order);
     		 
     	}
     	if(!empty($search['search'])){
     		$s_where = array();
     		$s_search = trim($search['search']);
     		$s_where[] = " (SELECT CONCAT(a.`first_name`,' ',a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = c.`cus_id`) LIKE '%{$s_search}%'";
     		$s_where[] = " c.`invoice_no` LIKE '%{$s_search}%'";
     		$where .=' AND ( '.implode(' OR ',$s_where).')';
     	}
     	return $db->fetchAll($sql.$where.$order);
     }
     public function getOrderBranch($search){
     	$db = $this->getAdapter();
     
     	$date = date_create($search["from_date"]);
     	$from_date = date_format($date, "Y-m-d");
     
     	$todate = date_create($search["end_date"]);
     	$to_date = date_format($todate, "Y-m-d");
     	$where = " AND c.order_date BETWEEN '$from_date' AND '$to_date'";
     	$sql='SELECT c.`id`,c.`order_date`,c.`cus_id`,(SELECT CONCAT(a.first_name," ",a.last_name) FROM ldc_customers AS a WHERE a.id = c.`cus_id`) AS `name` ,c.`invoice_no`,c.`grand_total`,c.`balance_due` FROM `ldc_order`AS c WHERE c.`cus_type`=2';
     	$order =' ORDER BY c.id DESC';
     	if(empty($search)){
     		return $db->fetchAll($sql.$where.$order);
     
     	}
     	if(!empty($search['search'])){
     		$s_where = array();
     		$s_search = trim($search['search']);
     		$s_where[] = " (SELECT CONCAT(a.`first_name`,' ',a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = c.`cus_id`) LIKE '%{$s_search}%'";
     		$s_where[] = " c.`invoice_no` LIKE '%{$s_search}%'";
     		$where .=' AND ( '.implode(' OR ',$s_where).')';
     	}
     	return $db->fetchAll($sql.$where.$order);
     }
     public function getTotalOrder($search){
     	$db = $this->getAdapter();
     	 
     	$date = date_create($search["from_date"]);
     	$from_date = date_format($date, "Y-m-d");
     	 
     	$todate = date_create($search["end_date"]);
     	$to_date = date_format($todate, "Y-m-d");
     	$where = " AND c.order_date BETWEEN '$from_date' AND '$to_date'";
     	$sql='SELECT c.`id`,c.`order_date`,c.`cus_id`,(SELECT CONCAT(a.first_name," ",a.last_name) FROM ldc_customers AS a WHERE a.id = c.`cus_id`) AS `name` ,c.`invoice_no`,c.`grand_total`,c.`balance_due` FROM `ldc_order`AS c WHERE 1 ';
     	$order =' ORDER BY c.id DESC';
     	if(empty($search)){
     		return $db->fetchAll($sql.$where.$order);
     		 
     	}
     	if(!empty($search['search'])){
     		$s_where = array();
     		$s_search = trim($search['search']);
     		$s_where[] = " (SELECT CONCAT(a.`first_name`,' ',a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = c.`cus_id`) LIKE '%{$s_search}%'";
     		$s_where[] = " c.`invoice_no` LIKE '%{$s_search}%'";
     		$where .=' AND ( '.implode(' OR ',$s_where).')';
     	}
     	return $db->fetchAll($sql.$where.$order);
     }
     function getParameter($title){
     	$db= $this->getAdapter();
     	$sql = 'SELECT p.`id`,p.`title`,p.`value` FROM `ldc_paramater` AS p WHERE p.`status`=1 AND p.`title`='."'$title'";
     	return $db->fetchRow($sql);
     }
     function getOrderDetail($id){
     	$db= $this->getAdapter();
     	$sql = 'SELECT p.id,(SELECT a.invoice_no FROM `ldc_order` AS a WHERE a.id = p.`order_id`) AS invoice,(SELECT b.pro_no FROM `ldc_product` AS b WHERE b.id = p.`pro_id`) AS pro_no,(SELECT b.pro_name FROM `ldc_product` AS b WHERE b.id = p.`pro_id`) AS pro_name,(SELECT (SELECT u.measure_name FROM `ldc_measure` AS u WHERE u.id = b.unit )FROM `ldc_product` AS b WHERE b.id = p.`pro_id`) AS unit,p.`price`,p.`qty`,p.`amount` FROM `ldc_order_detail` AS p WHERE p.`order_id`='.$id;
     	return $db->fetchAll($sql);
     }
     function getOrder($id){
     	$db= $this->getAdapter();
     	$sql='SELECT p.`id`,p.`invoice_no`,p.`discount_login`,(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = a.`title` LIMIT 1)," ", a.`first_name`," ",a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `name`,(SELECT a.house_num FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `house_no`,(SELECT a.street FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `street`,(SELECT (SELECT d.district_name FROM ldc_district AS d WHERE d.id =a.district LIMIT 1) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `district`,(SELECT (SELECT k.province_name FROM `ldc_province` AS k WHERE k.id = a.province_id LIMIT 1) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `province`,p.`order_date`,(SELECT (SELECT d.title FROM `ldc_partner` AS d WHERE d.id = a.company_id LIMIT 1) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `company`,(SELECT a.branch_name FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `branch`,p.`grand_total`,p.`book`,p.`balance_due`,p.`return`,(SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=11 AND v.key_code =p.`pending`) AS pending  FROM `ldc_order` AS p WHERE p.`id`='.$id;
     	return $db->fetchRow($sql);
     }
     public function getAllPartner(){
     	$db = $this->getAdapter();
     	$sql="SELECT p.`id`,p.`title` FROM `ldc_partner` AS p WHERE p.`status`=1";
     	return $db->fetchAll($sql);
     }
 }

