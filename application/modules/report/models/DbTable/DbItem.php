<?php
class Report_Model_DbTable_DbItem extends Zend_Db_Table_Abstract
{
	 private $table="ldc_product";
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
	function getItemCatagory(){
		$db=$this->getAdapter();
		$sql=" SELECT * FROM  ldc_item_cat WHERE `status`=1";
		return $db->fetchAll($sql);
	}
	function getItemAll(){
		$db=$this->getAdapter();
		$sql="SELECT * FROM $this->table WHERE `status`=1";
		return $db->fetchAll($sql);
	}
	function getMeasureAll(){
		$db=$this->getAdapter();
		$sql=" SELECT * FROM ldc_measure WHERE `status`=1";
		return $db->fetchAll($sql);
	}
 }

