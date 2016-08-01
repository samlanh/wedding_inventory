<?php
class Report_Model_DbTable_DbFoodGategory extends Zend_Db_Table_Abstract
{
	
	function getGategory(){
		$db = $this->getAdapter();
		$sql ="SELECT id,name_en,name_kh FROM ldc_food_cat WHERE `status`=1";
		return $db->fetchAll($sql);
	}
	function getFoodByCategoryId($cat_id){
		$db=$this->getAdapter();
		$sql="SELECT id,food_code,cat_id,name_en,name_kh,price,img,`status`,`date` FROM ldc_food WHERE cat_id=".$cat_id;
		return $db->fetchAll($sql);
	}
	function getItermByFoodId($id){
		$db=$this->getAdapter();
		$sql="SELECT fs.id,(SELECT name_kh FROM ldc_food_cat WHERE ldc_food_cat.id=fs.food_id) AS food_id,ldc_product.pro_no,(SELECT pro_name_kh FROM ldc_product WHERE ldc_product.id=fs.item_id) AS item_id,
		       fs.qty,fs.amount,fs.price,fs.su_id,fs.deliver_day,fs.status FROM ldc_food_ingredients fs,ldc_product 
		       WHERE fs.item_id=ldc_product.id AND  fs.food_id=".$id;
		return $db->fetchAll($sql);
	}
	

 }

