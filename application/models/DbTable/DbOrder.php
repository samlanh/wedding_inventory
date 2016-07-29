<?php

class Application_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_order';
    
    /**
     * Get current rate 
     * @return array(6);
     */
    function getOrderhistorydetail($id){
    	$db = $this->getAdapter();
    	$sql="SELECT c.id,(SELECT (SELECT b.measure_name FROM `ldc_measure` AS b WHERE b.id=a.unit LIMIT 1) AS unit FROM `ldc_product` AS a WHERE a.id = c.`pro_id` LIMIT 1) AS unit,(SELECT b.discount_login FROM `ldc_order` AS b WHERE b.id = c.`order_id`) AS dicount_login,(SELECT b.grand_total FROM `ldc_order` AS b WHERE b.id = c.`order_id`) AS grand_total,(SELECT b.invoice_no FROM `ldc_order` AS b WHERE b.id = c.`order_id`) AS invoice_no,(SELECT a.pro_no FROM `ldc_product` AS a WHERE a.id = c.`pro_id` LIMIT 1) AS pro_no,(SELECT a.pro_name FROM `ldc_product` AS a WHERE a.id = c.`pro_id` LIMIT 1) AS pro_name,(SELECT a.img_front FROM `ldc_product` AS a WHERE a.id = c.`pro_id` LIMIT 1) AS img_front,c.`price`,c.`qty`,c.`discount_percent`,c.`amount`,(SELECT b.grand_total FROM `ldc_order` AS b WHERE b.id = c.`order_id`) AS total,c.`order_date`, c.`pro_id` FROM `ldc_order_detail` AS c WHERE c.`order_id`=".$id;
    	$order = " ORDER BY c.`id` DESC ";
    	return $db->fetchAll($sql.$order);
    }
    function getOrderhistory($cus_id){
    	$db = $this->getAdapter();
    	$sql="SELECT p.`id`,p.`invoice_no`,p.`order_date`,p.discount_login,p.`grand_total` FROM `ldc_order` AS p WHERE p.`cus_id`=".$cus_id;
    	$order = " ORDER BY p.`id` DESC ";
    	return $db->fetchAll($sql.$order);
    }
    function getOrderInvoice($id){
    	$db = $this->getAdapter();
    	$sql='SELECT p.`id`,p.`invoice_no`,p.`order_date`,p.`grand_total`,(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.`type`=9 AND v.key_code =c.title LIMIT 1)," ",c.first_name," ",c.last_name) FROM `ldc_customers` AS c WHERE c.id=p.`cus_id`)AS `name`,(SELECT c.email FROM `ldc_customers` AS c WHERE c.id=p.`cus_id`)AS mail ,(SELECT c.branch_name FROM `ldc_customers` AS c WHERE c.id=p.`cus_id`)AS branch,(SELECT c.phone FROM `ldc_customers` AS c WHERE c.id=p.`cus_id`)AS phone,p.`discount_login` FROM `ldc_order` AS p WHERE p.id='.$id;
    	return $db->fetchRow($sql);
    }
    
 	

}