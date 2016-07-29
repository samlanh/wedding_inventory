<?php
class Application_Model_DbTable_Dbbookingtaxi extends Zend_Db_Table_Abstract
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
	function getAllLocation(){
		$db = $this->getAdapter();
		$sql = "SELECT id,location_name FROM `ldc_package_location` WHERE status=1 ";
		return $db->fetchAll($sql);
	}
	function getLocationNameById($location_id){
		$db = $this->getAdapter();
		$sql = "SELECT location_name FROM `ldc_package_location` WHERE id=$location_id LIMIT 1";
		return $db->fetchOne($sql);
	}
	function getTaxiPriceByLocation($vehicle,$from_locationid,$to_locationid){
		$db = $this->getAdapter();
		$sql = " SELECT price,tax,round_trip FROM `ldc_vehicletaxi` WHERE vehicle_id = $vehicle AND from_location = $from_locationid AND to_location = $to_locationid AND status=1 LIMIT 1";
		return $db->fetchRow($sql);
	}
	function getTimeArrived($vehicle,$from_locationid,$to_locationid){
		$db = $this->getAdapter();
		$sql = " SELECT note FROM `ldc_vehicletaxi` WHERE vehicle_id = $vehicle AND from_location = $from_locationid AND to_location = $to_locationid AND status=1 LIMIT 1";
		return $db->fetchOne($sql);
	}
	function createSessionTaxiBooking($data,$step=1){
// 		$this->clearSessionBYStep($step);
		$session =new Zend_Session_Namespace('taxibooking');
		if($step==1){
			$session->point_step=1;
			$session->step1=1;
			$session->step2=0;
			$session->step3=0;
			$session->step4=0;
			$data['return_date']=$data['pickup_date'];
// 			$data['return_time']=$data['pickup_time'].":".$data['pickup_mins'];//must get max hour plus with pick up
			$session->pickup_date = empty($data['pickup_date'])? null : $data['pickup_date'];
			$session->pickup_time = empty($data['pickup_time'])? null : $data['pickup_time'];
			$session->pickup_mins = empty($data['pickup_mins'])? null : $data['pickup_mins'];
			$session->return_date = empty($data['return_date'])? null : $data['return_date'];
			$session->return_time =empty($data['return_time'])? null : $data['return_time'];
// 			$session->return_mins =empty($data['return_mins'])? null : $data['return_mins'];
			$session->from_location = empty($data['from_location'])? 0 : $data['from_location'];
			$session->to_location = empty($data['to_location'])? 0 : $data['to_location'];
			$session->trip_way =$data['trip_way'];
			$session->from_locationname = $this->getLocationNameById($data['from_location']);
			$session->to_locationname = $this->getLocationNameById($data['to_location']);
			$db = new Application_Model_DbTable_DbGlobal();
			$session->vehiclevaliable = $db->getAllAvailableVehicle($data);
		}
		elseif($step==2){
			$session->point_step=2;
			$session->step2 =1;
			$session->vehicle_id=$data;//$data get parram store value only not array
			$row = $this->getTaxiPriceByLocation($data,$session->from_location,$session->to_location);
			if($session->trip_way==1){
				$taxiprice=$row['price']+($row['price']*$row['tax']/100);
			}else{
				$taxiprice=$row['round_trip']+($row['round_trip']*$row['tax']/100);
			}
			$session->taxi_price=$taxiprice;
			
			$db = new Application_Model_DbTable_DbGlobal();
			$array= array(
					'pickup_date'=>$session->pickup_date,
					'return_date'=>$session->pickup_date,
					'return_time'=>$session->pickup_time.":".$session->pickup_mins,
			);
			$session->guideavaliable =$db->getAvailableDriver($array);
			$row = $db->geVehicleById($data);
			$session->vehicle_name =$row["make"]." ".$row["model"]." ".$row["sub_model"]."(".$row["reffer"].")";
			
			$session->time = $this->getTimeArrived($data,$session->from_location,$session->to_location);

		}elseif($step==3){
			$session->point_step=3;
			$session->step3 =1;
			$session_user=new Zend_Session_Namespace('customer');
			$session->user_id = $session_user->customer_id;
			$session->user_name = $session_user->customer_name;
		}elseif($step==4){
			$session->point_step=4;
			$session->step4 =1;
		}
		return true;
	}
	function addTaxiBooking($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$session_user=new Zend_Session_Namespace('customer');
			$user_id = $session_user->customer_id;
			$session =new Zend_Session_Namespace('taxibooking');
			
			$db_globle = new Application_Model_DbTable_DbGlobal();
			$booking_code = $db_globle->getNewBookingCode();
			$trip_type = ($session->trip_way==1)?"One-Way":"Round Trip";
			
			$pickup_date = new DateTime($session->pickup_date);
			//$return_date = new DateTime($data["return_date"]);
			 
			$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
			//$returndate = $return_date->format('Y-m-d');
			
			
			$arr = array(
				'customer_id'		=>	$user_id,
				'trip_type'			=>	$trip_type,
				'booking_no'		=>	$booking_code,
				'pickup_location'	=>	$session->from_location,
				'dropoff_location'	=>	$session->to_location,
				'date_book'			=>	date('Y-m-d'),
				'pickup_date'		=>	$pickupdate,
				'pickup_time'		=>	$session->pickup_time.":".$session->pickup_mins,
				'total_fee'			=>	$session->taxi_price,
				//'deposite_fee'		=>	($session->taxi_price),
				'total_paymented'	=>	($session->taxi_price),
				'item_type'			=>	2,
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
				'item_name'	=>	$session->vehicle_name,
				'rent_num'	=>	1,
				'price'		=>	$session->taxi_price,
				//'deposite'	=>	($session->taxi_price)*(30/100),
				'total'		=>	$session->taxi_price,
				'total_paymented'	=>	($session->taxi_price),
				'status'	=>	1,
				'item_type'	=>	1
			);
			$this->_name="ldc_booking_detail";
			$this->insert($arr_deatail);
			$db->commit();
			return $book_id;
		}catch (Exception $e){
			$db->rollBack();
			$e->getMessage();
		}
	}
}
?>