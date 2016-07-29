<?php

class Booking_Model_DbTable_DbOrder extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_order';
    function getAllCustomer(){
    	$db= $this->getAdapter();
    	$sql= 'SELECT p.id,CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = p.`title` LIMIT 1)," ",p.`first_name`," ",p.`last_name`," (",p.`customer_code`,")")AS `name` FROM `ldc_customers`AS p where p.status=1 AND p.cus_type=1';
    	return $db->fetchAll($sql);
    }
    public function getAllBranch(){
    	$db = $this->getAdapter();
    	$sql = 'SELECT p.`id`,CONCAT(p.`branch_name`," (",(SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = p.`title` LIMIT 1)," ",p.`first_name`," ",p.`last_name`,") " )AS `name` FROM `ldc_customers` AS p WHERE p.`cus_type`= 2 AND p.`status`=1';
    	return $db->fetchAll($sql);
    }
//     public function getAllBranchByID($id){
//     	$db = $this->getAdapter();
//     	$sql = 'SELECT p.`id`,(SELECT a.title FROM `ldc_partner` AS a WHERE a.id =p.`company_id` ) AS company,CONCAT (p.`first_name`," ",p.`last_name`) AS `branch`,p.`phone`,p.`email`,p.`fax`,CONCAT (p.`commune`," , ", p.`district`," , ",(SELECT b.province_name FROM `ldc_province`AS b WHERE b.id = p.`province_id` LIMIT 1))  AS location, (SELECT b.province_name FROM `ldc_province`AS b WHERE b.id = p.`province_id` LIMIT 1) as province  FROM `ldc_customers` AS p WHERE p.`cus_type`= 2 AND p.id='.$id;
//     	return $db->fetchRow($sql);
//     }
    public function getCountProOrder($id){
    	$db= $this->getAdapter();
    	$sql = 'SELECT p.`hot_sale` FROM `ldc_product` AS p WHERE p.`id`='.$id;
    	return $db->fetchOne($sql);
    }
    function addCustomer($_data){
    	$db= $this->getAdapter();
    	//print_r($_data);exit();
    	if ($_data['type_cus']==1){
//     		$db = new Booking_Model_DbTable_DbOrder();
//     		$invoiceId = $db->getNewIvoiceID();
    		$_arr =array(
    				'invoice_no'=>$_data['reciept_no'],
    				'cus_id'=>$_data['cus_name'],
    				'discount_login'=>$_data['discount'],
    				'grand_total'=>$_data['grand_total'],
    				'order_date'=>$_data['order_date'],
    				'book'=>$_data['total_received'],
    				'balance_due'=>$_data['total_balance'],
    				'return'=>$_data['total_return'],
    				'pending'=>$_data['cus_pending'],
    				
    		);
    		$this->_name ='ldc_order';
    		$id= $this->insert($_arr);
    		 
    		 
    		$ids = explode(',', $_data['identity']);
    		foreach ($ids as $i){
    			$hotsale = $this->getCountProOrder($_data['item_name_'.$i]);
    			$cal = $hotsale+1;
    			$_arrr = array(
    					'hot_sale'=>$cal
    			);
    			$this->_name='ldc_product';
    			$where =$this->getAdapter()->quoteInto("id=?", $_data['item_name_'.$i]);
    			$this->update($_arrr, $where);
    		}
    		foreach ($ids as $i){
    			$arr = array(
    					'order_id'=>$id,
    					'order_date'=>$_data['order_date'],
    					'pro_id'=>$_data['item_name_'.$i],
    					'price'=>$_data['price'.$i],
    					'qty'=>$_data['qty'.$i],
    					//'discount_percent'=>$_data['discount'.$i],
    					'amount'=>$_data['amount'.$i]
    			);
    			$this->_name ='ldc_order_detail';
    			$this->insert($arr);
    		}
    	}else{
    		$_arr =array(
    				'invoice_no'=>$_data['br_reciept_no'],
    				'cus_id'=>$_data['branch_name'],
    				'discount_login'=>$_data['discount'],
    				'grand_total'=>$_data['grand_total'],
    				'order_date'=>$_data['br_order_date'],
    				'book'=>$_data['total_received'],
    				'balance_due'=>$_data['total_balance'],
    				'return'=>$_data['total_return'],
    				'cus_type'=>$_data['type_cus'],
    				'pending'=>$_data['branch_pending'],
    		);
    		$this->_name ='ldc_order';
    		$id= $this->insert($_arr);
    		 
    		 
    		$ids = explode(',', $_data['identity']);
    		foreach ($ids as $i){
    			$hotsale = $this->getCountProOrder($_data['item_name_'.$i]);
    			$cal = $hotsale+1;
    			$_arrr = array(
    					'hot_sale'=>$cal
    			);
    			$this->_name='ldc_product';
    			$where =$this->getAdapter()->quoteInto("id=?", $_data['item_name_'.$i]);
    			$this->update($_arrr, $where);
    		}
    		foreach ($ids as $i){
    			$arr = array(
    					'order_id'=>$id,
    					'order_date'=>$_data['br_order_date'],
    					'pro_id'=>$_data['item_name_'.$i],
    					'price'=>$_data['price'.$i],
    					'qty'=>$_data['qty'.$i],
    					//'discount_percent'=>$_data['discount'.$i],
    					'amount'=>$_data['amount'.$i]
    			);
    			$this->_name ='ldc_order_detail';
    			$this->insert($arr);
    		}
    	}
    	
    	
    }
    function updateCustomer($_data){
    	$db= $this->getAdapter();
    	//print_r($_data);exit();
    	if ($_data['type_cus']==1){
    		//     		$db = new Booking_Model_DbTable_DbOrder();
    		//     		$invoiceId = $db->getNewIvoiceID();
    		$_arr =array(
    				'invoice_no'=>$_data['reciept_no'],
    				'cus_id'=>$_data['cus_name'],
    				'discount_login'=>$_data['discount'],
    				'grand_total'=>$_data['grand_total'],
    				'order_date'=>$_data['order_date'],
    				'book'=>$_data['total_received'],
    				'balance_due'=>$_data['total_balance'],
    				'return'=>$_data['total_return'],
    				'pending'=>$_data['cus_pending'],
    				'status'=>$_data['cus_status'],
    
    		);
    		$this->_name ='ldc_order';
    		$where = 'id='.$_data['id'];
    		$id= $this->update($_arr, $where);
    		 
    		 
    		$ids = explode(',', $_data['identity']);
    		foreach ($ids as $i){
    			$hotsale = $this->getCountProOrder($_data['item_name_'.$i]);
    			$cal = $hotsale+1;
    			$_arrr = array(
    					'hot_sale'=>$cal
    			);
    			$this->_name='ldc_product';
    			$where =$this->getAdapter()->quoteInto("id=?", $_data['item_name_'.$i]);
    			$this->update($_arrr, $where);
    		}
    		
    		$this->_name ='ldc_order_detail';
    		$where ='order_id='.$_data['id'];
    		$this->delete($where);
    		
    		foreach ($ids as $i){
    			$arr = array(
    					'order_id'=>$_data['id'],
    					'order_date'=>$_data['order_date'],
    					'pro_id'=>$_data['item_name_'.$i],
    					'price'=>$_data['price'.$i],
    					'qty'=>$_data['qty'.$i],
    					//'discount_percent'=>$_data['discount'.$i],
    					'amount'=>$_data['amount'.$i]
    			);
    			$this->_name ='ldc_order_detail';
    			$this->insert($arr);
    		}
    	}else{
    		$_arr =array(
    				'invoice_no'=>$_data['br_reciept_no'],
    				'cus_id'=>$_data['branch_name'],
    				'discount_login'=>$_data['discount'],
    				'grand_total'=>$_data['grand_total'],
    				'order_date'=>$_data['br_order_date'],
    				'book'=>$_data['total_received'],
    				'balance_due'=>$_data['total_balance'],
    				'return'=>$_data['total_return'],
    				'cus_type'=>$_data['type_cus'],
    				'pending'=>$_data['branch_pending'],
    				'status'=>$_data['branch_status'],
    		);
    		$this->_name ='ldc_order';
    		$where = 'id='.$_data['id'];
    		$id= $this->update($_arr, $where);
    		 
    		 
    		$ids = explode(',', $_data['identity']);
    		foreach ($ids as $i){
    			$hotsale = $this->getCountProOrder($_data['item_name_'.$i]);
    			$cal = $hotsale+1;
    			$_arrr = array(
    					'hot_sale'=>$cal
    			);
    			$this->_name='ldc_product';
    			$where =$this->getAdapter()->quoteInto("id=?", $_data['item_name_'.$i]);
    			$this->update($_arrr, $where);
    		}
    		
    		$this->_name ='ldc_order_detail';
    		$where ='order_id='.$_data['id'];
    		$this->delete($where);
    		
    		foreach ($ids as $i){
    			$arr = array(
    					'order_id'=>$_data['id'],
    					'order_date'=>$_data['br_order_date'],
    					'pro_id'=>$_data['item_name_'.$i],
    					'price'=>$_data['price'.$i],
    					'qty'=>$_data['qty'.$i],
    					//'discount_percent'=>$_data['discount'.$i],
    					'amount'=>$_data['amount'.$i]
    			);
    			$this->_name ='ldc_order_detail';
    			$this->insert($arr);
    		}
    	}
    	 
    	 
    }
    function getOrderbyID($id){
    	$db= $this->getAdapter();
    	$sql= 'SELECT p.`id`,p.`order_date`,p.`invoice_no`,p.status,p.pending,p.discount_login,p.book,p.grand_total,p.balance_due,p.return,(SELECT b.phone FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS phone,(SELECT b.email FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS email,(SELECT (SELECT c.province_name FROM `ldc_province` AS c WHERE c.id=b.province_id) FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS province,p.`cus_id`,p.`cus_type` FROM `ldc_order` AS p WHERE p.`id`='.$id;
    	return $db->fetchRow($sql);
    }
    function getOrderDetailID($id){
    	$db= $this->getAdapter();
    	$sql= 'SELECT * FROM `ldc_order_detail` AS c WHERE c.`order_id`='.$id;
    	return $db->fetchAll($sql);
    }
    function getCusOrder($search){
    	$db= $this->getAdapter();
    	$date = date_create($search["from_date"]);
    	$from_date = date_format($date, "Y-m-d");
    	//print_r($from_date);
    	 
    	$todate = date_create($search["end_date"]);
    	$to_date = date_format($todate, "Y-m-d");
    	$where = " AND p.order_date BETWEEN '$from_date' AND '$to_date'";
    	//$where="";
    	$sql= 'SELECT p.`id`,p.`invoice_no`,(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = a.`title` LIMIT 1)," ", a.`first_name`," ",a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `name`,p.`order_date`,(SELECT (SELECT d.title FROM `ldc_partner` AS d WHERE d.id = a.company_id LIMIT 1) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `company`,(SELECT a.branch_name FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 )AS `branch`,p.`grand_total`,p.`book`,p.`balance_due`,p.`return`,(SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=11 AND v.key_code =p.`pending`) AS pending  FROM `ldc_order` AS p WHERE 1 ';
    	$order =" ORDER BY p.id DESC";
    	if(empty($search)){
    		return $db->fetchAll($sql.$where.$order);
    	
    	}
    	if ($search['company']>-1){
    		$where .=' AND (SELECT a.company_id FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) = '.$search['company'];
    	}
    	if ($search['pending']>-1){
    		$where .=' AND p.`pending` = '.$search['pending'];
    	}
    	if(!empty($search['search'])){
    		$s_where = array();
    		$s_search = addslashes(trim($search['search']));
    		$s_where[] = "(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = a.`title` LIMIT 1),' ',a.`first_name`,' ',a.`last_name`) FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) LIKE '%$s_search%'";
    		$s_where[] = "(SELECT a.branch_name FROM `ldc_customers` AS a WHERE a.id = p.`cus_id` LIMIT 1 ) LIKE '%$s_search%'";
    		$s_where[] = "p.`invoice_no` LIKE '%$s_search%'";
    		$where .=' AND ('.implode(' OR ',$s_where).')';
    	}
    	//echo $sql.$where.$order;
    	return $db->fetchAll($sql.$where.$order);
    }
    
    function getAllCustomerById($id){
    	$db= $this->getAdapter();
    	$sql= 'SELECT p.id,p.`branch_name`,p.`discount_login`,CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = p.`title` LIMIT 1)," ",p.`first_name`," ",p.`last_name`)AS `name`,p.house_num,p.street,(SELECT d.district_name FROM `ldc_district` AS d WHERE d.id = p.district) AS district,(SELECT k.province_name FROM `ldc_province` AS k WHERE k.id = p.province_id) AS province,p.`phone`,p.`email` FROM `ldc_customers`AS p WHERE p.id='.$id;
    	return $db->fetchRow($sql);
    }
    function getCustomerOrderInfoById($id){
    	$db= $this->getAdapter();
    	$sql= 'SELECT p.`id`,p.`order_date`,p.`discount_login`,p.`invoice_no`,p.pending,(SELECT CONCAT((SELECT v.name_en FROM `ldc_view` AS v WHERE v.type=9 AND v.key_code = b.`title` LIMIT 1)," ",b.first_name," ",b.last_name) FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS `name`,(SELECT b.phone FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS phone,(SELECT b.email FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS email,(SELECT b.branch_name FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS branch_name,(SELECT b.house_num FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS house_num,(SELECT b.street FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS street,(SELECT (SELECT c.district_name FROM `ldc_district` AS c WHERE c.id=b.district) FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS district,(SELECT (SELECT c.province_name FROM `ldc_province` AS c WHERE c.id=b.province_id) FROM `ldc_customers` AS b WHERE b.id=p.`cus_id`) AS province,p.`cus_id` FROM `ldc_order` AS p WHERE p.`cus_id`='.$id;
    	return $db->fetchRow($sql);
    }
    public function getNewIvoiceID(){
    	$this->_name='ldc_order';
    	$db = $this->getAdapter();
    
    	$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "IN-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
}  
	function getCategory(){
		$db= $this->getAdapter();
		$sql="SELECT id,title as name FROM ldc_make WHERE status=1";
		return $db->fetchAll($sql);
	}
	function getProduct(){
		$db= $this->getAdapter();
		$sql="SELECT id,pro_no,pro_name as name FROM ldc_product WHERE status=1";
		return $db->fetchAll($sql);
	}
	function getProductsByID($id){
		$db= $this->getAdapter();
		$sql="SELECT id,pro_no,pro_name as name ,price FROM ldc_product WHERE status=1 and id =".$id;
		return $db->fetchRow($sql);
	}
// 	function getAllProductsByID($id){
// 		$db= $this->getAdapter();
// 		$sql="SELECT id,pro_no,pro_name as name ,price,category_id FROM ldc_product WHERE status=1 and category_id =".$id;
// 		$rows=$db->fetchAll($sql);
// 		$options = '';
// 		if(!empty($rows))foreach($rows as $value){
// 			$options .= '<option value="'.$value['id'].'" >'.htmlspecialchars($value['name'], ENT_QUOTES).'</option>';
// 		}
// 		return $options;
// 	}
	public function getNewCustomerId(){
		$this->_name='ldc_customers';
		$db = $this->getAdapter();
		$sql=" SELECT id FROM $this->_name ORDER BY id DESC LIMIT 1 ";
		$acc_no = $db->fetchOne($sql);
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre = "CUS-";
		for($i = $acc_no;$i<4;$i++){
			$pre.='0';
		}
		return $pre.$new_acc_no;
	}
	
	function getAllCom(){
		$db =$this->getAdapter();
		$sql ='SELECT p.id,p.`title` FROM `ldc_partner` AS p WHERE p.`status`=1';
		return $db->fetchAll($sql);
	}
	function getAllprovince(){
		$db =$this->getAdapter();
		$sql ='SELECT p.id,p.`province_name` FROM `ldc_province` AS p WHERE p.`status`=1';
		return $db->fetchAll($sql);
	}
	function ajaxaddCustomer($data){
		$this->_name='ldc_customers';
		$db = $this->getAdapter();
		$arr = array(
				'customer_code'=>$data['txt_cus_id'],
				'title'=>$data['title'],
				'first_name'=>$data['first_cus_name'],
				'last_name'=>$data['last_cus_name'],
				'province_id'=>$data['province'],
				'district'=>$data['district'],
				'street'=>$data['street'],
				'house_num'=>$data['house_no'],
				'phone'=>$data['txt_phone'],
				'email'=>$data['txt_mail'],
				'status'=>1
		);
		return $this->insert($arr);
	}
	function ajaxaddBranch($data){
		$this->_name='ldc_customers';
		$db = $this->getAdapter();
		//$sql ='SELECT p.`title` FROM `ldc_partner` AS p WHERE p.`id`= '.$data['txtcom_name'];
		//$com_name = $db->fetchOne($sql);
		$arr = array(
				'customer_code'=>$data['txt_branch_id'],
				'title'=>$data['title_branch'],
				'first_name'=>$data['txt_first_cus_name'],
				'last_name'=>$data['txtlast_cus_name'],
				'branch_name'=>$data['txtbranch_name'],
				'district'=>$data['district_branch'],
				'province_id'=>$data['province_branch'],
				'street'=>$data['txtstreet'],
				'house_num'=>$data['txthouse_no'],
				'phone'=>$data['txtbranch_phone'],
				'email'=>$data['txtbranch_mail'],
				'cus_type'=>2,
				'company_id'=>$data['txtcom_name'],
				'status'=>1,
	
		);
		return $this->insert($arr);
	}
	public function getAllPartner(){
		$db = $this->getAdapter();
		$sql="SELECT p.`id`,p.`title` FROM `ldc_partner` AS p WHERE p.`status`=1";
		return $db->fetchAll($sql);
	}
}