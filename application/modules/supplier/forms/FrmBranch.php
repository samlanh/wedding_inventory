<?php 
Class Group_Form_FrmBranch extends Zend_Dojo_Form {
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
public function FrmAddBranch($data=null){
		
		$_dob= new Zend_Dojo_Form_Element_DateTextBox('dob_client');
		$_dob->setValue(date("Y-m-d"));
		$_dob->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','class'=>'fullside'));
		
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		
		$_namekh = new Zend_Dojo_Form_Element_TextBox('branch_name');
		$_namekh->setAttribs(array(
						'dojoType'=>'dijit.form.ValidationTextBox',
						'class'=>'fullside',
						'required' =>'true'
		));
		
		$_clientno = new Zend_Dojo_Form_Element_TextBox('client_no');
		$_clientno->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'style'=>'color:red;',
				'readOnly'=>true
		));
		$id_client = $db->getNewCustomerId();
		$_clientno->setValue($id_client);
	
		
		$_situ_status = new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_situ_status->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		
		$group_num = new Zend_Dojo_Form_Element_TextBox('group_num');
		$group_num->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$country = new Zend_Dojo_Form_Element_TextBox('country');
		$country->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$commune = new Zend_Dojo_Form_Element_TextBox('commune');
		$commune->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$district = new Zend_Dojo_Form_Element_TextBox('district');
		$district->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$province = new Zend_Dojo_Form_Element_FilteringSelect('province');
		$province->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt = $db->getAllProvince(1);
		$province->setMultiOptions($opt);
		
		$_street = new Zend_Dojo_Form_Element_TextBox('street');
		$_street->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
		));
		
		$_id_type = new Zend_Dojo_Form_Element_FilteringSelect('id_type');
		$_id_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'required' =>'true'
		));
		
		$address = new Zend_Dojo_Form_Element_TextBox('address');
		$address->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
		));
		
		$_phone = new Zend_Dojo_Form_Element_TextBox('phone');
		$_phone->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				'required' =>'true'
		));
		
		$photo=new Zend_Form_Element_File('photo');
		$photo->setAttribs(array(
		));
		
		$job = new Zend_Dojo_Form_Element_TextBox('job');
		$job->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$national_id=new Zend_Dojo_Form_Element_TextBox('national_id');
		$national_id->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				));
		
		$email=new Zend_Dojo_Form_Element_TextBox('email');
		$email->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				'required' =>'true'
		));
		
		$fax=new Zend_Dojo_Form_Element_TextBox('fax');
		$fax->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		

		$balance=new Zend_Dojo_Form_Element_TextBox('balance');
		$balance->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));

		$_id = new Zend_Form_Element_Hidden("id");
		
		$_desc = new Zend_Dojo_Form_Element_TextBox('desc');
		$_desc->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside',
				'style'=>'width:96%;min-height:30px;'));
		
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		
		$_title=  new Zend_Dojo_Form_Element_FilteringSelect('title');
		$_title->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		//$com_name = $db->getCompanyName();
// 		$opt_Company = array();
// 		if (!empty($com_name)){
// 			foreach($com_name as $com){
// 				$opt_Company[$com['id']=$com['title']];
// 			}
// 		}
// 		$_title->setMultiOptions($com_name);
		
		
		
		$nationality = new Zend_Dojo_Form_Element_TextBox('nationality');
		$nationality->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		
		$passport = new Zend_Dojo_Form_Element_TextBox('passport');
		$passport->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$p_issuedate = new Zend_Dojo_Form_Element_DateTextBox('pissue_date');
		$p_issuedate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$p_issuedate->setValue(date("Y-m-d"));
		
		$p_expireddate = new Zend_Dojo_Form_Element_DateTextBox('pexpired_date');
		$p_expireddate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$p_expireddate->setValue(date("Y-m-d"));
		
		$card_code = new Zend_Dojo_Form_Element_TextBox('card_code');
		$card_code->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$c_issuedate = new Zend_Dojo_Form_Element_DateTextBox('cissue_date');
		$c_issuedate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$c_issuedate->setValue(date("Y-m-d"));
		
		$c_expireddate = new Zend_Dojo_Form_Element_DateTextBox('cexpired_date');
		$c_expireddate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$c_expireddate->setValue(date("Y-m-d"));
		
		$ftb = new Zend_Dojo_Form_Element_TextBox('ftb');
		$ftb->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$f_issuedate = new Zend_Dojo_Form_Element_DateTextBox('fissue_date');
		$f_issuedate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$f_issuedate->setValue(date("Y-m-d"));
		
		$f_expireddate = new Zend_Dojo_Form_Element_DateTextBox('fexpired_date');
		$f_expireddate->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
		));
		$f_expireddate->setValue(date("Y-m-d"));
		
		$address1 = new Zend_Dojo_Form_Element_TextBox('address1');
		$address1->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$address2 = new Zend_Dojo_Form_Element_TextBox('address2');
		$address2->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$i_city = new Zend_Dojo_Form_Element_TextBox('i_city');
		$i_city->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$in_zipcode = new Zend_Dojo_Form_Element_TextBox('i_zipcode');
		$in_zipcode->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$state = new Zend_Dojo_Form_Element_TextBox('state');
		$state->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$in_phone = new Zend_Dojo_Form_Element_TextBox('i_phone');
		$in_phone->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		
		$icontry = new Zend_Dojo_Form_Element_FilteringSelect('countries');
		$icontry->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$row = $db->getAllCountry();
		$opt_country = array();
		if(!empty($row)){
			foreach ($row as $rs){
				$opt_country[$rs['id']]=$rs['country_name'];
			}
		}
		$icontry->setMultiOptions($opt_country);
		$id = new Zend_Dojo_Form_Element_TextBox('id');
		$id->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$occupation = new Zend_Dojo_Form_Element_TextBox('occupation');
		$occupation->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$dicount_login = new Zend_Dojo_Form_Element_NumberTextBox('discount_login');
		$dicount_login->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true
		));
		
		$old_photo = new Zend_Form_Element_Hidden('old_photo');
		
		if($data!=null){
			$dicount_login->setValue($data['discount_login']);
			$occupation->setValue($data['occupation']);
			$old_photo->setValue($data['photo']);
			$id->setValue($data['id']);
			$_title->setValue($data['title']);
			$_namekh->setValue($data['first_name']);
			$_dob->setValue($data['dob']);
			$country->setValue($data['pob']);
			$nationality->setValue($data['nationality']);
			$_desc->setValue($data['note']);
			$_clientno->setValue($data['customer_code']);
			
				
			$_phone->setValue($data['phone']);
			$email->setValue($data['email']);
			$fax->setValue($data['fax']);
			$group_num->setValue($data['group_num']);
			$address->setValue($data['house_num']);
			$_street->setValue($data['street']);
			$commune->setValue($data['commune']);
			$province->setValue($data['province_id']);
$district->setValue($data['district']);
			$_situ_status->setValue($data['status']);
		}
		$this->addElements(array($state,$occupation,$old_photo,$id,$icontry,$in_phone,$in_zipcode,$i_city,$address2,$address1,$commune,$district,$province,$p_issuedate,$p_expireddate,$c_issuedate,$c_expireddate,$f_issuedate,$f_expireddate,$passport,$card_code,$ftb,$nationality,$_title,$balance,$fax,$email,$group_num,$country,$_id,$photo,$job,$national_id,$_namekh,$_situ_status,
				$_street,$_id_type,$address,$dicount_login,$_phone,$_desc,$_status,$_clientno,$_dob));
		return $this;
		
	}
	
}