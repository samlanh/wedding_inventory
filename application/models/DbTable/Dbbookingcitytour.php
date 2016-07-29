<?php

class Application_Model_DbTable_Dbbookingcitytour extends Zend_Db_Table_Abstract
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
	function getPackageCityTourDetailById($package_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `ldc_package_location` WHERE id=$package_id AND service_type=1 ";
//echo $sql;
		return $db->fetchRow($sql);
	}
	function getPackagPhotoById($package_id){
		$db = $this->getAdapter();
		$sql = "SELECT pic_title FROM `ldc_picture_location` WHERE location_id = $package_id AND STATUS=1 ";
//echo $sql;exit();
		return $db->fetchAll($sql);
	}
	
	function getPackageCityTourById($package_id){
		$db = $this->getAdapter();
		$sql = "SELECT location_name FROM `ldc_package_location` WHERE id=$package_id AND service_type=1 ";
		return $db->fetchOne($sql);
	}
	function getPriceBYPackageandVehicle($vehicle_id,$package_id){
		$db = $this->getAdapter();
		$sql="SELECT price FROM `ldc_vehicletaxitour` WHERE vehicle_id=$vehicle_id AND package_id=$package_id LIMIT 1";
		return $db->fetchOne($sql);
	}
	function clearSessionBYStep($step){
		$session_step =new Zend_Session_Namespace('bookcitytour');
		if($step==1){
			
			$session_step->unsetAll();
// 			$session_step->pickup_date =null;
// 			$session_step->pickup_time = null;
// 			$session_step->pickup_mins = null;
// 			$session_step->step2 =0;
// 			$session_step->return_date = null;
// 			$session_step->return_time =null;
// 			$session_step->return_mins =null;
// 			$session_step->vehiclevaliable = null;
			
		}elseif($step==2){
			$session_step->step3 =0;
			$session_step->vehicle_id=null;//$data get parram store value only not array
			$session_step->price=0;
			$session_step->guideavaliable =null;
			$session_step->vehicle_name =null;
			
			$session_step->guide_id =null;
			$session_step->step4 =0;
			$session_step->guidename=null;
			$session_step->guideprice=null;
		}elseif($step==3){
			$session_step->guide_id =null;
			$session_step->step4 =0;
			$session_step->guidename=null;
			$session_step->guideprice=null;
		}
	}
	function createSessionBookingCityTour($data,$step=1){
		$this->clearSessionBYStep($step);
		$session_step =new Zend_Session_Namespace('bookcitytour');
		
		if($step==1){
			$session_step->package_id=$data;
			$session_step->package_name=$this->getPackageCityTourById($data);
			$session_step->max_hour=$this->getMaxHourByPackageId($data);//to show max hour in front
			$session_step->step1=1;
			$session_step->step2=0;
			$session_step->step3=0;
			$session_step->step4=0;
			$session_step->point_step=1;
		}
		elseif($step==2){
			$session_step->point_step=2;
			$data['return_date']=$data['pickup_date'];
			$session_step->pickup_date = empty($data['pickup_date'])? null : $data['pickup_date'];
			$session_step->pickup_time = empty($data['pickup_time'])? null : $data['pickup_time'];
			$session_step->pickup_mins = empty($data['pickup_mins'])? null : $data['pickup_mins'];
			$session_step->step2 =1;
			$session_step->return_date = empty($data['return_date'])? null : $data['return_date'];
			$session_step->return_time =empty($data['return_time'])? null : $data['return_time'];
			$session_step->return_mins =empty($data['return_mins'])? null : $data['return_mins'];
			$db = new Application_Model_DbTable_DbGlobal();
			$session_step->vehiclevaliable = $db->getAllAvailableVehicle($data);
		}elseif($step==3){
			$session_step->point_step=3;
			$session_step->step3 =1;
			$session_step->vehicle_id=$data;//$data get parram store value only not array
			$session_step->price=$this->getPriceBYPackageandVehicle($data,$session_step->package_id);
			$db = new Application_Model_DbTable_DbGlobal();
			$array= array(
					'pickup_date'=>$session_step->pickup_date,
					'return_date'=>$session_step->pickup_date,
					'return_time'=>$session_step->pickup_time.":".$session_step->pickup_mins,
			);
			$session_step->guideavaliable =$db->getAvailableDriver($array);
			$row = $db->geVehicleById($data);
			$session_step->vehicle_name =$row["make"]." ".$row["model"]." ".$row["sub_model"];
			$session_step->reff =$row["reffer"];
		}elseif($step==4){
			$session_step->point_step=4;
			$session_step->step4 =1;
			if(!empty($data)){//if select guide/
				$row = $this->getGuideNormalPrice($data);
				$guideprice=$row['c_normalprice'];
				$guidename=$row['first_name'].'-'.$row['last_name'];
				$guide_id = $row["id"];
				//$session_step->guide_id =$guide_id;
			}else{
				$guideprice=0;
				$guidename='None';
				$guide_id = 'None';
			}
			$session_step->guidename=$guidename;
			$session_step->guideprice=$guideprice;
			$session_step->guideid=$guide_id;
		}elseif($step==5){
			$session_step->point_step=5;
			$session_step->step5 =1;
		}elseif($step==6){
			$session_step->point_step=6;
			$session_step->step6 =1;
		}
		return true;
// 		if(!empty($session_step_one->step2)){
// 			$session_step_one->vehicle_id=$data['vehicle_id'];
// 		}
// 		$session_step_one->step2 = null;
// 		$session_step_one->step3 = null;
		
	}
	function getGuideNormalPrice($guide_id){
	    $db= $this->getAdapter();
		$sql =" SELECT id,first_name ,last_name,c_normalprice FROM `ldc_driver` WHERE id=$guide_id LIMIT 1 ";
		return $db->fetchRow($sql);
	}
	function getMaxHourByPackageId($package_id){
		$db= $this->getAdapter();
		$sql =" SELECT max_hour FROM `ldc_vehicletaxitour` WHERE package_id=$package_id LIMIT 1 ";
		return $db->fetchOne($sql);
	}
	
	function addCityTourBooking($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
		$session_user=new Zend_Session_Namespace('customer');
		$user_id = $session_user->customer_id;
		$session =new Zend_Session_Namespace('bookcitytour');
			
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$booking_code = $db_globle->getNewBookingCode();
		
		$vehicle_fee = $session->price;
		$guide_fee = $session->guideprice;
		
		
		$total_fee = $vehicle_fee+$guide_fee;
		
		//$deposit = $total_fee*(30/100);
		$pickup_date = new DateTime($session->pickup_date);
		//$return_date = new DateTime($data["return_date"]);
		
		$arr = array(
				'customer_id'		=>	$user_id,
				'booking_no'		=>	$booking_code,
				'package_id'		=>	$session->package_id,
// 				'pickup_location'	=>	$session->from_locationname,
// 				'dropoff_location'	=>	$session->to_locationname,
				'date_book'			=>	date('Y-m-d'),
				'pickup_date'		=>	$pickup_date->format("Y-m-d"),
				'pickup_time'		=>	$session->pickup_time.":".$session->pickup_mins,
				'total_fee'			=>	$total_fee,
				//'deposite_fee'		=>	$deposit,
				'total_paymented'	=>	$total_fee,
				'item_type'			=>	3,
		);
			
		if($data["payment_type"]==1){
			$arr['visa_name']=$data["card_name"];
			$arr['card_id']=$data["card_id"];
			$arr['secu_code']=$data["secu_code"];
			$arr['card_exp_date']=$data["card_exp_date"];
			$arr['card_id']=$data["card_id"];
			$arr['payment_type']=1;
			$arr['status']=1;
		
		}elseif($data["payment_type"]==2){
			$arr['card_id']=$data["code_num"];
			$arr['payment_type']=2;
			$arr['status']=2;
		}elseif($data["payment_type"]==3){
			$arr['payment_type']=3;
			$arr['status']=2;
		}
		$this->_name = "ldc_booking";
		$book_id = $this->insert($arr);
			
		$arr_deatail = array(
				'book_id'	=>	$book_id,
				'item_id'	=>	$session->vehicle_id,
				'item_name'	=>$session->vehicle_name,
				'rent_num'	=>	1,
				'price'		=>	$session->price,
				//'deposite'	=>	($session->price)*(30/100),
				'total'		=>	$session->price,
				'total_paymented'	=>	($session->price),
				'status'	=>	1,
				'item_type'	=>	1
		);
		$this->_name="ldc_booking_detail";
		$this->insert($arr_deatail);
		
		if($session->guideid !="None"){
			$arr_deatail = array(
					'book_id'			=>	$book_id,
					'item_id'			=>	$session->guideid,
					'item_name'			=>$session->guidename,
					'rent_num'			=>	1,
					'price'				=>	$session->guideprice,
					//'deposite'			=>	($session->guideprice)*(30/100),
					'total'				=>	$session->guideprice,
					'total_paymented'	=>	($session->guideprice),
					'status'			=>	1,
					'item_type'			=>	4
			);
			$this->_name="ldc_booking_detail";
			$this->insert($arr_deatail);
		}
// 		exit();
		$db->commit();
		return $book_id;
		}catch (Exception $e){
			$db->rollBack();
			$e->getMessage();
		}
		
	}  
 
}
?>