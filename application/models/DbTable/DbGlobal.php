<?php
class Application_Model_DbTable_DbGlobal extends Zend_Db_Table_Abstract
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
	function getLogo(){
		$db = $this->getAdapter();
		$sql = "SELECT id ,title,photo_name,
		(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_photo.`status`  ) AS status
		FROM `ldc_photo` WHERE status=1 ";
		$order=' ORDER BY id DESC';
		return $db->fetchRow($sql.$order);
	}
	public function getAllParameter($title){
		$db= $this->getAdapter();
		$this->_name="ldc_paramater";
		$sql = "SELECT p.`value` FROM $this->_name AS p WHERE p.`status`=1 AND p.`title`='$title'";
		return $db->fetchOne($sql);
	}
	public function getGlobalDb($sql)
  	{
  		$db=$this->getAdapter();
  		$row=$db->fetchAll($sql);  		
  		if(!$row) return NULL;
  		return $row;
  	}
  	public function getGlobalDbRow($sql)
  	{
  		$db=$this->getAdapter();  		
  		$row=$db->fetchRow($sql);
  		if(!$row) return NULL;
  		return $row;
  	}
  	public static function getActionAccess($action)
    {
    	$arr=explode('-', $action);
    	return $arr[0];    	
    }     
    public function isRecordExist($conditions,$tbl_name){
		$db=$this->getAdapter();		
		$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1"; 
		$row= count($db->fetchRow($sql));
		if(!$row) return NULL;
		return $row;	
    }
    /*for select 1 record by id of earch table by using params*/
    public function GetRecordByID($conditions,$tbl_name){
    	$db=$this->getAdapter();
    	$sql="SELECT * FROM ".$tbl_name." WHERE ".$conditions." LIMIT 1";
    	$row = $this->fetchRow($sql);
    	return $row;
    	$row= $db->fetchRow($sql);
    	return $row;
    }
    /**
     * insert record to table $tbl_name
     * @param array $data
     * @param string $tbl_name
     */
    public function addRecord($data,$tbl_name){
    	$this->setName($tbl_name);
    	return $this->insert($data);
    }
    public function updateRecord($data,$id,$tbl_name){
    	$this->setName($tbl_name);
    	$where=$this->getAdapter()->quoteInto('id=?',$id);
    	$this->update($data,$where);    	
    }   
    public function DeleteRecord($tbl_name,$id){
    	$db = $this->getAdapter();
		$sql = "UPDATE ".$tbl_name." SET status=0 WHERE id=".$id;
		return $db->query($sql);
    } 
     public function DeleteData($tbl_name,$where){
    	$db = $this->getAdapter();
		$sql = "DELETE FROM ".$tbl_name.$where;
		return $db->query($sql);
    } 
    public function getDayInkhmerBystr($str){
    	
    	$rs=array(
    			'Mon'=>'áž…áŸ�áž“áŸ’áž‘',
    			'Tue'=>'áž¢áž„áŸ’áž‚áž¶ážš',
    			'Wed'=>'áž–áž»áž’',
    			'Thu'=>"áž–áŸ’ážšáž ",
    			'Fri'=>"ážŸáž»áž€áŸ’ážš",
    			'Sat'=>"ážŸáŸ…ážšáž¸",
    			'Sun'=>"áž¢áž¶áž‘áž·áž�áŸ’áž™");
    	if($str==null){
    		return $rs;
    	}else{
    	return $rs[$str];
    	}
    
    }
    public function convertStringToDate($date, $format = "Y-m-d H:i:s")
    {
    	if(empty($date)) return NULL;
    	$time = strtotime($date);
    	return date($format, $time);
    }   
    public static function getResultWarning(){
          return array('err'=>1,'msg'=>'áž˜áž·áž“â€‹áž‘áž¶áž“áŸ‹â€‹áž˜áž¶áž“â€‹áž‘áž“áŸ’áž“áž·áž“áŸ�áž™â€‹áž“áž¼ážœâ€‹áž¡áž¾áž™â€‹áž‘áŸ�!');	
    }
   /*@author Mok Channy
    * for use session navigetor 
    * */
//    public static function SessionNavigetor($name_space,$array=null){
//    	$session_name = new Zend_Session_Namespace($name_space);
//    	return $session_name;   	
//    }
    function getAllClient($branch_id=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT c.`client_id` AS id  ,c.`branch_id`,
    	CONCAT(c.`name_en`,'-',c.`name_kh`) AS name , client_number
    	FROM `ln_client` AS c WHERE c.`name_en`!='' AND c.status=1  " ;
    	if($branch_id!=null){
    		$sql.=" AND c.`branch_id`= $branch_id ";
    
    	}
    	$sql.=" ORDER BY id DESC";
    	return $db->fetchAll($sql);
    }
    function getAllClientNumber($branch_id=null){
    	$db = $this->getAdapter();
    	$sql = " SELECT c.`client_id` AS id  ,c.client_number AS name, c.`branch_id`
    	FROM `ln_client` AS c WHERE c.`name_en`!='' AND c.status=1 " ;
    	if($branch_id!=null){
    		$sql.=" AND c.`branch_id`= $branch_id ";
    	}
    	return $db->fetchAll($sql);
    }
    public  function getclientdtype(){
    	$db = $this->getAdapter();
    	$sql="SELECT key_code AS id,CONCAT(name_kh,'-',name_en) AS name ,displayby FROM `ldc_view` WHERE STATUS =1 AND type=6";
    	$rows = $db->fetchAll($sql);
    	return $rows;
    }
   public function getAllProvince($opt=null){
   	$this->_name='ldc_province';
   	$sql = " SELECT id,province_name FROM $this->_name WHERE status=1 AND province_name!='' ORDER BY id DESC";
   	$db = $this->getAdapter();
    $rows =$db->fetchAll($sql);
	   	if($opt!=null){
	   		$options="";
	   		if(!empty($rows))foreach($rows AS $row){
	   			$options[$row['id']]=$row['province_name'];
	   		}
	   		return $options;
	   	}else{
	   		return $rows;
	   	}
   }
   
   public function getAllPackageDay($opt=null){
   	$this->_name='ldc_rankday';
   	$sql = " SELECT id,CONCAT(day_title,'(',from_amountday,'-',to_amountday,')') as package FROM $this->_name WHERE status=1 AND day_title!='' ";
   	$db = $this->getAdapter();
   	$rows =$db->fetchAll($sql);
   	if($opt!=null){
   		$options="";
   		if(!empty($rows))foreach($rows AS $row){
   			$options[$row['id']]=$row['day_title'];
   		}
   		return $options;
   	}else{
   		return $rows;
   	}
   }
   public function getAllTax($opt=null){
   	$this->_name='ldc_tax';
   	$sql = " SELECT value as id ,CONCAT(title,'( ',value,'%)') AS title FROM $this->_name WHERE title!='' AND STATUS=1 ";
   	$db = $this->getAdapter();
   	$rows =$db->fetchAll($sql);
   	if($opt!=null){
   		$options="";
   		if(!empty($rows))foreach($rows AS $row){
   			$options[$row['id']]=$row['title'];
   		}
   		return $options;
   	}else{
   		return $rows;
   	}
   }
   public function getAllNameOwner($opt=null){
   	$sql="SELECT id,owner_name FROM ldc_owner WHERE 1";
   	return $this->getAdapter()->fetchAll($sql);
   }
   public function getAllCurrency($id,$opt = null){
	   	$sql = "SELECT * FROM ln_currency WHERE status = 1 ";
	   	if($id!=null){
	   		$sql.=" AND id = $id";
	   	}
	   	$rows = $this->getAdapter()->fetchAll($sql);
	   	if($opt!=null){
	   		$options="";
	   		if(!empty($rows))foreach($rows AS $row){
	   			$options[$row['id']]=($row['displayby']==1)?$row['displayby']:$row['curr_nameen'];
	   		}
	   		return $options;
	   	}else{
	   		return $rows;
	   	}
   	
   }
   public function getNewCustomerId(){
   	$this->_name='ldc_customers';
   	$db = $this->getAdapter();
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = "CUS-";
   	for($i = $acc_no;$i<4;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getNewSupplierId(){
   	$this->_name='ldc_supplier';
   	$db = $this->getAdapter();
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = "SU-";
   	for($i = $acc_no;$i<4;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getNewClientId(){
   	$this->_name='ldc_customer';
   	$db = $this->getAdapter();
   	$row = $this->getSystemSetting('customer_prefix');
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<4;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getNewBookingCode(){
   	$this->_name='ldc_booking';
   	$db = $this->getAdapter();
   	$row = $this->getSystemSetting('booking_prefix');
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<3;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
   public function getDriverCode(){
   	$this->_name='ldc_driver';
   	$db = $this->getAdapter();
   	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
   	$acc_no = $db->fetchOne($sql);
   	$new_acc_no= (int)$acc_no+1;
   	$acc_no= strlen((int)$acc_no+1);
   	
   	$row = $this->getSystemSetting('driver_prefix');
   	$pre = ($row['value']);
   	for($i = $acc_no;$i<3;$i++){
   		$pre.='0';
   	}
   	return $pre.$new_acc_no;
   }
     
   public static function getCurrencyType($curr_type){
   	$curr_option = array(
   			1=>'ážšáŸ€áž›',
   			2=>'ážŠáž»áž›áŸ’áž›áž¶'
   			);
   	return $curr_option[$curr_type];
   	
   }
   public function getAllSituation($id = null){
   	$_status = array(
   			1=>$this->tr->translate("Single"),
   			2=>$this->tr->translate("Married"),
   			3=>$this->tr->translate("Windowed"),
   			4=>$this->tr->translate("Mindowed")
   	);
   	if($id==null)return $_status;
   	else return $_status[$id];
   }
   public function GetAllIDType($id = null){
   	$_status = array(
   			1=>$this->tr->translate("National ID"),
   			2=>$this->tr->translate("Family Book"),
   			3=>$this->tr->translate("Resident Book"),
   			4=>$this->tr->translate("Other")
   	);
   	if($id==null)return $_status;
   	else return $_status[$id];
   }
   public function getAllDegree($id=null){
   	$tr= Application_Form_FrmLanguages::getCurrentlanguage();
   	$opt_degree = array(
   			''=>$this->tr->translate("----áž‡áŸ’ážšáž¾ážŸážšáž¾ážŸ----"),
   			1=>$this->tr->translate("Diploma"),
   			2=>$this->tr->translate("Associate"),
   			3=>$this->tr->translate("Bechelor"),
   			4=>$this->tr->translate("Master"),
   			5=>$this->tr->translate("PhD")
   	);
   	if($id==null)return $opt_degree;
   	else return $opt_degree[$id]; 
  }

  function countDaysByDate($start,$end){
  	$first_date = strtotime($start);
  	$second_date = strtotime($end);
  	$offset = $second_date-$first_date;
  	return floor($offset/60/60/24);
  
  }

 public function returnAfterHoliday($holiday_option,$date){
	  $rs = $this->checkHolidayExist($holiday_option,$date);
	  if(is_array($rs)){
	  	$d = new DateTime($rs['start_date']);
	  	$d->modify( 'next day' );//here check for holiday_option
	  	$date =  $d->format( 'Y-m-d' );
	  	$this->returnAfterHoliday($holiday_option,$date);
	  }else{
	  	echo $date;
	  	return $date;
	  }
  }

  public function getVewOptoinTypeByType($type=null,$option = null,$limit =null,$first_option =null){
  	$db = $this->getAdapter();
  	$sql="SELECT id,key_code,CONCAT(name_kh,'-',name_en) AS name_en ,displayby FROM `ldc_view` WHERE status =1 ";//just concate
  	if($type!=null){
  		$sql.=" AND type = $type ";
  	}
  	if($limit!=null){
  		$sql.=" LIMIT $limit ";
  	}
  	$rows = $db->fetchAll($sql);
  	if($option!=null){
  		$options=array();
  		if($first_option==null){//if don't want to get first select
  			$options=array(''=>"-----áž‡áŸ’ážšáž¾ážŸážšáž¾ážŸ-----",-1=>"Add New",);
  		}
  		if(!empty($rows))foreach($rows AS $row){
  			$options[$row['key_code']]=$row['name_en'];//($row['displayby']==1)?$row['name_kh']:$row['name_en'];
  		}
  		return $options;
  	}else{
  		return $rows;
  	}
  }
//   public function getCollteralType($option = null,$limit =null){
//   	$db = $this->getAdapter();
//   	$sql="SELECT id,title_en,title_kh,displayby FROM `ln_callecteral_type` WHERE status =1 ";
//   	if($limit!=null){
//   		$sql.=" LIMIT $limit ";
//   	}
//   	$rows = $db->fetchAll($sql);
//   	if($option!=null){
//   		$options=array(''=>"-----Select Callecteral Type-----",'-1'=>"Add New");
//   		if(!empty($rows))foreach($rows AS $row){
//   			$options[$row['id']]=($row['displayby']==1)?$row['title_kh']:$row['title_en'];
//   		}
//   		return $options;
//   	}else{
//   		return $rows;
//   	}
//   }
  
  public function checkHolidayExist($date_next,$holiday_option){//for check collect payment in holiday or not
  	$db = $this->getAdapter();
  	$sql="SELECT start_date FROM `ln_holiday` WHERE start_date='".$date_next."'";
  	$rs =  $db->fetchRow($sql);
  	
  	$db = new Setting_Model_DbTable_DbLabel();
  	$array = $db->getAllSystemSetting();
  	if($rs){
  		$d = new DateTime($rs['start_date']);
  		if($holiday_option==1){
  			$str_option = 'previous day';
  		}elseif($holiday_option==2){
  			$str_option = 'next day';
  		}else{
  			return  $d->format( 'Y-m-d' );
  		}
  		$d->modify($str_option); //here check for holiday option //can next day,next week,next month
  		$date_next =  $d->format( 'Y-m-d' );
//   		return $date_next;
  		
  		$d = new DateTime($date_next);
  		$day_work = date("D",strtotime($date_next));
  		
  		if($day_work=='Sat' OR $day_work=='Sun' ){
  			if(($day_work=='Sat' AND $array['work_saturday']==1) OR ($day_work=='Sun' AND $array['work_sunday']==1)){//sat working
  				return $date_next;
  			}else if($day_work=='Sat' AND $array['work_saturday']==0){//sat not working
  				if($holiday_option==1){//after 
  					$str_next = '+2 day';
  				}else{//before
  					$str_next = '-1 day';//thu
  				}
  				
  				$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  				$date_next =  $d->format( 'Y-m-d' );
  				return $date_next;
  			}else{//sun not working continue to monday // but not check if mon day not working
  				if($holiday_option==1){//after
  					$str_next = '+1 day';
  				}else{//before
  					$str_next = '-1 day';//thu
  				}
  				$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  				$date_next =  $d->format( 'Y-m-d' );
  				return $date_next;
  			}
  		}else{
  			return $date_next;
  		}
  		
  	}
  	else{
  		$d = new DateTime($date_next);
  		$day_work = date("D",strtotime($date_next));
  	    if($day_work=='Sat' OR $day_work=='Sun' ){
  	    	if(($day_work=='Sat' AND $array['work_saturday']==1) OR ($day_work=='Sun' AND $array['work_sunday']==1)){//sat working
  	    		return $date_next;
  	    	}else if($day_work=='Sat' AND $array['work_saturday']==0){//sat not working
  	    		$str_next = '+2 day';
  	    		$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  	    		$date_next =  $d->format( 'Y-m-d' );
  	    		return $date_next;
  	    	}else{//sun not working continue to monday // but not check if mon day not working
  	    		$str_next = '+1 day';
  	    		$d->modify($str_next); //here check for holiday option //can next day,next week,next month
  	    		$date_next =  $d->format( 'Y-m-d' );
  	    		return $date_next;
  	    	}
  	    }else{
  	    	return $date_next;
  	    }
  	}
  }
  public function CountDayByDate($start,$end){
  	$db = new Application_Model_DbTable_DbGlobal();
  	return ($db->countDaysByDate($start,$end));
  }
  public function CurruncyTypeOption(){
  	$db = $this->getAdapter();
  	$rows=array(2=>"ážŠáž»áž›áŸ’áž›áž¶",3=>"áž”áž¶áž�",1=>"ážšáŸ€áž›");
  	$option='';
  	if(!empty($rows))foreach($rows as $key=>$value){
  		$option .= '<option value="'.$key.'" >'.htmlspecialchars($value, ENT_QUOTES).'</option>';
  	}
  	return $option;
  }
  public function getSystemSetting($keycode){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ln_system_setting` WHERE keycode ='".$keycode."'";
  	return $db->fetchRow($sql);
  }
  static function getPaymentTermById($id=null){
  	$arr = array(
  			1=>"áž�áŸ’áž„áŸƒ",
  			2=>"áž¢áž¶áž‘áž·áž�áŸ’áž™",
  			3=>"áž�áŸ‚");
  	if($id!=null){
		return $arr[$id];
  	}
  	return $arr;
  	
  }
  function getVehicle(){
  	$db = $this->getAdapter();
  	$sql = "SELECT 
			  v.`reffer`,
			  v.`frame_no`,
			  m.`title` AS make,
			  md.`title` AS model,
			  sm.`title` AS sub_model,
  				v.`img_front_right`,
  			 v.`img_seat`,
  			 v.`id`
			FROM
			  `ldc_vehicle` AS v,
			  `ldc_item_cat` AS m,
			  `ldc_model` AS md,
			  `ldc_submodel` AS sm 
			WHERE v.`make_id` = m.`id` 
			  AND v.`sub_model` = sm.`id` 
			  AND v.`model_id` = md.`id` 
  			  AND v.`status`=1 ";
  	$order=' ORDER BY v.`date` DESC';
  	return $db->fetchAll($sql.$order);
  }
  function getVehicleDetail($id){
  	$db = $this->getAdapter();
  	$sql = "SELECT
			  v.*,
			  m.`title` AS make,
			  md.`title` AS model,
			  sm.`title` AS sub_model
			FROM
			  `ldc_vehicle` AS v,
			  `ldc_item_cat` AS m,
			  `ldc_model` AS md,
			  `ldc_submodel` AS sm
			WHERE v.`make_id` = m.`id`
			  AND v.`sub_model` = sm.`id`
			  AND v.`model_id` = md.`id` 
  			  AND v.`id`=$id ";
  	return $db->fetchRow($sql);
  }
  function getAllFoodCat(){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,CONCAT(name_en,' ',name_kh) AS name FROM ldc_food_cat WHERE status = 1";
  	$order=' ORDER BY id DESC';
  	return $db->fetchAll($sql.$order);
  }
  function getAllItemCat(){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,CONCAT(name_en,' ',name_kh) AS name FROM ldc_item_cat WHERE status = 1";
  	$order=' ORDER BY id DESC';
  	return $db->fetchAll($sql.$order);
  }
  function getAllModels(){
  	$db = $this->getAdapter();
  	$sql = " SELECT id,title AS name FROM ldc_model WHERE status = 1";
  	return  $db->fetchAll($sql);
  }
  function ajaxaddMake($data){
  	$this->_name='ldc_item_cat';
  	$db = $this->getAdapter();
	  	$arr = array(
	  			'name_kh'=>$data['txt_make'],
	  			'name_en'=>'',
	  			'status'=>1
	  			
	  	);
  	return $this->insert($arr);
  }
  function ajaxaddModel($data){
  	$this->_name='ldc_model';
  	$db = $this->getAdapter();
  	$arr = array(
  			'brand_id'=>$data['txt_makeid'],
  			'title'=>$data['txt_model'],
  			'status'=>1
  
  	);
  	return $this->insert($arr);
  }
  function getViews($type=2){
  	$db=$this->getAdapter();
  	$sql="SELECT key_code,name_en FROM ldc_view WHERE `type`=$type";
  	return $db->fetchAll($sql);
  }
  function getAllLocationByProvince($province,$opt=null){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,location_name FROM `ldc_package_location` WHERE STATUS=1 AND province_id=$province AND is_package!=1 ";
  	$row =  $db->fetchAll($sql);
  	if($opt!=null){
  		$option='';
  		foreach($row as $r){
  			$option .= '<option value="'.$r['id'].'">'.htmlspecialchars($r['location_name'], ENT_QUOTES).'</option>';
  		}
  		return $option;
  	}else {
  		return $row;
  	}
  }

 
  function getParameter($title){
  	$db= $this->getAdapter();
  	$sql = 'SELECT p.`id`,p.`title`,p.`value` FROM `ldc_paramater` AS p WHERE p.`status`=1 AND p.`title`='."'$title'";
  	return $db->fetchRow($sql);
  }
  function getmenucontact(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 3";
  	return $db->fetchAll($sql);
  }
  function getmenucontactFooter(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE id=3 AND `type`= 3";
  	return $db->fetchAll($sql);
  }
  function getAllGuestBookFooter($limit=null){
  	$db = $this->getAdapter();
  	$sql = "SELECT id,name,subject,email,website,comment,date FROM `ldc_guestbook` WHERE subject !='' ";
  	if($limit !=null){
  		$sql.=" AND status =1 ORDER BY id DESC ";
  	}else{
  		$sql.=" AND status =1 ORDER BY id DESC LIMIT 2";
  	}
  	
  	return $db->fetchAll($sql);
  }
  function getmenuabout(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getmenuService(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getmenuaboutFooter(){
  	$db = $this->getAdapter();
  	$sql = "SELECT * FROM `ldc_manytable` WHERE id=4 AND `type`= 2";
  	return $db->fetchAll($sql);
  }
  function getAllJob(){
  	$db = $this->getAdapter();
  	$sql = "SELECT j.`id`,j.`job_title`,j.`salary`,j.`hiring`,j.`closting_date`,j.`job_type` FROM `ldc_job` AS j WHERE j.`status`=1 ORDER BY j.`posting_date` DESC";
  	return $db->fetchAll($sql);
  }
  function getJobById($id){
  	$id= addslashes($id);
  	$db = $this->getAdapter();
  	$sql = "SELECT j.* FROM `ldc_job` AS j WHERE j.id= $id ";
  	return $db->fetchRow($sql);
  }
 
 
  function getAllSlider(){
  	$db = $this->getAdapter();
  	$sql = "SELECT id ,title,caption,ordering,img,
  	(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_slider.`status` limit 1 ) AS status,is_showcaption
  	FROM `ldc_slider` WHERE status=1 ";
  	return $db->fetchAll($sql);
  }

  function getLocatioNameById($id){
  	$db = $this->getAdapter();
  	$sql="SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=$id";
  	return $db->fetchOne($sql);
  	
  }

	function getAllPromotion(){
		$db= $this->getAdapter();
		$sql="SELECT *  FROM ldc_promotion  where status=1 ORDER BY id ASC ";
		return $db->fetchAll($sql);
	}
	
	function getItem(){ // Intergrediant of food
		$db= $this->getAdapter();
		$sql = "SELECT p.id,p.`pro_name_kh`,p.`pro_name_en` FROM `ldc_product` AS p WHERE p.`status`=1";
		return $db->fetchAll($sql);
	}
	function getProductPrice($id){
		$db = $this->getAdapter();
		$sql = "SELECT p.`price`,m.`measure_name_kh` FROM `ldc_product` AS p,`ldc_measure` AS m WHERE p.`id`=$id AND p.`unit`=m.`id`";
		return $db->fetchRow($sql);
	}
	function getCustomer($type){
		$db = $this->getAdapter();
		if($type==1){
			$sql = "SELECT c.id,c.`first_name`,c.`last_name` FROM `ldc_customers` AS c WHERE c.`status`=1";
			//$row = $db->fetchAll($sql);
		}else{
			$sql = "SELECT c.id,c.`customer_code` FROM `ldc_customers` AS c WHERE c.`status`=1";
		}
		return $db->fetchAll($sql);
	}
	
	function getFood(){
		$db = $this->getAdapter();
		$sql = "SELECT f.`id`,f.`name_kh`,f.`name_en` FROM `ldc_food` AS f WHERE f.`status`=1";
		return $db->fetchAll($sql);
	}
	function getFoodByCat($id){
		$db = $this->getAdapter();
		$sql = "SELECT f.`id`,f.`name_kh` as name FROM `ldc_food` AS f WHERE f.`status`=1 AND f.`cat_id`=$id";
		return $db->fetchAll($sql);
	}	
	function getCustomerById($id){
		$db = $this->getAdapter();
		$sql = "SELECT c.`phone`,c.`email`,c.address FROM `ldc_customers` AS c WHERE c.`id`=$id";
		return $db->fetchRow($sql);
	}
}