<?php

class Application_Model_DbTable_Dbstuffrental extends Zend_Db_Table_Abstract
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
	public function getEquipment($data){
		$db= $this->getAdapter();
                $pickup_date = new DateTime($data["pickup_date"]);
		$return_date = new DateTime($data["return_date"]);
		$pickupdate = $pickup_date->format('Y-m-d'); // 2003-10-16
		$returndate = $return_date->format('Y-m-d');

		
		 
		$pickupdates=date_create($data["pickup_date"]);
		$returndates =date_create($data["return_date"]);
		$diff=date_diff($pickupdates,$returndates);
		$day = $diff->format("%a%");
		$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$day AND r.`to_amountday`>=$day LIMIT 1";
		$row_randay = $db->fetchOne($sql_rankday);
		if($row_randay){
			$row_randay;
		}else{
			$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 LIMIT 1";
			$row_randay=$db->fetchOne($sql_rankday);
		}
                 $sql="SELECT d.`id`,d.`equipment_name`,d.`reference_no`,d.`photo_front`,d.`url`,(SELECT st.price FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS price,(SELECT st.`extra_price` FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS extra_price  FROM `ldc_stuff` AS d 
		WHERE d.`status`=1";
		//$sql="SELECT d.`id`,d.`equipment_name`,d.`reference_no`,d.`photo_front`,d.`url`,(SELECT st.price FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS price,(SELECT st.`extra_price` FROM `ldc_stuff_details` AS st WHERE st.`stuff_id`=d.`id` AND st.`package_id`=$row_randay limit 1) AS extra_price  FROM `ldc_stuff` AS d WHERE d.id NOT IN(SELECT bd.`item_id` FROM ldc_booking AS b , `ldc_booking_detail` AS bd WHERE b.id=bd.`book_id` AND '$pickupdate' BETWEEN b.`pickup_date` AND b.`return_date` AND '$returndate' BETWEEN b.`pickup_date` AND b.`return_date` AND bd.item_type=3) AND d.`status`=1";
		return $db->fetchAll($sql);
	}
	function getProductSelected($data){
		if(!empty($data)){
			$ids = explode(',', $data['identity_equipment']);
			foreach ($ids as $i){
				$product_id = $this->getProductNameById($data['equipmentid_'.$i]);
				$product_price = $this->getProductPrice($data['equipmentid_'.$i]);
				$arr[$i]=array(
						"product_id"=>$data['equipmentid_'.$i],
						'amount_rent'=>$data['number_equipment_'.$i],
						"product_name"=>$product_id,
						"pro_price"	=>	$product_price,
						);
			}
			return $arr;
		}
	}
	function getProductNameById($pro_id){
		$db = $this->getAdapter();
		$sql = " SELECT equipment_name FROM `ldc_stuff` WHERE id =$pro_id LIMIT 1 ";
		return $db->fetchOne($sql);
	}
	function getProductPrice($pro_id){
		$db = $this->getAdapter();
		$session =new Zend_Session_Namespace('stuffbooking');
		$pickup_date = date_create($session->pickup_date);
		$return_date = date_create($session->return_date);
		$diff=date_diff($pickup_date,$return_date);
		$day = $diff->format("%a%")+1;
		
		$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`from_amountday`<=$day AND r.`to_amountday`>=$day LIMIT 1";
		$row_randay = $db->fetchOne($sql_rankday);
		if($row_randay){
			$row_randay;
		}else{
			$sql_rankday = "SELECT r.`id` FROM `ldc_rankday` AS r WHERE r.`is_morethen`=1 LIMIT 1";
			$row_randay=$db->fetchOne($sql_rankday);
		}
		$sql_product="SELECT s.`price` FROM `ldc_stuff_details` AS s WHERE s.`stuff_id`=$pro_id AND s.`package_id`=$row_randay LIMIT 1";
		return $db->fetchOne($sql_product);
	}
	function createSessionStuffBooking($data,$step=1){
// 		$this->clearSessionBYStep($step);
		$session =new Zend_Session_Namespace('stuffbooking');
		if($step==1){
			$session->point_step=1;
			$session->step1=1;
			$session->step2 = 0;
			$session->step3 = 0;
			$session->step4 = 0;
			$session->pickup_date = $data['pickup_date'];
			$session->pickup_time = $data['pickup_time'].":".$data['pickup_mins'];
			$session->return_date = $data['return_date'];
			$session->return_time = $data['return_time'].":".$data['return_mins'];
			$db = new Application_Model_DbTable_DbGlobal();
			$array= array(
					'pickup_date'=>$data['pickup_date'],
					'return_date'=>$data['return_date'],
					'return_time'=>$data['return_time'].":".$data['return_mins'],
			);
			$session->rowsproduct = $this->getEquipment($array);
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
		$session =new Zend_Session_Namespace('stuffbooking');
		$row = $session->products;
		$db_globle = new Application_Model_DbTable_DbGlobal();
		$booking_code = $db_globle->getNewBookingCode();
		$customer_session  = new Zend_Session_Namespace('customer');
		$customer_id = $customer_session->customer_id;
		
		$pickup_time= $session->pickup_time;
		$return_time = $session->return_time;
		
		$pickup_date = new DateTime($session->pickup_date);
		$return_date = new DateTime($session->return_date);
		
		$diff=date_diff(date_create($session->pickup_date),date_create($session->return_date));
		$total_day = $diff->format("%a%")+1;
		$total =0;
		foreach ($row as $rs){
			$total += (($rs["pro_price"]*$rs["amount_rent"])*$total_day);
		}
		
		$arr = array(
				'customer_id'	        =>	$customer_id,
				'booking_no'	        =>	$booking_code,
				'date_book'		=>	date('Y-m-d'),
				'pickup_date'		=>	$pickup_date->format('Y-m-d'),
				'pickup_time'		=>	$pickup_time,
				'return_date'		=>	$return_date->format('Y-m-d'),
				'return_time'		=>	$return_time,
				'total_fee'		=>	$total,
				'total_paymented'	=>	$total,
				'item_type'		=>	4,
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
			$arr_deatail = array(
					'book_id'	=>	$book_id,
					'item_id'	=>	$rs["product_id"],
					'item_name'	=>	$rs["product_name"],
					'rent_num'	=>	$rs["amount_rent"],
					'price'		=>	$rs["pro_price"],
					'total'		=>	(($rs["pro_price"]*$rs["amount_rent"])*$total_day),
					'total_paymented'	=>	(($rs["pro_price"]*$rs["amount_rent"])*$total_day),
					'status'	=>	1,
					'item_type'	=>	3
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