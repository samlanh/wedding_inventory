<?php
class Report_Model_DbTable_DbFoodGategory extends Zend_Db_Table_Abstract
{
	
	function getGategory($search=null){
		$db = $this->getAdapter();
		$sql ="SELECT fc.id,fc.name_en,fc.name_kh,fc.status FROM ldc_food_cat AS fc,ldc_food WHERE ldc_food.cat_id=fc.id AND fc.status=1";
		$where="";
		if($search['adv_search']){
			$s_where=array();
			$s_search=addslashes(trim($search['adv_search']));
			$s_where[]= " fc.name_kh LIKE '%{$s_search}%'";
			$s_where[]= " fc.name_en LIKE '%{$s_search}%'";
			$s_where[]= " ldc_food.name_kh LIKE '%{$s_search}%'";
			$s_where[]= " ldc_food.name_en LIKE '%{$s_search}%'";
			$s_where[]= " ldc_food.food_code LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		if ($search['cat_food']){
			$where.=" AND fc.id=".$search['cat_food'];
		}
		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	}
	function getFoodByCategoryId($cat_id){
		$db=$this->getAdapter();
		$sql="SELECT id,food_code,cat_id,name_en,name_kh,price,img,`status`,`date` FROM ldc_food WHERE cat_id=".$cat_id;
		return $db->fetchAll($sql);
	}
	function getItermByFoodId($id){
		$db=$this->getAdapter();
		$sql="SELECT fs.id,(SELECT measure_name_kh FROM ldc_measure WHERE ldc_measure.id=ldc_product.unit ) AS measur_names,
             (SELECT name_kh FROM ldc_food_cat WHERE ldc_food_cat.id=fs.food_id) AS food_id,ldc_product.pro_no,
             (SELECT pro_name_kh FROM ldc_product WHERE ldc_product.id=fs.item_id) AS item_id,
	    	 fs.qty,fs.amount,fs.price,fs.su_id,fs.deliver_day,fs.status FROM ldc_food_ingredients fs,ldc_product 
             WHERE fs.item_id=ldc_product.id AND  fs.food_id=".$id;
		return $db->fetchAll($sql);
	}
	function getCategoryFood(){
		$db=$this->getAdapter();
		$sql="SELECT id,name_kh FROM ldc_food_cat WHERE `status`=1";
		$order=" ORDER BY id DESC";
		return $db->fetchAll($sql.$order);
	}
	

 }

