<?php
class Application_Form_FrmBook extends Zend_Dojo_Form{
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
	public function getFromBooking(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_db = new Application_Model_DbTable_DbGlobal();
		$rows = $_db->getGlobalDb("SELECT * FROM `ldc_locationtype` WHERE `status` = 1");
		
		$opt_location = array(0=>$this->tr->translate("CHOOSE_LOCTION"));
		if(!empty($rows)){
			foreach($rows AS $row) {$opt_location[$row['id']]=$row['title'];};
		}
		$location = new Zend_Dojo_Form_Element_FilteringSelect('location');
		$location->setAttribs(array(
				'dojoType'=>$this->filter,'class'=>'css-dropdowns'
				));		
		$location->setMultiOptions($opt_location);
		
		$relocation = new Zend_Dojo_Form_Element_FilteringSelect('relocation');
		$opt_relocation = array(0=>$this->tr->translate("CHOOSE_LOCTION"));
		foreach($rows AS $rowre){$opt_relocation[$rowre['id']]=$rowre['title'];};
		$relocation->setMultiOptions($opt_relocation);
		$relocation->setAttribs(array(
				'dojoType'=>$this->filter,'class'=>'css-dropdowns'
		));
		
		$pdate = new Zend_Dojo_Form_Element_DateTextBox('pdate');
		$pdate->setAttribs(array(
				'dojoType'=>$this->date,'class'=>'fullside'
		));
		$pdate->setValue(date("d-m-Y"));
		
		$redate = new Zend_Dojo_Form_Element_DateTextBox('redate');
		$redate->setAttribs(array(
				'dojoType'=>$this->date,'class'=>'fullside'
		));
		$ptime = new Zend_Dojo_Form_Element_TimeTextBox('ptime');
		$ptime->setAttribs(array(
				'class'=>'full-width'
		));
		$retime = new Zend_Dojo_Form_Element_TimeTextBox('retime');
		$retime->setAttribs(array(
				'class'=>'full-width'
		));
		
		$rent_day = new Zend_Dojo_Form_Element_TextBox('rent_day');
		$rent_day->setAttribs(array(
				'class'=>'full-width'
		));
		$vehicle = new Zend_Dojo_Form_Element_FilteringSelect('vehicle');
		$opt_vehicle = array(0=>$this->tr->translate("CHOOSE_LOCTION"));
		foreach($rows AS $rowre){$opt_vehicle[$rowre['id']]=$rowre['title'];};
		$vehicle->setMultiOptions($opt_vehicle);
		$vehicle->setAttribs(array(
				'dojoType'=>$this->filter,'class'=>'css-dropdowns'
		));
		
		$vehicle_rental = new Zend_Dojo_Form_Element_TextBox('vehicle_rental');
		$vehicle_rental->setAttribs(array(
				'class'=>'full-width'
		));
		
		$driver_free = new Zend_Dojo_Form_Element_TextBox('driver_free');
		$driver_free->setAttribs(array(
				'class'=>'full-width'
		));
		$driver_lang = new Zend_Dojo_Form_Element_TextBox('driver_lang');
		$driver_lang->setAttribs(array(
				'class'=>'full-width'
		));
		
		$driver_free2 = new Zend_Dojo_Form_Element_TextBox('driver_free2');
		$driver_free2->setAttribs(array(
				'class'=>'full-width'
		));
		$driver_lang2 = new Zend_Dojo_Form_Element_TextBox('driver_lang2');
		$driver_lang2->setAttribs(array(
				'class'=>'full-width'
		));
		$tour = new Zend_Dojo_Form_Element_TextBox('tour');
		$tour->setAttribs(array(
				'class'=>'full-width'
		));
		$tour2 = new Zend_Dojo_Form_Element_TextBox('tour2');
		$tour2->setAttribs(array(
				'class'=>'full-width'
		));
		$tour_lang = new Zend_Dojo_Form_Element_TextBox('tour_lang');
		$tour_lang->setAttribs(array(
				'class'=>'full-width'
		));
		$tour_lang2 = new Zend_Dojo_Form_Element_TextBox('tour_lang2');
		$tour_lang2->setAttribs(array(
				'class'=>'full-width'
		));
		$extra_house = new Zend_Dojo_Form_Element_TextBox('extra_house');
		$extra_house->setAttribs(array(
				'class'=>'full-width'
		));
		$sunday = new Zend_Dojo_Form_Element_TextBox('sunday');
		$sunday->setAttribs(array(
				'class'=>'full-width'
		));
		$official_holiday = new Zend_Dojo_Form_Element_TextBox('official_holiday');
		$official_holiday->setAttribs(array(
				'class'=>'full-width'
		));
		$over_time = new Zend_Dojo_Form_Element_TextBox('over_time');
		$over_time->setAttribs(array(
				'class'=>'full-width'
		));
		$refundable = new Zend_Dojo_Form_Element_TextBox('refundable');
		$refundable->setAttribs(array(
				'class'=>'full-width'
		));
		$vat = new Zend_Dojo_Form_Element_TextBox('vat');
		$vat->setAttribs(array(
				'class'=>'full-width'
		));
		$this->addElements(array($location,$relocation,$pdate,$redate,$ptime,
				$retime,$rent_day,$vehicle,$vehicle_rental,$driver_free,$driver_lang,$driver_free2,$driver_lang2,
				$tour,$tour2,$tour_lang,$tour_lang2,$extra_house,$sunday,$official_holiday,$over_time,$refundable,$vat));
		return $this;
	}
	
}

