<?php
class Application_Form_FrmBooking extends Zend_Dojo_Form{
	protected $tr = null;
	protected $btn =null;//text validate
	protected $filter = null;
	protected $text =null;
	protected $validate = null;
	protected $date;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->btn = 'dijit.form.Button';
		$this->validate = 'dijit.form.ValidationTextBox';
		$this->date = 'dijit.form.DateTextBox';
	}
	public function FromBooking(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_db = new Application_Model_DbTable_DbGlobal();
		$c_date = date("d-m-Y");
		$pickup_date = new Zend_Form_Element_Text("pickup_date");
		$pickup_date->setAttribs(array('id'=>'pick_date','size'=>"20",'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		$pickup_date->setValue($c_date);
		if($request->getParam("pickup_date")==""){
			$pickup_date->setValue($c_date);
		}else{
			$pickup_date->setValue($request->getParam("pickup_date"));
		}
		
		$return_date = new Zend_Form_Element_Text("return_date");
		$return_date->setAttribs(array('id'=>'datepicker','style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		if($request->getParam("return_date")==""){
			$return_date->setValue($c_date);
		}else{
			$return_date->setValue($request->getParam("return_date"));
		}
		
		$rows = $_db->getGlobalDb("SELECT id,province_name FROM `ldc_province` WHERE `status` = 1");
		
		$opt_location = array(0=>$this->tr->translate("CHOOSE_LOCTION"));
		if(!empty($rows)){
			foreach($rows AS $row) {$opt_location[$row['id']]=$row['province_name'];};
		}
		$pickup_location = new Zend_Form_Element_Select("pickup_location");
		$pickup_location->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		$pickup_location->setMultiOptions($opt_location);
		//$pickup_location->setValue(25);
		if($request->getParam("pickup_location")==""){
			$pickup_location->setValue(25);
		}else{
			$pickup_location->setValue($request->getParam("pickup_location"));
		}
		
		$return_location = new Zend_Form_Element_Select("return_location");
		$return_location->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		$return_location->setMultiOptions($opt_location);
		if($request->getParam("return_location")==""){
			$return_location->setValue(25);
		}else{
			$return_location->setValue($request->getParam("return_location"));
		}
		for($i=1;$i<12;$i++){
			$time = $i+6;
			if($i+6<=12){
				$icon = " AM";
			}else{
				$icon = " PM";
			}
			$value = $time.$icon;
			$option_time[$time] = $value;
		}
		$pickup_time = new Zend_Form_Element_Select("pickup_time");
		$pickup_time->setMultiOptions($option_time);
		$pickup_time->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$return_time = new Zend_Form_Element_Select("return_time");
		$return_time->setMultiOptions($option_time);
		$return_time->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$option_minute = array('00'=>'00');
		$sum = 0;
		for($j=1;$j<=3;$j++){
			$min = $sum+15;
			$sum=$sum+15;
			$option_minute[$min] = $sum;
		}
		
		$pickup_minute = new Zend_Form_Element_Select("pickup_minute");
		$pickup_minute->setMultiOptions($option_minute);
		$pickup_minute->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$return_minute = new Zend_Form_Element_Select("return_minute");
		$return_minute->setMultiOptions($option_minute);
		$return_minute->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$user_name = new Zend_Form_Element_Text("user_name");
		$user_name->setAttribs(array('style'=>'width: 100% !important;',
				'class'=>'control_style'
				));
		
		$password = new Zend_Form_Element_Password("password");
		$password->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$s_user_name = new Zend_Form_Element_Text("s_user_name");
		$s_user_name->setAttribs(array('data-validation-length'=>"min4",'style'=>'width: 100% !important;padding:1px !important;','onChange'=>'checkUserName(1);',
				'class'=>'control_style'));
		
		$s_password = new Zend_Form_Element_Password("s_password");
		$s_password->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$first_name = new Zend_Form_Element_Text("first_name");
		$first_name->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$last_name = new Zend_Form_Element_Text("last_name");
		$last_name->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$phone = new zend_form_element_text("phone");
		$phone->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$email = new zend_form_element_text('email');
		$email->setAttribs(array('type'=>'email','style'=>'width: 100% !important;padding:1px !important;',
				'onChange'=>'checkUserName(2);',
				'class'=>'control_style'));
		
		$passport_id = new zend_form_element_text('pass_id');
		$passport_id->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$pass_issue_date = new zend_form_element_text("pass_issue_date");
		$pass_issue_date->setAttribs(array('id'=>'pass_issue_date','style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$pass_exp_date = new zend_form_element_text('pass_exp_date');
		$pass_exp_date->setAttribs(array('id'=>'pass_exp_date','style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));

		$nationl = new zend_form_element_text('national');
		$nationl->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$opt_gender = array(1=>'M',2=>'F');
		$gender = new Zend_Form_Element_Select('gender');
		$gender->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;','class'=>'control_style'));
		$gender->setMultiOptions($opt_gender);
		
		$card_id = new zend_form_element_text('card_id');
		$card_id->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;','class'=>'control_style'));
		
		$card_issue_date = new zend_form_element_text("card_issue_date");
		$card_issue_date->setAttribs(array('id'=>'card_issue_date','style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$card_exp_date = new zend_form_element_text('card_exp_date');
		$card_exp_date->setAttribs(array('id'=>'card_exp_date','style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$secu_code = new zend_form_element_text('secu_code');
		$secu_code->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$card_name = new zend_form_element_text('card_name');
		$card_name->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$fly_no = new Zend_Form_Element_Text("fly_no");
		$fly_no->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;height: 30px;',
				'class'=>'control_style'
		));
		
		$fly_date = new Zend_Form_Element_Text("fly_date");
		$fly_date->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;height: 30px;',
				'class'=>'control_style'
		));
		$fly_date->setValue($c_date);
		
		$fly_time = new Zend_Form_Element_Text("fly_time");
		$fly_time->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;height: 30px;',
				'class'=>'control_style'
		));
		
		$fly_destination = new Zend_Form_Element_Textarea("fly_destination");
		$fly_destination->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;height: 30px;',
				'class'=>'control_style'
		));
		$dob = new Zend_Form_Element_Text("dob");
		$dob->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;height: 30px;',
				'class'=>'control_style','placeholder'=>'Date of Birth : d-m-YYYY'
		));
		
		$this->addElements(array($dob,$fly_date,$fly_destination,$fly_no,$fly_time,$card_name,$secu_code,$s_password,$s_user_name,$pickup_minute,$return_minute,$return_time,$pickup_time,$user_name,$password,$pickup_date,$return_date,$pickup_location,$return_location,$first_name,$last_name,$phone,$email,$passport_id,$pass_issue_date,$pass_exp_date,$nationl,$card_id,$card_issue_date,$card_exp_date,$gender));
		return $this;
	}
	public function FrmCustomer($data=null){
		$_dob= new Zend_Form_Element_Text('dob_client');
		$_dob->setValue(date("d-m-Y"));
		$_dob->setAttribs(array('class'=>'fullside',"style"=>"width:100%"));
		
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		
		$_namekh = new Zend_Form_Element_Text('name_kh');
		$_namekh->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		
		$_clientno = new Zend_Form_Element_Text('client_no');
		$_clientno->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$id_client = $db->getNewClientId();
		$_clientno->setValue($id_client);
		
		$_nameen = new Zend_Form_Element_Text('name_en');
		$_nameen->setAttribs(array(
				
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$_sex = new Zend_Form_Element_Select('sex');
		$_sex->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$opt_status = $db->getVewOptoinTypeByType(1,1);
		unset($opt_status[-1]);
		unset($opt_status['']);
		$_sex->setMultiOptions($opt_status);
		
		$_situ_status = new Zend_Form_Element_Select('status');
		$_situ_status->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$home = new Zend_Form_Element_Text('home');
		$home->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$group_num = new Zend_Form_Element_Text('group_num');
		$group_num->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));

		$i_group_num = new Zend_Form_Element_Text('igroup_num');
		$i_group_num->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$country = new Zend_Form_Element_Select('country');
		$country->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$row = $db->getAllCountry();
		$opt_country = array();
		if(!empty($row)){
			foreach ($row as $rs){
				$opt_country[$rs['id']]=$rs['country_name'];
			}
		}
		$country->setMultiOptions($opt_country);
		
		$state = new Zend_Form_Element_Text('state');
		$state->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		
		$commune = new Zend_Form_Element_Text('commune');
		$commune->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$district = new Zend_Form_Element_Text('district');
		$district->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$icommune = new Zend_Form_Element_Text('zip');
		$icommune->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$city = new Zend_Form_Element_Text('city');
		$city->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$province = new Zend_Form_Element_Select('province');
		$province->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$opt = $db->getAllProvince(1);
		$province->setMultiOptions($opt);
		
		$_street = new Zend_Form_Element_Text('street');
		$_street->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$address1 = new Zend_Form_Element_Text('address1');
		$address1->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$_id_type = new Zend_Form_Element_Select('id_type');
		$_id_type->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$address = new Zend_Form_Element_Text('address');
		$address->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$_phone = new Zend_Form_Element_Text('phone');
		$_phone->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$photo=new Zend_Form_Element_File('photo');
		$photo->setAttribs(array(
		));
		
		$job = new Zend_Form_Element_Text('job');
		$job->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$national_id= new Zend_Form_Element_Text('national_id');
		$national_id->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$email=new Zend_Form_Element_Text('email');
		$email->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$iemail=new Zend_Form_Element_Text('iemail');
		$iemail->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$fax=new Zend_Form_Element_Text('fax');
		$fax->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		
		$balance=new Zend_Form_Element_Text('balance');
		$balance->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$_id = new Zend_Form_Element_Hidden("id");
		
		$_desc = new Zend_Form_Element_Text('desc');
		$_desc->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
				));
		
		$_status=  new Zend_Form_Element_Select('status');
		$_status->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_title=  new Zend_Form_Element_Select('title');
		$_title->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;','class'=>'control_style'));
		$_status_opt = array(
				1=>$this->tr->translate("Mr."),
				2=>$this->tr->translate("Ms"),
				3=>$this->tr->translate("Miss")
		);
		$_title->setMultiOptions($_status_opt);
		
		$customer_type=  new Zend_Form_Element_Select('customer_type');
		$customer_type->setAttribs(array('style'=>'width: 100% !important;padding:1px !important;'));
		$_status_opt = array(
				1=>$this->tr->translate("Self"),
				2=>$this->tr->translate("Agency"),
		);
		$customer_type->setMultiOptions($_status_opt);
		
		$nationality = new Zend_Form_Element_Text('nationality');
		$nationality->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$company_name = new Zend_Form_Element_Text('company_name');
		$company_name->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$passport = new Zend_Form_Element_Text('passport');
		$passport->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$p_issuedate = new Zend_Form_Element_Text('pissue_date');
		$p_issuedate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$p_issuedate->setValue(date("Y-m-d"));
		
		$p_expireddate = new Zend_Form_Element_Text('pexpired_date');
		$p_expireddate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$p_expireddate->setValue(date("Y-m-d"));
		
		$card_code = new Zend_Form_Element_Text('card_code');
		$card_code->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$c_issuedate = new Zend_Form_Element_Text('cissue_date');
		$c_issuedate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$c_issuedate->setValue(date("Y-m-d"));
		
		$c_expireddate = new Zend_Form_Element_Text('cexpired_date');
		$c_expireddate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$c_expireddate->setValue(date("Y-m-d"));
		
		$ftb = new Zend_Form_Element_Text('ftb');
		$ftb->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$f_issuedate = new Zend_Form_Element_Text('fissue_date');
		$f_issuedate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$f_issuedate->setValue(date("Y-m-d"));
		
		$f_expireddate = new Zend_Form_Element_Text('fexpired_date');
		$f_expireddate->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		$f_expireddate->setValue(date("Y-m-d"));
		
		$address2 = new Zend_Form_Element_Text('address2');
		$address2->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$in_city = new Zend_Form_Element_Text('i_city');
		$in_city->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$in_province = new Zend_Form_Element_Text('country');
		$in_province->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$in_zipcode = new Zend_Form_Element_Text('i_zipcode');
		$in_zipcode->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$in_phone = new Zend_Form_Element_Text('i_phone');
		$in_phone->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		
		$in_note = new Zend_Form_Element_Text('i_note');
		$in_note->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$old_photo = new Zend_Form_Element_Hidden('old_photo');
		$ids = new Zend_Form_Element_Hidden('id');
		
		$loc_address= new Zend_Form_Element_Textarea("loc_add");
		
		$in_add = new Zend_Form_Element_Textarea("int_add");
		
		$fly_no = new Zend_Form_Element_Text("fly_no");
		$fly_no->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$fly_date = new Zend_Form_Element_Text("fly_date");
		$fly_date->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$fly_time = new Zend_Form_Element_Text("fly_time");
		$fly_time->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$fly_destination = new Zend_Form_Element_Text("fly_destination");
		$fly_destination->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$occupation = new Zend_Form_Element_Text('occupation');
		$occupation->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		$photo = new Zend_Form_Element_File('photo');
		$photo->setAttribs(array(
		));
		
		$fax=new Zend_Form_Element_Text('fax');
		$fax->setAttribs(array(
				'style'=>'width: 100% !important;padding:1px !important;',
				'class'=>'control_style'
		));
		
		
		if($data!=null){
			$home->setValue($data["house_num"]);
			$district->setValue($data["district"]);
			
			$ids->setValue($data['id']);
			$_title->setValue($data['title']);
			$_namekh->setValue($data['first_name']);
			$_nameen->setValue($data['last_name']);
			$_sex->setValue($data['sex']);
			$_clientno->setValue($data['customer_code']);
			$_dob->setValue(date("d-m-Y"),strtotime($data['dob']));
			$country->setValue($data['pob']);
			$nationality->setValue($data['nationality']);
		
			$company_name->setValue($data['company_name']);
			$_phone->setValue($data['phone']);
			$email->setValue($data['email']);
			
			$group_num->setValue($data['group_num']);
			$address->setValue($data['house_num']);
			$_street->setValue($data['street']);
			$commune->setValue($data['commune']);
			$province->setValue($data['province_id']);
			
			$address1->setValue($data["address1"]);
			$address2->setValue($data['address2']);
			$in_city->setValue($data['i_city']);
			$state->setValue($data['i_state']);
			$in_zipcode->setValue($data['i_zipcode']);
			$icommune->setValue($data['i_zipcode']);
			$in_phone->setValue($data['i_phone']);
			$city->setValue($data['i_city']);
			$fax->setValue($data['fax']);
			
			$national_id->setValue($data['nationality']);
			$occupation->setValue($data['occupation']);
			

		}
		$this->addElements(array($state,$fax,$photo,$occupation,$fly_date,$fly_destination,$fly_no,$fly_time,$iemail,$city,$icommune,$address1,$i_group_num,$home,$loc_address,$in_add,$old_photo,$ids,$in_note,$in_phone,$in_zipcode,$in_province,$in_city,$address2,$customer_type,$commune,$district,$province,$p_issuedate,$p_expireddate,$c_issuedate,$c_expireddate,$f_issuedate,$f_expireddate,$passport,$card_code,$ftb,$company_name,$nationality,$_title,$balance,$fax,$email,$group_num,$country,$_id,$photo,$job,$national_id,$_namekh,$_nameen,$_sex,$_situ_status,
				$_street,$_id_type,$address,$_phone,$_desc,$_status,$_clientno,$_dob));
		return $this;
	}
	
}

