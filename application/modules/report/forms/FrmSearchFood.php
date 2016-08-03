<?php 
Class Report_Form_FrmSearchFood extends Zend_Dojo_Form {
	protected $tr;
	protected $tvalidate ;//text validate
	protected $filter;
	protected $text;
	protected $date;
	protected $tarea=null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->date = 'dijit.form.DateTextBox';
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	public function FrmSearchInfo(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$advance_search = new Zend_Dojo_Form_Element_DateTextBox("adv_search");
		$advance_search->setValue($request->getParam("adv_search"));
		$advance_search->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside',));
		 //customer infor 
		$_customer=  new Zend_Dojo_Form_Element_FilteringSelect('customer');
		$_customer->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_cus_opt = array(''=>$this->tr->translate("Select Customer Name..."));
		$cus=new Report_Model_DbTable_DbCustomer();
		$cus_rows=$cus->getCustomer();
		if(!empty($cus_rows)) foreach ($cus_rows As $cus_rs)$_cus_opt[$cus_rs['id']]=$cus_rs['name'];
		$_customer->setMultiOptions($_cus_opt);
		$_customer->setValue($request->getParam("customer"));
		
		///cotroll search food 
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
		//date
		$cat_food = new Zend_Dojo_Form_Element_FilteringSelect("cat_food");
		$cat_food->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect',
									'class'=>'fullside',
									'placeholder'=>$this->tr->translate("CATEGORY_FOOD")
				               ));
		$db_cat=new Report_Model_DbTable_DbFoodGategory();
		$row=$db_cat->getCategoryFood();
		$opt_cat=array(''=>$this->tr->translate("CATEGORY_FOOD"),);
		if(!empty($row)) foreach ($row as $rs)$opt_cat[$rs['id']]=$rs['name_kh'];
		$cat_food->setMultiOptions($opt_cat);
		
		$cat_food->setValue($request->getParam("cat_food"));
		 
		//date
		$start_date= new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$dates = date("Y-m-d");
		$start_date->setAttribs(array(
				'dojoType'=>"dijit.form.DateTextBox",
				'class'=>'fullside',
				'required'=>false));
		$_date = $request->getParam("start_date");
		if(empty($_date)){
			$_date = date('Y-m-d');
		}
		$start_date->setValue($_date);
		
		$end_date= new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$date = date("Y-m-d");
		$end_date->setAttribs(array(
				'dojoType'=>"dijit.form.DateTextBox",
				'class'=>'fullside',
				'required'=>false));
		$_date = $request->getParam("end_date");
		if(empty($_date)){
			$_date = date("Y-m-d");
		}
		$end_date->setValue($_date);
		
		$this->addElements(array($_customer,$cat_food,$_status,$start_date,$end_date,$advance_search));
		return $this;
		
	}
	
}