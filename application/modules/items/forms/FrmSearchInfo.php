<?php 
Class Items_Form_FrmSearchInfo extends Zend_Dojo_Form{
	protected $tr = null;
	protected $btn =null;//text validate
	protected $filter = null;
	protected $text =null;
	protected $validate = null;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->btn = 'dijit.form.Button';
		$this->validate = 'dijit.form.ValidationTextBox';
	}
	public function FrmDepartment($_data=null){
		$request=Zend_Controller_Front::getInstance()->getRequest();
	
		$_title = new Zend_Dojo_Form_Element_TextBox('title');
		$_title->setAttribs(array('dojoType'=>$this->text,'class'=>'fullside', 
				'placeholder'=>$this->tr->translate("Search")));
		$_title->setValue($request->getParam("title"));
	
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside' ));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL_STATUS"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status_search"));
		
		//supplier company 
		$sup_company=  new Zend_Dojo_Form_Element_FilteringSelect('sup_company');
		$sup_company->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside' ));
		$sup_com = array(
				-1=>$this->tr->translate("Select Company"),
				    );
		$sup_company->setValue($request->getParam("sup_company"));
		$db_com=new Group_Model_DbTable_DbClient();
		$rows=$db_com->getSupCompany();
		if(!empty($rows)) foreach ($rows As $rs) $sup_com[$rs['id']]=$rs['name'];
		$sup_company->setMultiOptions($sup_com);
		
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
	
		$this->addElements(array($sup_company,$_title,$start_date,$end_date,$_status));
	
		return $this;
	}
	
}