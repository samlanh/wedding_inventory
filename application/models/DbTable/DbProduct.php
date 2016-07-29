<?php

class Application_Model_DbTable_DbProduct extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_product';
    
    /**
     * Get current rate 
     * @return array(6);
     */
    function getSearchProduc($cate_search,$txtsearch){
		
    	$db = $this->getAdapter();
    	$sql="SELECT p.id,p.pro_no,p.pro_name,p.price,p.bar_code,p.category_id,(SELECT a.`measure_name` FROM `ldc_measure` AS a WHERE a.`id`=p.`unit` LIMIT 1) AS unit ,p.img_front,p.description FROM ldc_product AS p  WHERE p.`status` =1 ";
    	$where ="";
    	if(@$cate_search>-1){
    		$where.= " AND `category_id`= ".$cate_search;
    	}
    	if(@!empty($txtsearch)){
    		$s_where=array();
    		$s_search=addslashes(trim($txtsearch));
    		$s_where[]=" pro_name LIKE '%{$s_search}%'";
    		$s_where[]=" pro_no LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ',$s_where).')';
			
    	}
    	$order = " ORDER BY id DESC ";
    	return $db->fetchAll($sql.$where.$order);
    }
	function getHotSaleProductByCatID($cate_id){
    	$db= $this->getAdapter();
    	$sql="SELECT p.`id`, (SELECT c.title FROM ldc_make AS c WHERE c.id = p.`category_id` LIMIT 1) AS cate_name,p.`pro_no`,p.`pro_name`,p.`bar_code`,p.`img_front`,p.`bar_code`,p.`price`,p.description,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= unit Limit 1)AS unit FROM ldc_product AS p WHERE p.`category_id`=".$cate_id." ORDER BY p.hot_sale DESC LIMIT 10 ";
    	return $db->fetchAll($sql);
    }
 	function getAllproduc(){
 		$db = $this->getAdapter();
 		$sql="SELECT id,pro_no,pro_name,price,bar_code,category_id,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= unit Limit 1)AS unit,img_front,description FROM ldc_product  WHERE STATUS =1 ORDER BY id DESC LIMIT 10";
 		return $db->fetchAll($sql);
 	}
    function getAllCate(){
    	$db = $this->getAdapter();
    	$sql="SELECT p.id, p.`title` AS `name`,p.img_feature FROM ldc_make AS p WHERE p.`status`=1 LIMIT 6";
    	return $db->fetchAll($sql);
    }
    function getAllCateMenu(){
    	$db = $this->getAdapter();
    	$sql="SELECT p.id, p.`title` AS `name`,p.img_feature FROM ldc_make AS p WHERE p.`status`=1";
    	return $db->fetchAll($sql);
    }
    function getCateById($id){
    	$db = $this->getAdapter();
    	$sql="SELECT p.id, p.`title` AS `name` FROM ldc_make AS p WHERE p.`status`=1 AND p.id=".$id;
    	return $db->fetchRow($sql);
    }
  	function getAllProByID($id){
  		$db = $this->getAdapter();
  		$sql="SELECT p.`id`, (SELECT c.title FROM ldc_make AS c WHERE c.id = p.`category_id` LIMIT 1) AS cate_name,p.`pro_no`,p.`pro_name`,p.`bar_code`,p.`img_front`,p.`bar_code`,p.`price`,p.description,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= p.unit LIMIT 1)AS unit FROM ldc_product AS p WHERE p.`category_id`=".$id." ORDER BY p.id DESC ";
  		return $db->fetchAll($sql);
  	}
  	function getNewProductByCate($id){
  		$db = $this->getAdapter();
  		$sql="SELECT p.`id`, (SELECT c.title FROM ldc_make AS c WHERE c.id = p.`category_id` LIMIT 1) AS cate_name,p.`pro_no`,p.`pro_name`,p.`bar_code`,p.`img_front`,p.`bar_code`,p.`price`,p.description,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= p.unit LIMIT 1)AS unit FROM ldc_product AS p WHERE p.`category_id`=".$id." ORDER BY p.id DESC LIMIT 4 ";
  		return $db->fetchAll($sql);
  	}
 	function getProductDetail($id){
 		$db = $this->getAdapter();
 		$sql="SELECT p.`id`,p.`category_id`, (SELECT c.title FROM ldc_make AS c WHERE c.id = p.`category_id` LIMIT 1) AS cate_name,p.`pro_no`,p.`pro_name`,p.`bar_code`,p.`img_front`,p.`bar_code`,p.`price`,p.description,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= p.unit LIMIT 1)AS unit FROM ldc_product AS p WHERE p.`id`=$id";
 		
 		return $db->fetchRow($sql);
 	}
 	function getProductByCode($id){
 		$db = $this->getAdapter();
 		$sql="SELECT p.`id`,p.`category_id`, (SELECT c.title FROM ldc_make AS c WHERE c.id = p.`category_id` LIMIT 1) AS cate_name,p.`pro_no`,p.`pro_name`,p.`bar_code`,p.`img_front`,p.`bar_code`,p.`price`,p.description,(SELECT c.measure_name FROM `ldc_measure` AS c WHERE c.id= p.unit LIMIT 1)AS unit FROM ldc_product AS p WHERE p.`pro_no`="."'$id'";
 		return $db->fetchRow($sql);
 	}
function addCardById($data){
 	if(!empty($data["action"])) {
 			switch($data["action"]) {
 				case "add":
		 		$product_session = new Zend_Session_Namespace('product_add_cart');
		 		$product_session->cart_item;
		 		
		 		$session = $product_session->cart_item;
		 		
		 		 
		 		
		 		$rowproduct = $this->getProductByCode($data['id']);
		        if(!empty($data["quantity"])) {
					//$rowproduct = $this->getProductDetail($data['id']);
					//print_r($rowproduct);exit();
					$itemArray = array($rowproduct["pro_no"]=>array('name'=>$rowproduct["pro_name"],'id'=>$rowproduct["id"], 'code'=>$rowproduct["pro_no"], 'quantity'=>$data["quantity"],'description'=>$rowproduct["description"], 'price'=>$rowproduct["price"],'img_front'=>$rowproduct['img_front'],'unit'=>$rowproduct['unit']));
					if(!empty($product_session->cart_item)) {
						$array = array_map(
					create_function('$sessions', 'return $sessions["code"];'),
					$session
				);
						if(in_array($rowproduct["pro_no"],$array)) {
							foreach($product_session->cart_item as $k => $v) {
								
								if($data['id'] == $k){
									$product_session->cart_item[$k]["quantity"] = $product_session->cart_item[$k]["quantity"]+$data["quantity"];
								}
							}
						} else {
							$product_session->cart_item = array_merge($product_session->cart_item,$itemArray);
						}
					} else {
						$product_session->cart_item = $itemArray;
					}
				}
				return $product_session->cart_item;
 			
		 	break;
 	case "remove":
 		$product_session = new Zend_Session_Namespace('product_add_cart');
 		if(!empty($product_session->cart_item)) {
 			foreach($product_session->cart_item as $k => $v) {
 				if($data["id"] == $v["id"])
 					unset($product_session->cart_item[$k]);
 				if(empty($product_session->cart_item))
 					unset($product_session->cart_item);
 			}
 		}
 		if(!empty($product_session->cart_item)) {
 			$product_session->cart_item = array_merge($product_session->cart_item);
 		}
 		return $product_session->cart_item;
 		break;
 	}
 }
}


function updateCart($data){
	if(!empty($data["action"])) {
		switch($data["action"]) {
			case "update":
				$product_session = new Zend_Session_Namespace('product_add_cart');
				$product_session->cart_item;
				$session = $product_session->cart_item;
				if(!empty($data["quantity"])) {
					if(!empty($product_session->cart_item)) {
						foreach($product_session->cart_item as $k => $v) {
							if($data['id'] == $k){
								$product_session->cart_item[$k]["quantity"] = $data["quantity"];
							}
						}
					} 
				}
				return $product_session->cart_item;
		 	break;
			case "remove":
				$product_session = new Zend_Session_Namespace('product_add_cart');
				if(!empty($product_session->cart_item)) {
					foreach($product_session->cart_item as $k => $v) {
						if($data["id"] == $v["id"])
							unset($product_session->cart_item[$k]);
						if(empty($product_session->cart_item))
							unset($product_session->cart_item);
					}
				}
				if(!empty($product_session->cart_item)){
					$product_session->cart_item = array_merge($product_session->cart_item);
				}
				return $product_session->cart_item;
				break;
		}
	}
}
}