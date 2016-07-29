<?php

class Application_Model_DbTable_Dbguiderental extends Zend_Db_Table_Abstract
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
	static function getProductRankingDayPrice($product_id){
		$sql = " 
		SELECT id,(SELECT CONCAT(from_amountday,'-',to_amountday,' Days') FROM `ldc_rankday` WHERE id=package_id) AS package_name,
		  (SELECT equipment_name FROM `ldc_stuff` WHERE id=stuff_id) AS equipment_name,price 
           FROM `ldc_stuff_details` WHERE status=1 AND stuff_id = $product_id ";
		$db = new Application_Model_DbTable_Dbstuffrental();
		return $db->getGlobalDb($sql);
	}
	function getStuffNameById($id){
		$db = $this->getAdapter();
		$sql = " SELECT equipment_name FROM `ldc_stuff` WHERE id=$id AND status=1 ";
		return $db->fetchOne($sql);
	}
	public function getAllAvailableGuide($data,$type=3){ // 1=driver,2=guide,3=both  
		$db= $this->getAdapter();
		
		$pickup_date = new DateTime($data["pickup_date"]);
		$return_date = new DateTime($data["return_date"]);
		$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
		$returndate = $return_date->format('Y-m-d');
		$returntime = $data["return_time"];
		if($type==1){
			$position_type = " AND d.`position_type`=1";
		}elseif ($type==2){
			$position_type = " AND d.`position_type`=2";
		}else {
			$position_type = "";
		}
		$sql="SELECT d.`id`,d.`driver_id`,CONCAT(d.`first_name`,' ',d.`last_name`) AS `name`,d.`experience_desc`,d.`sex`,d.`nationality`,d.`lang_note`,d.`tel`,d.`email`,d.`photo`,d.`c_holidayprice`,d.`c_normalprice`,d.`c_otprice`,d.`c_weekendprice`,d.`p_holidayprice`,d.`p_normalprice`,d.`p_otprice`,d.`p_weekendprice`,d.`monthly_price`,d.`position_type`
		FROM `ldc_driver` AS d WHERE  d.id NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate ' BETWEEN b.`pickup_date` AND b.`return_date` AND (bd.item_type=2 OR bd.item_type=4 OR bd.item_type=5) AND b.status !=3) AND d.`status`=1 $position_type";//AND b.`return_time` >= '$returntime'
		//echo $sql;exit();
		return $db->fetchAll($sql);
	}
	public function getAllGuideFilter($data){ // 1=driver,2=guide,3=both
		$db= $this->getAdapter();
		$type = $data["type"];
		$session =new Zend_Session_Namespace('guidebooking');
		$pickup_date = new DateTime($session->pickup_date);
		$return_date = new DateTime($session->return_date);
		$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
		$returndate = $return_date->format('Y-m-d');
		$returntime = $session->return_time;
		if($type==1){
			$position_type = " AND d.`position_type`=1";
		}elseif ($type==2){
			$position_type = " AND d.`position_type`=2";
		}else {
			$position_type = "";
		}
		$sql="SELECT d.`id`,d.`driver_id`,CONCAT(d.`first_name`,' ',d.`last_name`) AS `name`,d.`experience_desc`,d.`sex`,d.`nationality`,d.`lang_note`,d.`tel`,d.`email`,d.`photo`,d.`c_holidayprice`,d.`c_normalprice`,d.`c_otprice`,d.`c_weekendprice`,d.`p_holidayprice`,d.`p_normalprice`,d.`p_otprice`,d.`p_weekendprice`,d.`position_type`,d.`monthly_price`
		FROM `ldc_driver` AS d WHERE  d.id NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate ' BETWEEN b.`pickup_date` AND b.`return_date` AND (bd.item_type=2 OR bd.item_type=4 OR bd.item_type=5) AND b.status !=3) AND d.`status`=1 $position_type";
		//return $returntime;
		return $db->fetchAll($sql);
	}
	function getProductSelected($data){
		if(!empty($data)){
			$ids = explode(',', $data['identity_guide']);
			foreach ($ids as $i){
				$product_id = $this->getGuideSelected($data['guideid_'.$i]);
				if($product_id["position_type"]==1){
					$item_name = "Chauffeur Rental (".$product_id["driver_name"].")";
				}elseif($data["position_type"]==2){
					$item_name = "Guide Rental (".$product_id["driver_name"].")";
				}else{
					$item_name = "Guide & Chauffeur Rental (".$product_id["driver_name"].")";
				}
				$arr[$i]=array(
						"product_id"=>$data['guideid_'.$i],
						'amount_rent'=>1,
						"product_name"=>$item_name,
						"pro_price"	=>	$product_id["p_normalprice"],
						"position_type"	=>	$product_id["position_type"],
						"monthly_price"	=>	$product_id["monthly_price"],
						);
			}
			return $arr;
		}
	}
	public function getGuideSelected($id){
		$db = $this->getAdapter();
		$sql="SELECT CONCAT(d.`first_name`,' ',d.`last_name`) AS driver_name ,d.`p_normalprice`,d.`monthly_price`,d.`position_type` FROM ldc_driver AS d WHERE d.id=$id LIMIT 1";
		$row = $db->fetchRow($sql);
		return $row;
	}
	function createSessionGuideBooking($data,$step=1){
// 		$this->clearSessionBYStep($step);
		$session =new Zend_Session_Namespace('guidebooking');
		if($step==1){
			$pickup_date = date_create($data['pickup_date']);
			$return_date = date_create($data['return_date']);
			$diff=date_diff($pickup_date,$return_date);
			$total_day = $diff->format("%a%")+1;
			
			if($data["rental_type"]==1){
				$duration = $total_day;
			}else {$duration = $data["monthly_rent"];}
			
			$session->point_step=1;
			$session->step1=1;
			$session->step2 = 0;
			$session->step3 = 0;
			$session->step4 = 0;
			$session->duration = $duration;
			$session->pickup_date = $data['pickup_date'];
			$session->pickup_time = $data['pickup_time'].":".$data['pickup_mins'];
			$session->return_date = $data['return_date'];
			$session->return_time = $data['return_time'].":".$data['return_mins'];
			$session->is_month = $data["rental_type"];
			$session->month_num = $data["monthly_rent"];
			
			
			$db = new Application_Model_DbTable_DbGlobal();
			$array= array(
					'pickup_date'=>$data['pickup_date'],
					'return_date'=>$data['return_date'],
					'return_time'=>$data['return_time'].":".$data['return_mins'],
					'is_month'	 =>$data["rental_type"],
			);
			$session->rowsguide = $this->getAllAvailableGuide($array);
		}
		elseif($step==2){
			$session->point_step=2;
			$session->step2 =1;
			$session->step3 = 0;
			$session->step4 = 0;
			$products = $this->getProductSelected($data);
			$session->products=$products;
		}elseif($step==3){
			$session->point_step=3;
			$session->step3 =1;
			//$session->step4 = 0;
		}elseif($step==4){
			$session->point_step=4;
			$session->step4 =1;
		}
		return true;
	}
	
	public function addProductRental($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
		$session =new Zend_Session_Namespace('guidebooking');
		$row = $session->products;
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$booking_code = $db_globle->getNewBookingCode();
		$customer_session  = new Zend_Session_Namespace('customer');
		$customer_id = $customer_session->customer_id;
		
		$pickup_time= $session->pickup_time;
		$return_time = $session->return_time;
		
		$rental_type=$session->is_month; // 1=daily,2=monthly,3=yearly
		$total_month = $session->month_num;
		
		$pickup_date = new DateTime($session->pickup_date);
		$return_date = new DateTime($session->return_date);
		
		$diff=date_diff(date_create($session->pickup_date),date_create($session->return_date));
		$total_day = $diff->format("%a%")+1;
		$total =0;
		foreach ($row as $rs){
			if($rental_type==1){
				$total += (($rs["pro_price"]*$rs["amount_rent"])*$total_day);
			}else{
				$total += (($rs["monthly_price"]*$rs["amount_rent"])*$total_month);
			}
		}
		
		$arr = array(
				'customer_id'	=>	$customer_id,
				'booking_no'	=>	$booking_code,
				'date_book'			=>	date('Y-m-d'),
				'pickup_date'		=>	$pickup_date->format('Y-m-d'),
				'pickup_time'		=>	$pickup_time,
				'return_date'		=>	$return_date->format('Y-m-d'),
				'return_time'		=>	$return_time,
				'total_fee'			=>	$total,
				'total_paymented'	=>	$total,
				'item_type'			=>	5,
				'rental_type'		=>	$rental_type,
				'total_duration'	=>	$session->duration,
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
		
		foreach ($row as $rs){
			if($rental_type==1){
				$price=$rs["pro_price"];
                                $total = (($rs["pro_price"]*$rs["amount_rent"])*$total_day);
			}else{
				$price=$rs["monthly_price"];
                                $total = (($rs["pro_price"]*$rs["amount_rent"])*$total_month);
			}
			$pos_type = $rs["position_type"];
			if($pos_type==1){
				$item_type=2;
			}elseif ($pos_type==2){
				$item_type = 4;
			}else{ $item_type= 5;}
			
			$arr_deatail = array(
					'book_id'	=>	$book_id,
					'item_id'	=>	$rs["product_id"],
					'item_name'	=>	$rs["product_name"],
					'rent_num'	=>	$rs["amount_rent"],
					'price'		=>	$price,
					'total'		=>	$total,
					'total_paymented'	=>	$total,
					'status'	=>	1,
					'item_type'	=>	4
			);
			$this->_name="ldc_booking_detail";
			$this->insert($arr_deatail);
		}
		$db->commit();
		return $book_id;
		}catch (Exception$e){
			$db->rollBack();
			echo $e->getMessage();
		}
		
	}
}
?>