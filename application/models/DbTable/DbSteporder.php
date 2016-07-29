<?php

class Application_Model_DbTable_DbSteporder extends Zend_Db_Table_Abstract
{
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
	function createSessionStepOrdering($step=1){
		$session =new Zend_Session_Namespace('ordering');
		if($step==1){
				
			$session->point_step=1;
			$session->step1=1;
			$session->step2 = 0;

		}
		elseif($step==2){
			$session->point_step=2;
			$session->step2 =1;
			
		}
		return true;
	}
}
?>