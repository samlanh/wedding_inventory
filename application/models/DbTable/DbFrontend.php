<?php

class Application_Model_DbTable_DbFrontend extends Zend_Db_Table_Abstract
{
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function getGlobalDbRow($sql)
	{
		$db=$this->getAdapter();
		$row=$db->fetchRow($sql);
		if(!$row) return NULL;
		return $row;
	}
	public function getGlobalDb($sql)
	{
		$db=$this->getAdapter();
		$row=$db->fetchAll($sql);
		if(!$row) return NULL;
		return $row;
	}
	
  function getVehicleDiscount(){
  	$db = $this->getAdapter();
  	$sql = "SELECT 
			  v.`reffer`,
			  v.`frame_no`,
			  (SELECT title FROM `ldc_make` AS m WHERE m.id=v.`make_id`) AS make,
			  (SELECT title FROM `ldc_model` AS md WHERE v.`model_id`=md.id ) AS model,
			  (SELECT sm.title FROM `ldc_submodel` AS sm WHERE sm.id=v.`sub_model`) AS sub_model,
  		          v.`img_front_right`,discount,discount_fromdate,discount_todate,
  			  v.`img_seat`,
  			   v.`id`
			FROM
			  `ldc_vehicle` AS v
			WHERE
  			   v.`status`=1 AND v.is_promotion=1 AND is_sale!=1";
  	$order=' ORDER BY id DESC';
  	return $db->fetchAll($sql.$order);
  }
 
  function getAllWelcomenote(){
  	$db = $this->getAdapter();
  	$sql = " SELECT title,description FROM `ldc_manytable` WHERE type=1 ";
  	return $db->fetchRow($sql);
  }
  function getCityTurePackage($location_id){
  	$sql="SELECT id ,location_name ,note,is_package FROM `ldc_package_location` WHERE 
  	location_name!='' AND STATUS=1 AND service_type=1 AND province_id = $location_id ";
  	$order=' ORDER BY is_package DESC ,id DESC  ';
  	return $this->getAdapter()->fetchAll($sql.$order);
  }
function getLocatoinTurByPackageId($package_id){
  	$sql="SELECT l.* FROM `ldc_package_location` AS l,`ldc_package_detail` AS d WHERE 
d.location_id =l.id AND d.package_id=$package_id ";
  	$db = $this->getAdapter();
  	return $db->fetchAll($sql);
  }
  function getAllProvince(){
  	$sql="SELECT * FROM ldc_province WHERE province_name!='' ";
  	$order=' ORDER BY id DESC';
  	return $this->getAdapter()->fetchAll($sql.$order);
  }
//   function getCityTurePackage(){
//        $sql="SELECT 
//                ct.id,
//                ct.vehicle_id,
//                ct.package_id,
//                ct.price,
//                ct.one_hour,
//                ct.max_hour,
//                ct.`status`,
//                (SELECT reffer FROM ldc_vehicle WHERE id=ct.vehicle_id LIMIT 1) AS vehicle_reff,
//                (SELECT frame_no FROM ldc_vehicle WHERE id=ct.vehicle_id LIMIT 1) AS frame_no,
//                (SELECT CONCAT(title,'( ',VALUE,'%)') FROM ldc_tax WHERE VALUE=tax) AS tax,
//                (SELECT location_name FROM ldc_package_location WHERE id=ct.package_id ) AS package_name,
//                (SELECT note FROM ldc_package_location WHERE id=ct.package_id ) AS citourdetail
//                FROM ldc_vehicletaxitour AS ct WHERE ct.`status` = 1";
//     	$order=' ORDER BY ct.id DESC';
//     	return $this->getAdapter()->fetchAll($sql.$order);
//   }
  public static function getPicCityTourByLocation($location_id){
  	$db = new Application_Model_DbTable_DbFrontend();
  	$sql=" SELECT pic_title FROM `ldc_picture_location` WHERE location_id=$location_id AND STATUS=1 AND pic_title!='' ORDER BY id ASC LIMIT 1";
  	return $db->getGlobalDbRow($sql);
  }
  function getAllPhotoGalllery(){
  	$db = $this->getAdapter();
  	$sql="SELECT * FROM ldc_photo WHERE status=1 AND title !='' ";
  	return $db->fetchAll($sql);
  }
 
}
?>