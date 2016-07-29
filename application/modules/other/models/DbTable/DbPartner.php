<?php

class Other_Model_DbTable_DbPartner extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_partner';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addPartner($_data){
    	
    	$img=str_replace(" ", "_", $_data['title']) . '.jpg';
    	$upload=new Zend_File_Transfer();
    	$a=$upload->addFilter('Rename',
    			array('target'=>PUBLIC_PATH .'/images/partner/'.$img,'overwrite'=>true));
    	//print_r($a);exit();
    	$recieve=$upload->receive();
    	if($recieve){
    		$imgs=$img;
    	}
    	else{
    		$imgs="";
    	}
    	$_arr = array(
    			'title'=>$_data['title'],
    			'image_partner'=>$imgs,
    			'status'=>$_data['status'],
    			'footer_show'=>$_data['footer_show'],
    			'user_id'=>$this->getUserId()
    			);
    	$this->insert($_arr);
    }
    function updatePartner($_data){
    	$img=str_replace(" ", "_", $_data['title']) . '.jpg';
    	$upload=new Zend_File_Transfer();
    	$a=$upload->addFilter('Rename',
    			array('target'=>PUBLIC_PATH .'/images/partner/'.$img,'overwrite'=>true));
    	$recieve=$upload->receive();
    	if($recieve){
    		$imgs=$img;
    	}
    	else{
    		$imgs=$_data['old_photo'];
    	}
    	$_arr = array(
    			'title'=>$_data['title'],
    			'image_partner'=>$imgs,
    			'status'=>$_data['status'],
    			'footer_show'=>$_data['footer_show'],
    			'user_id'=>$this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);//insert data
    }
    
    function getAllPartner($search=null){
    	$db = $this->getAdapter();
    	$sql = "SELECT id ,title,image_partner,
    	(SELECT name_en FROM `ldc_view` WHERE TYPE=10 AND key_code =ldc_partner.`footer_show` limit 1  ) AS footer_show,
    	(SELECT name_en FROM `ldc_view` WHERE TYPE=2 AND key_code =ldc_partner.`footer_show` limit 1  ) AS status
    	FROM `ldc_partner` WHERE 1 ";
    	 
    	$where="";
    	 
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['adv_search']);
    		$s_where[]=" title LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ',$s_where).')';
    	}
    	$order=' ORDER BY id ASC';
    	return $db->fetchAll($sql.$where.$order);
    }
    function getPartnerById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT *  FROM $this->_name ";
    	$sql.= " WHERE id= $id";
   	return $db->fetchRow($sql);
    }

    function getAllPartnerToFooter(){
    	$db= $this->getAdapter();
    	$sql="SELECT *  FROM $this->_name  where status=1 AND footer_show=1 ORDER BY id ASC ";
    	return $db->fetchAll($sql);
    }
}  
	  

