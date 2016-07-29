<?php

class Application_Model_DbTable_DbSlide extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_slider';
    
    /**
     * Get current rate 
     * @return array(6);
     */
    function getSlideImage(){
    	$db = $this->getAdapter();
    	$sql='SELECT p.`id`,p.`title`,p.`img`,p.`ordering`,p.`is_showcaption` FROM `ldc_slider` AS p WHERE p.`status`=1 ORDER BY p.`ordering` ASC';
    	return $db->fetchAll($sql);
    }
}


