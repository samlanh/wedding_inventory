<?php

class Other_Model_DbTable_DbSlideshow extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_slider';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    function addsledeshow($_data){
    	if(@$_data['is_showcaption']=="" or @$_data['is_showcaption']==null){
		$is_showcaption=0;    		
    	}else{
    		$is_showcaption=1;
    	}
    	$img=str_replace(" ", "_", $_data['title']) . '.jpg';
    	$upload=new Zend_File_Transfer();
    	$a=$upload->addFilter('Rename',
    			array('target'=>PUBLIC_PATH .'/images/slideshow/'.$img,'overwrite'=>true));
    	//print_r($a);exit();
    	$recieve=$upload->receive();
    	if($recieve){
//     		print_r($recieve);exit();
    		$imgs=$img;
    	}
    	else{
    		$imgs="";
    	}
    	$_arr = array(
    			'title'=>$_data['title'],
    			'ordering'=>$_data['ordering'],
    			'img'=>$imgs,
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId()
    			);
    	$this->insert($_arr);//insert data
    }
    function updateSlideshow($_data){
    	if(@$_data['is_showcaption']=="" or @$_data['is_showcaption']==null){
    		$is_showcaption=0;
    	}else{
    		$is_showcaption=1;
    	}
    	$img=str_replace(" ", "_", $_data['title']) . '.jpg';
    	$upload=new Zend_File_Transfer();
    	$a=$upload->addFilter('Rename',
    			array('target'=>PUBLIC_PATH .'/images/slideshow/'.$img,'overwrite'=>true));
    	$recieve=$upload->receive();
    	if($recieve){
    		$imgs=$img;
    	}
    	else{
    		$imgs=$_data['old_photo'];
    	}
    	$_arr = array(
    			'title'=>$_data['title'],
    			'ordering'=>$_data['ordering'],
    			'img'=>$imgs,
    			'status'=>$_data['status'],
    			'user_id'=>$this->getUserId()
    	);
    	$where=$this->getAdapter()->quoteInto("id=?", $_data['id']);
    	$this->update($_arr, $where);//insert data
    }
    
//     $photoname = str_replace(" ", "_", $post['txt_name']) . '.jpg';
//     $upload = new Zend_File_Transfer();
//     $upload->addFilter('Rename',
//     		array('target' => PUBLIC_PATH . '/images/fi-upload/'. $photoname, 'overwrite' => true) ,'photo');
//     $receive = $upload->receive();
//     if($receive)
//     {
//     	$post['photo'] = $photoname;
//     }
    
    function getAllSLIDE($search=null){
    	$db = $this->getAdapter();
    	$sql = "SELECT id ,title,ordering,img,
    	(SELECT name_en FROM `ldc_view` WHERE type=2 AND key_code =ldc_slider.`status`  ) AS status,is_showcaption
    	FROM `ldc_slider` WHERE 1 ";
    	 
    	$where="";
    	 
    	if(!empty($search['title'])){
    		$s_where=array();
    		$s_search=addslashes($search['adv_search']);
    		$s_where[]=" title LIKE '%{$s_search}%'";
    		$s_where[]=" caption LIKE '%{$s_search}%'";
    		$s_where[]=" ordering LIKE '%{$s_search}%'";
    		$where.=' AND ('.implode(' OR ',$s_where).')';
    	}
    	$order=' ORDER BY ordering ASC';
    	return $db->fetchAll($sql.$where.$order);
    }
    function getSlidshowById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT *  FROM $this->_name ";
    	$sql.= " WHERE id= $id";
   	return $db->fetchRow($sql);
    }

}  
	  

