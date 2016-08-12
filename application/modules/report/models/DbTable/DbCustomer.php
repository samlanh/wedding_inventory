<?php
class Report_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	function getGategory($search=null){
		$db = $this->getAdapter();
		$sql ="SELECT id,name_en,name_kh FROM ldc_food_cat ";
		$where=" where status=1 ";
		if($search['adv_search']){
			$s_where=array();
			$s_search=addslashes(trim($search['adv_search']));
			$s_where[]= " name_kh LIKE '%{$s_search}%'";
			$s_where[]= " name_en LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		if ($search['cat_food']){
			$where.=" AND id=".$search['cat_food'];
		}
		echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	 function getCustomerAll($search=null){
	 	$db=$this->getAdapter();
	 	$sql="SELECT c.id,c.customer_code,c.first_name,c.phone,c.email,c.address,
			      cc.ceremony_date,cc.address_1,cc.address_2,cc.address_3,cc.is_meeting,cc.status
			      FROM ldc_customers AS c,ldc_customer_ceremony AS cc 
			      WHERE c.id=cc.cu_id  AND cc.status=1";
	 	$where=" ";
	 	$from_date =(empty($search['start_date']))? '1': " cc.ceremony_date >= '".$search['start_date']." 00:00:00'";
	 	$to_date = (empty($search['end_date']))? '1': " cc.ceremony_date <= '".$search['end_date']." 23:59:59'";
	 	$where = " AND ".$from_date." AND ".$to_date;
	 	
	 	if(!empty($search['adv_search'])){
	 		$s_where=array();
	 		$s_search=addslashes(trim($search['adv_search']));
	 		$s_where[]= " c.customer_code LIKE '%{$s_search}%'";
	 		$s_where[]= " c.first_name LIKE '%{$s_search}%'";
	 		$s_where[]= " c.phone LIKE '%{$s_search}%'";
	 		$s_where[]= " c.email LIKE '%{$s_search}%'";
	 		$s_where[]= " c.address LIKE '%{$s_search}%'";
	 		$s_where[]= " cc.address LIKE '%{$s_search}%'";
	 		$where.=' AND ('.implode(' OR ', $s_where).')';
	 	}
	 	if(!empty($search['customer'])){
	 		$where.=" AND c.id=".$search['customer'];
	 	}
	 	$order=" ORDER By id DESC ";
	 	echo $sql.$where;
	 	return $db->fetchAll($sql.$where.$order);
	 }
	function getCustomer(){
		$db=$this->getAdapter();
		$sql=" SELECT id,customer_code,first_name AS `name` FROM ldc_customers WHERE `status`=1";
		return $db->fetchAll($sql);
	}
	function getSupplierRpt($search=null){
		$db=$this->getAdapter();
		$sql="SELECT p.id,p.`su_code`,p.`first_name`,
		    	p.`p_phone`,p.`p_email`,p.`company_name`,p.c_phone,p.c_email,p.note,p.date,
		      (SELECT name_en FROM `ldc_view` WHERE key_code = p.`status` AND `type`=2) AS `status` FROM `ldc_supplier` AS p WHERE 1";
		
		$where=" ";
		$from_date =(empty($search['start_date']))? '1': " p.date >= '".$search['start_date']." 00:00:00'";
		$to_date = (empty($search['end_date']))? '1': " p.date <= '".$search['end_date']." 23:59:59'";
		$where = " AND ".$from_date." AND ".$to_date;
		 
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search=addslashes(trim($search['adv_search']));
			$s_where[]= " p.first_name LIKE '%{$s_search}%'";
			$s_where[]= " p.su_code LIKE '%{$s_search}%'";
			$s_where[]= " p.p_phone LIKE '%{$s_search}%'";
			$s_where[]= " p.p_email LIKE '%{$s_search}%'";
			$s_where[]= " p.c_phone LIKE '%{$s_search}%'";
			$s_where[]= " p.company_name LIKE '%{$s_search}%'";
			$s_where[]= " p.c_email LIKE '%{$s_search}%'";
			$s_where[]= " p.date LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		if(!empty($search['supplier'])){
			$where.=" AND id=".$search['supplier'];
		}
		
		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	function getAllSupplier(){
		$db=$this->getAdapter();
		$sql=" SELECT id,first_name As name FROM ldc_supplier WHERE `status`=1";
		return $db->fetchAll($sql);
	}
	function getSupplierItem($id){
		$db=$this->getAdapter();
		$sql="SELECT id,pro_no,pro_name_en,pro_name_kh,price,category_id,
		        (SELECT first_name FROM ldc_supplier WHERE ldc_supplier.id=ldc_product.unit) AS  unit,
		       img_front,bar_code,`date`,`status`  
		       FROM ldc_product WHERE unit=$id AND `status`=1";
		return $db->fetchAll($sql);
	}

 }

