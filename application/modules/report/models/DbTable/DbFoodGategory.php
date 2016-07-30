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
		$sql="SELECT fs.id,fs.food_id,fs.item_id,
		       fs.qty,fs.amount,fs.su_id,fs.deliver_day,fs.price,fs.status FROM ldc_food_ingredients fs,ldc_product 
		       WHERE fs.item_id=ldc_product.id AND  fs.food_id=$id";
		return $db->fetchAll($sql);
	}
	

 }

