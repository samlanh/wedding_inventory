<?php

class Order_Model_DbTable_DbQuote extends Zend_Db_Table_Abstract
{

    protected $_name = 'ldc_quotation';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getQuoteID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM ldc_quotation GROUP BY quot_code ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "QO-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
    public function getOrderID(){
    	$db =$this->getAdapter();
    	$sql=" SELECT id FROM ldc_order GROUP BY order_code ORDER BY id DESC LIMIT 1 ";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	$pre = "OR-";
    	for($i = $acc_no;$i<4;$i++){
    		$pre.='0';
    	}
    	return $pre.$new_acc_no;
    }
    
    public function getQuoteMergeID(){
    	$db =$this->getAdapter();
    	$sql="SELECT q.`merge_id` FROM `ldc_quotation` AS q WHERE q.`merge_id` !='' GROUP BY q.`merge_id` ORDER BY q.`merge_id` DESC LIMIT 1";
    	$acc_no = $db->fetchOne($sql);
    	$new_acc_no= (int)$acc_no+1;
    	$acc_no= strlen((int)$acc_no+1);
    	return $new_acc_no;
    }
   public function getUnitOption(){
    	$db = $this->getAdapter();
    	$sql="SELECT p.id,CONCAT(p.`measure_name_kh`,' ',p.`measure_name_en`) as name FROM `ldc_measure` AS p WHERE p.`status`=1 ORDER BY p.`id`DESC";
    	return $db->fetchAll($sql);
    }
    
   	public function getFoodCat(){
   		$db = $this->getAdapter();
    	$sql="SELECT fc.`id`,fc.`name_kh` FROM `ldc_food_cat` AS fc WHERE fc.`status`=1";
    	return $db->fetchAll($sql);
   	}
    function addQuote($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		if($data['identity_wedding']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_wedding'],
    					'type'			=>	1,
    					'price'			=>	$data['t_price_wedding'],
    					'address'		=>	$data['address_wedding'],
    					'date_do'		=>	$data["date_wedding"],
    					'time_do'		=>	$data["time_wedding"],
    					'total_pay'		=>	$data["amount_wedding"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$id = $this->insert($arr);
    			
    			$ids = explode(',', $data['identity_wedding']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'	=>	$id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_in);
    			}
    		}
    		
    		if($data['identity_breakfast']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_breakfast'],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$id = $this->insert($arr);
    			 
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$id,
    						'food_id'=>$data['item_name_breakfast_'.$i],
    						'qty'=>$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_in);
    			}
    		}
    		
    		if($data['identity_lunch']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_lunch'],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$id = $this->insert($arr);
    		
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$id,
    						'food_id'=>$data['item_name_lunch_'.$i],
    						'qty'=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_in);
    			}
    		}
	    	 
    		if($data['identity_dinner']){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$id = $this->insert($arr);
    		
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$id,
    						'food_id'=>$data['item_name_dinner_'.$i],
    						'qty'=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_in);
    			}
    		}
	    		
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	}
    }
    
    function addQuoteNew($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$arr =array(
    				'quot_code'		=>	$data['qutoe_code'],
    				'customer_id'	=>	$data['customer_name'],
    				'ceremony_id'	=>	$data["ceremony_date"],
    				'total_pay'		=>	$data["amount_wedding"]+$data["amount_breakfast"]+$data["amount_lunch"]+$data["amount_dinner"]+$data["amount_service"],
    				'status'		=>	$data['status'],
    		);
    		$this->_name ="ldc_quotation";
    		$id = $this->insert($arr);
    		
    		if($data['identity_wedding']!=""){
    			
    				$arr_in = array(
    						'quote_id'		=>	$id,
    						'num_table'		=>	$data['t_number_wedding'],
    						'title'			=>	$data['title_wedding'],
    						'label'			=>	$data['label_wedding'],
    						'is_free'		=>	$data["is_free_wedding"],
    						'free'			=>	$data["free_wedding"],
    		    			'type'			=>	1,
    		    			'price'			=>	$data['t_price_wedding'],
    		    			'address'		=>	$data['address_wedding'],
    		    			'date_do'		=>	$data["date_wedding"],
    		    			'time_do'		=>	$data["time_wedding"],
    						'total_pay'		=>	$data["amount_wedding"]
    				);
    				$this->_name ='ldc_quotation_connection';
    				$qc_id = $this->insert($arr_in);
    			
    			$ids = explode(',', $data['identity_wedding']);
    			
    			
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>	$qc_id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'cat_id'	=>	$data['food_cat_name_wedding_'.$i],
    						//'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_wedding_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_wedding"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    							'quote_id'		=> $id,
    							'qc_id'			=>	$qc_id,
    							'type'			=>	1,
    							'food_id'		=>	$data['item_name_wedding_'.$i],
    							'item_id'		=>	$row_ind["item_id"],
    							'qty'			=>	$row_ind["qty"],
    							'su_id'			=>	$row_ind["su_id"],
    							'measure_id'	=>	$row_ind["measure_id"],
    							'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
//     						$db->getProfiler()->setEnabled(true);
    						$this->insert($arr_d);
//     						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     						$db->getProfiler()->setEnabled(false);
    					}
    				}
    			}
    		}
    
    		if($data['identity_breakfast']!=""){
    			$arr_in = array(
    					'quote_id'		=>	$id,
    					'num_table'		=>	$data['t_number_breakfast'],
    					'title'		=>	$data['title_breakfast'],
    					'label'		=>	$data['label_breakfast'],
    					'is_free'		=>	$data["is_free_breakfast"],
    					'free'			=>	$data["free_breakfast"],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>	$qc_id,
    						'food_id'	=>	$data['item_name_breakfast_'.$i],
    						'cat_id'	=>	$data['food_cat_name_breakfast_'.$i],
    						//'qty'		=>	$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_breakfast_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_breakfast"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $id,
    								'qc_id'			=>	$qc_id,
    								'type'			=>	2,
    								'food_id'		=>	$data['item_name_breakfast_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    
    		if($data['identity_lunch']!=""){
    			
    			$arr_in = array(
    					'quote_id'		=>	$id,
    					'num_table'		=>	$data['t_number_lunch'],
    					'title'			=>	$data['title_lunch'],
    					'label'			=>	$data['label_lunch'],
    					'is_free'		=>	$data["is_free_lunch"],
    					'free'			=>	$data["free_lunch"],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'		=>$data['item_name_lunch_'.$i],
    						'cat_id'	=>	$data['food_cat_name_lunch_'.$i],
    						//'qty'			=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_lunch_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_lunch"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $id,
    								'qc_id'			=>	$qc_id,
    								'type'			=>	3,
    								'food_id'		=>	$data['item_name_lunch_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    		 
    		if($data['identity_dinner']){
    			
    			$arr_in = array(
    					'quote_id'		=>	$id,
    					'num_table'		=>	$data['t_number_dinner'],
    					'title'			=>	$data['title_dinner'],
    					'label'			=>	$data['label_dinner'],
    					'is_free'		=>	$data["is_free_lunch"],
    					'free'			=>	$data["free_lunch"],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'	=>$data['item_name_dinner_'.$i],
    						'cat_id'	=>	$data['food_cat_name_dinner_'.$i],
    						//'qty'		=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_dinner_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_dinner"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $id,
    								'qc_id'			=>	$qc_id,
    								'type'			=>	4,
    								'food_id'		=>	$data['item_name_dinner_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    		 
    		if($data['identity_service']){
    		
    			$arr_in = array(
    					'quote_id'		=>	$id,
    					//'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	5,
    					//'price'			=>	$data['t_price_dinner'],
    					//'address'		=>	$data['address_dinner'],
    					//'date_do'		=>	$data["date_dinner"],
    					//'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_service"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    		
    			$ids = explode(',', $data['identity_service']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'	=>$data['item_name_ser_'.$i],
    						'qty'		=>$data['qty_ser_'.$i],
    						'price'		=>$data['qty_ser_'.$i],
    						'note'		=>$data['qty_ser_'.$i],
    		
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    			}
    		}
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	}
    }
    
    function updateQuoteNew($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$arr =array(
    				//'quot_code'		=>	$data['qutoe_code'],
    				'customer_id'	=>	$data['customer_name'],
    				'ceremony_id'	=>	$data["ceremony_date"],
    				'total_pay'		=>	$data["amount_wedding"]+$data["amount_breakfast"]+$data["amount_lunch"]+$data["amount_dinner"]+$data["amount_service"],
    				'status'		=>	$data['status'],
    		);
    		$this->_name ="ldc_quotation";
    		$where = $db->quoteInto("id=?", $data["id"]);
    		$this->update($arr, $where);
    		
    		$sql_con = "DELETE FROM ldc_quotation_connection WHERE quote_id=".$data['id'];
    		$db->query($sql_con);
    		
    		if($data['id_wedding']){
	    		$sql_we = "DELETE FROM ldc_quotation_detail WHERE qc_id=".$data['id_wedding'];
	    		$db->query($sql_we);
    		}
    		
    		if($data['id_breakfast']){
	    		$sql_bf = "DELETE FROM ldc_quotation_detail WHERE qc_id=".$data['id_breakfast'];
	    		$db->query($sql_bf);
    		}
    		
    		if($data['id_lunch']){
	    		$sql_lu = "DELETE FROM ldc_quotation_detail WHERE qc_id=".$data['id_lunch'];
	    		$db->query($sql_lu);
    		}
    		
    		if($data['id_dinner']){
	    		$sql_di = "DELETE FROM ldc_quotation_detail WHERE qc_id=".$data['id_dinner'];
	    		$db->query($sql_di);
    		}
    		
    		if($data['id_service']){
    			$sql_ds = "DELETE FROM ldc_quotation_detail WHERE qc_id=".$data['id_service'];
    			$db->query($sql_ds);
    		}
    		
    		$sql = "DELETE FROM ldc_quote_item WHERE quote_id=".$data["id"];
    		$db->query($sql);
    
    	if($data['identity_wedding']!=""){
    			
    				$arr_in = array(
    						'quote_id'		=>	$data["id"],
    						'num_table'		=>	$data['t_number_wedding'],
    		    			'type'			=>	1,
    		    			'price'			=>	$data['t_price_wedding'],
    		    			'address'		=>	$data['address_wedding'],
    		    			'date_do'		=>	$data["date_wedding"],
    		    			'time_do'		=>	$data["time_wedding"],
    						'total_pay'		=>	$data["amount_wedding"]
    				);
    				$this->_name ='ldc_quotation_connection';
    				$qc_id = $this->insert($arr_in);
    			
    			$ids = explode(',', $data['identity_wedding']);
    			
    			
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>	$qc_id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'cat_id'	=>	$data['food_cat_name_wedding_'.$i],
    						//'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_wedding_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_wedding"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    							'quote_id'		=> $data["id"],
    							'qc_id'			=>	$qc_id,
    							'type'			=>	1,
    							'food_id'		=>	$data['item_name_wedding_'.$i],
    							'item_id'		=>	$row_ind["item_id"],
    							'qty'			=>	$row_ind["qty"],
    							'su_id'			=>	$row_ind["su_id"],
    							'measure_id'	=>	$row_ind["measure_id"],
    							'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
//     						$db->getProfiler()->setEnabled(true);
    						$this->insert($arr_d);
//     						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     						Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     						$db->getProfiler()->setEnabled(false);
    					}
    				}
    			}
    		}
    
    		if($data['identity_breakfast']!=""){
    			$arr_in = array(
    					'quote_id'		=>	$data["id"],
    					'num_table'		=>	$data['t_number_breakfast'],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>	$qc_id,
    						'food_id'	=>	$data['item_name_breakfast_'.$i],
    						'cat_id'	=>	$data['food_cat_name_breakfast_'.$i],
    						//'qty'		=>	$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_breakfast_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_breakfast"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $data["id"],
    								'qc_id'			=>	$qc_id,
    								'type'			=>	2,
    								'food_id'		=>	$data['item_name_breakfast_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    
    		if($data['identity_lunch']!=""){
    			
    			$arr_in = array(
    					'quote_id'		=>	$data["id"],
    					'num_table'		=>	$data['t_number_lunch'],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'		=>$data['item_name_lunch_'.$i],
    						'cat_id'	=>	$data['food_cat_name_lunch_'.$i],
    						//'qty'			=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_lunch_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_lunch"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $data["id"],
    								'qc_id'			=>	$qc_id,
    								'type'			=>	3,
    								'food_id'		=>	$data['item_name_lunch_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    		 
    		if($data['identity_dinner']){
    			
    			$arr_in = array(
    					'quote_id'		=>	$data["id"],
    					'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    			
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'	=>$data['item_name_dinner_'.$i],
    						'cat_id'	=>	$data['food_cat_name_dinner_'.$i],
    						//'qty'		=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    				
    				$row_food_ind = $this->getFoodIngrediant($data['item_name_dinner_'.$i]);
    				if(!empty($row_food_ind)){
    					foreach ($row_food_ind as $row_ind){
    						$deliver_day = $row_ind["deliver_day"];
    						$do_date_wed = new DateTime($data["date_dinner"]);
    						$deliver_date = $do_date_wed->modify('-'.$deliver_day.' day');
    						$arr_d = array(
    								'quote_id'		=> $data["id"],
    								'qc_id'			=>	$qc_id,
    								'type'			=>	4,
    								'food_id'		=>	$data['item_name_dinner_'.$i],
    								'item_id'		=>	$row_ind["item_id"],
    								'qty'			=>	$row_ind["qty"],
    								'su_id'			=>	$row_ind["su_id"],
    								'measure_id'	=>	$row_ind["measure_id"],
    								'deliver_day'	=>	$deliver_date->format('Y-m-d'),
    						);
    						$this->_name = "ldc_quote_item";
    						$this->insert($arr_d);
    					}
    				}
    			}
    		}
    		 
    		if($data['identity_service']){
    		
    			$arr_in = array(
    					'quote_id'		=>	$data["id"],
    					//'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	5,
    					//'price'			=>	$data['t_price_dinner'],
    					//'address'		=>	$data['address_dinner'],
    					//'date_do'		=>	$data["date_dinner"],
    					//'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_service"],
    			);
    			$this->_name ='ldc_quotation_connection';
    			$qc_id = $this->insert($arr_in);
    		
    			$ids = explode(',', $data['identity_service']);
    			foreach ($ids as $i){
    				$arr_ins = array(
    						'qc_id'		=>$qc_id,
    						'food_id'	=>$data['item_name_ser_'.$i],
    						'qty'		=>$data['qty_ser_'.$i],
    						'price'		=>$data['qty_ser_'.$i],
    						'note'		=>$data['qty_ser_'.$i],
    		
    				);
    				$this->_name ='ldc_quotation_detail';
    				$this->insert($arr_ins);
    			}
    		}
    		$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	}
    }
    
    function mergerQuote($id){
    	$db = $this->getAdapter();
    	$ids = explode(',', $id);
    	$merge_id = $this->getQuoteMergeID();
    	foreach ($ids as $i){
    		$arr_ins = array(
    				'merge_id'		=>	$merge_id,
    		);
    		$this->_name ='ldc_quotation';
    		$where = $db->quoteInto("id=?", $i);
    		$this->update($arr_ins, $where);
    	}
    }
    function updateQuote($data){
//     	print_r($data);
    $db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
    		if($data['identity_wedding']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_wedding'],
    					'type'			=>	1,
    					'price'			=>	$data['t_price_wedding'],
    					'address'		=>	$data['address_wedding'],
    					'date_do'		=>	$data["date_wedding"],
    					'time_do'		=>	$data["time_wedding"],
    					'total_pay'		=>	$data["amount_wedding"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$where = $db->quoteInto("id=?", $data["id_wedding"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			if($data["id_wedding"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_wedding"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			
    			if($data["id_wedding"]!=""){
	    			$sql_w ="DELETE FROM ldc_quotation_detail WHERE quote_id=".$data["id_wedding"];
	    			$db->query($sql_w);
    			}
    			$ids = explode(',', $data['identity_wedding']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'	=>	$_id,
    						'food_id'	=>	$data['item_name_wedding_'.$i],
    						'qty'		=>	$data['qty_wedding_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    		
    		if($data['identity_breakfast']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_breakfast'],
    					'type'			=>	2,
    					'price'			=>	$data['t_price_breakfast'],
    					'address'		=>	$data['address_breakfast'],
    					'date_do'		=>	$data["date_breakfast"],
    					'time_do'		=>	$data["time_breakfast"],
    					'total_pay'		=>	$data["amount_breakfast"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$where = $db->quoteInto("id=?", $data["id_breakfast"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			if($data["id_breakfast"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_breakfast"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			
    			if($data["id_breakfast"]!=""){
	    			$sql_b ="DELETE FROM ldc_quotation_detail WHERE quote_id=".$data["id_breakfast"];
	    			$db->query($sql_b);
    			}
    			$ids = explode(',', $data['identity_breakfast']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$_id,
    						'food_id'=>$data['item_name_breakfast_'.$i],
    						'qty'=>$data['qty_breakfast_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    		
    		if($data['identity_lunch']!=""){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_lunch'],
    					'type'			=>	3,
    					'price'			=>	$data['t_price_lunch'],
    					'address'		=>	$data['address_lunch'],
    					'date_do'		=>	$data["date_lunch"],
    					'time_do'		=>	$data["time_lunch"],
    					'total_pay'		=>	$data["amount_lunch"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			
//     			$db->getProfiler()->setEnabled(true);
    			$where = $db->quoteInto("id=?", $data["id_lunch"]);
    			
    			if($data["id_lunch"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_lunch"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			if($data["id_lunch"]!=""){
    			$sql_l ="DELETE FROM ldc_quotation_detail WHERE quote_id=".$data["id_lunch"];
    			$db->query($sql_l);
    			}
    			
    			$ids = explode(',', $data['identity_lunch']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$_id,
    						'food_id'=>$data['item_name_lunch_'.$i],
    						'qty'=>$data['qty_lunch_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
	    	 
    		if($data['identity_dinner']){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	4,
    					'price'			=>	$data['t_price_dinner'],
    					'address'		=>	$data['address_dinner'],
    					'date_do'		=>	$data["date_dinner"],
    					'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$where = $db->quoteInto("id=?", $data["id_dinner"]);
    			
//     			$db->getProfiler()->setEnabled(true);
    			
    			if($data["id_dinner"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_dinner"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			$this->update($arr, $where);
    			
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			if($data["id_dinner"]!=""){
	    			$sql_d ="DELETE FROM ldc_quotation_detail WHERE quote_id=".$data["id_dinner"];
	    			$db->query($sql_d);
    			}
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$_id,
    						'food_id'=>$data['item_name_dinner_'.$i],
    						'qty'=>$data['qty_dinner_'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    				
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    		
    		if($data['identity_service']){
    			$arr =array(
    					'quot_code'		=>	$data['qutoe_code'],
    					'customer_id'	=>	$data['customer_name'],
    					//'num_table'		=>	$data['t_number_dinner'],
    					'type'			=>	5,
    					//'price'			=>	$data['t_price_dinner'],
    					//'address'		=>	$data['address_dinner'],
    					//'date_do'		=>	$data["date_dinner"],
    					//'time_do'		=>	$data["time_dinner"],
    					'total_pay'		=>	$data["amount_dinner"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$data['status'],
    					//'desc'			=>	$data['note']
    			);
    			$this->_name ="ldc_quotation";
    			$where = $db->quoteInto("id=?", $data["id_dinner"]);
    			 
    			//     			$db->getProfiler()->setEnabled(true);
    			 
    			if($data["id_dinner"]!=""){
    				$this->update($arr, $where);
    				$_id = $data["id_dinner"];
    			}else {
    				$id = $this->insert($arr);
    				$_id = $id;
    			}
    			$this->update($arr, $where);
    			 
    			//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
    			//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
    			//     			$db->getProfiler()->setEnabled(false);
    			if($data["id_dinner"]!=""){
    				$sql_d ="DELETE FROM ldc_quotation_detail WHERE quote_id=".$data["id_dinner"];
    				$db->query($sql_d);
    			}
    			$ids = explode(',', $data['identity_dinner']);
    			foreach ($ids as $i){
    				$arr_in = array(
    						'quote_id'=>$_id,
    						'food_id'=>$data['item_name_ser'.$i],
    						'qty'=>$data['qty_ser'.$i],
    						'price'=>$data['qty_ser'.$i],
    						'note'=>$data['qty_ser'.$i],
    				);
    				$this->_name ='ldc_quotation_detail';
    		
    				//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_in);
    				//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
    				//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
    				//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
	    		
	    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		Application_Model_DbTable_DbUserLog::writeMessageError($e);
    		echo $e->getMessage();exit();
    	} 
    }
    
    function convertToOrder($id){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	$row_quote = $this->getQuote($id);
    	//print_r($row_quote);exit();
    	$order_code = $this->getOrderID();
    	try {
    	if(!empty($row_quote)){
    		foreach ($row_quote as $rs_quote){
    			$arr = array(
    					'order_code'	=>	$order_code,
    					'customer_id'	=>	$rs_quote['customer_id'],
    					'num_table'		=>	$rs_quote['num_table'],
    					'type'			=>	$rs_quote['type'],
    					'price'			=>	$rs_quote['price'],
    					'address'		=>	$rs_quote['address'],
    					'date_do'		=>	$rs_quote["date_do"],
    					'time_do'		=>	$rs_quote["time_do"],
    					'total_pay'		=>	$rs_quote["total_pay"],
    					//'balance'		=>	$data[""],
    					'status'		=>	$rs_quote['status'],
    					//'desc'		=>	$data['note']	
    			);
    			$this->_name = "ldc_order";
//     			$db->getProfiler()->setEnabled(true);
    			$_id = $this->insert($arr);
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     			Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     			$db->getProfiler()->setEnabled(false);
    			$row_quote_detail = $this->getQuoteDetail($rs_quote['id']);
    			
    			foreach ($row_quote_detail as $rs_quote_detail){
    				$arr_detail = array(
    						'order_id'	=>	$_id,
    						'food_id'	=>	$rs_quote_detail['food_id'],
    						'qty'		=>	$rs_quote_detail['qty'],
    				);
    				$this->_name = "ldc_order_detail";
//     				$db->getProfiler()->setEnabled(true);
    				$this->insert($arr_detail);
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
//     				Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
//     				$db->getProfiler()->setEnabled(false);
    			}
    		}
    	}
    	$db->commit();
    	}catch (Exception $e){
    		$db->rollBack();
    		echo $e->getMessage();
    	}
    }
    public function getStatus(){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,name_en,name_kh,key_code FROM ldc_view WHERE type=2 AND status=1";
    	return $db->fetchAll($sql);
    }
    
    function getAllQuote($search){
    	$db = $this->getAdapter();
    	$start_date = $search["start_date"];
    	$end_date = $search["end_date"];
//     	$sql ="SELECT 
// 				  q.`quot_code` AS id,
// 				  q.`quot_code`,
				  
// 				  (SELECT CONCAT(c.`first_name`,' ',c.`last_name`) FROM `ldc_customers` AS c WHERE c.id=q.`customer_id`) AS customer,
// 				  (SELECT c.`ceremony_date` FROM `ldc_customers` AS c WHERE c.id=q.`customer_id`) AS date_ceremony,
// 				  (SELECT qs.`num_table` FROM `ldc_quotation` AS qs WHERE qs.`quot_code`=q.`quot_code` AND qs.`type`=1) AS wedding_food_table,
    			 
// 				  (SELECT qs.`num_table` FROM `ldc_quotation` AS qs WHERE qs.`quot_code`=q.`quot_code` AND qs.`type`=2) AS breakfast_food_table,
// 				  (SELECT qs.`num_table` FROM `ldc_quotation` AS qs WHERE qs.`quot_code`=q.`quot_code` AND qs.`type`=3) AS lunch_food_table,
// 				  (SELECT qs.`num_table` FROM `ldc_quotation` AS qs WHERE qs.`quot_code`=q.`quot_code` AND qs.`type`=4) AS dinner_food_table,
//     			 q.`status`
// 				FROM
// 				  `ldc_quotation` AS q WHERE 1
// 				  ";
		$sql = "SELECT 
				  q.id,
				  q.`quot_code`,
				c.first_name,
				cc.`ceremony_date`,
				  cc.address_1,
				  (SELECT qc.`num_table` FROM `ldc_quotation_connection` AS qc WHERE q.id=qc.`quote_id` AND qc.`type`=1) AS table_for_wedding,
				  (SELECT qc.`num_table` FROM `ldc_quotation_connection` AS qc WHERE q.id=qc.`quote_id` AND qc.`type`=2) AS table_for_breakfast,
				  (SELECT qc.`num_table` FROM `ldc_quotation_connection` AS qc WHERE q.id=qc.`quote_id` AND qc.`type`=3) AS table_for_lunch,
				  (SELECT qc.`num_table` FROM `ldc_quotation_connection` AS qc WHERE q.id=qc.`quote_id` AND qc.`type`=4) AS table_for_dinner,
				   q.`total_pay`,
				  q.status
				FROM
				  `ldc_quotation` AS q,
					ldc_customer_ceremony AS cc,
				 	ldc_customers AS c
				WHERE q.`status`=1 AND q.customer_id=c.id AND q.`ceremony_id`=cc.id AND cc.ceremony_date >= '$start_date' AND cc.ceremony_date <='$end_date' ";
    	$where ="";
    	if($search['status_search']>-1){
			$where.= " AND status = ".$search['status_search'];
		}
		if(!empty($search['title'])){
			$s_where=array();
			$s_search=$search['title'];
			$s_where[]= " q.`quot_code` LIKE '%{$s_search}%'";
			$s_where[]=" c.first_name LIKE '%{$s_search}%'";
			$s_where[]= " cc.ceremony_date LIKE '%{$s_search}%'";
			$s_where[]= " cc.address LIKE '%{$s_search}%'";
			//$s_where[]= " cate LIKE '%{$s_search}%'";
			$where.=' AND ('.implode(' OR ', $s_where).')';
		}
		$order = " ORDER BY id DESC";
		$group_by ="GROUP BY q.`quot_code`";
		//echo $sql.$where;		
		return $db->fetchAll($sql.$where.$group_by.$order);	
    }
    function getQuoteDetailByid($id,$type){
    	$db = $this->getAdapter();
    	$sql="SELECT 
				  qs.*,
				  q.`id` AS quote_id,
				  q.`ceremony_id`,
				  q.`total_pay` ,
				  qc.`num_table`,
				  qc.`price`,
				  qc.`address`,
				  qc.`date_do`,
				  qc.`time_do`,
				  qc.`total_pay`
				FROM
				  `ldc_quotation_detail` AS qs,
				  `ldc_quotation` AS q,
				  `ldc_quotation_connection` AS qc 
				WHERE q.`id` = qc.`quote_id` 
				  AND qc.`id` = qs.`qc_id` 
				  AND q.`id` = $id 
				  AND qc.type = $type ";
    	return $db->fetchAll($sql);
    }
    
    function getQuoteOrderDetail($id,$type){
    	$db = $this->getAdapter();
    	$sql = "SELECT qc.*,qd.cat_id,qd.`food_id`,qd.`qty`,qd.price as price_d FROM `ldc_quotation_connection` AS qc,`ldc_quotation_detail` AS qd WHERE qc.`id`=qd.`qc_id` AND qc.`type`=$type AND qc.`quote_id`=$id ";
    	return $db->fetchAll($sql);
    }
    
    function getQuoteDetail($ids){
    	$db = $this->getAdapter();
    	$sql="SELECT
		    	qs.*
		    FROM
		    	`ldc_quotation_detail` AS qs
		    WHERE qs.`quote_id` = $ids";
    	echo $sql;
    	return $db->fetchAll($sql);
    }
    function getQuoteByid($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  q.*,
				  c.`phone`,
				  c.`email`,
				  c.`house_num`,
				  c.`street`,
				  (SELECT p.`province_name` FROM `ldc_province` AS p WHERE p.`id`=c.`province_id`) AS province,
				  (SELECT p.`province_id` FROM `ldc_district` AS p WHERE p.`id`=c.`district`) AS district
				FROM
				  `ldc_quotation` AS q,
				  `ldc_customers` AS c 
				WHERE q.`quot_code` = '$id' 
				  AND q.`customer_id` = c.`id`";
    	return $db->fetchRow($sql);
    }
    function getQuoteOrderById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  q.`quot_code`,
				  q.`ceremony_id`,
				  q.`customer_id`,
				  q.`total_pay`,
				  q.`status`,
				  (SELECT c.`address_1` FROM `ldc_customer_ceremony` AS c WHERE c.`id`=q.`ceremony_id`) AS address
				FROM
				  `ldc_quotation` AS q
				WHERE q.`id`=1";
    	return $db->fetchRow($sql);
    }
    function getQuote($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT
			    	q.*
			    FROM
			    	`ldc_quotation` AS q,
			    	`ldc_customers` AS c
			    WHERE q.`quot_code` = '$id'
			    	AND q.`customer_id` = c.`id`";
			   return $db->fetchAll($sql);
    }
 	function getTypeById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT id,type,status FROM $this->_name  WHERE id=".$id;
   		return $db->fetchRow($sql);
    }
	function getAllMake(){
    	$db=$this->getAdapter();
    	$sql="SELECT id,title FROM ldc_food_cat WHERE `status`=1 ";
    	return $db->fetchAll($sql);
    }
    
    function getQuoteIngredients($id){
    	$db=$this->getAdapter();
    	$sql="SELECT f.*,m.`measure_name_kh` FROM `ldc_food_ingredients` AS f,`ldc_product` AS p, `ldc_measure` AS m WHERE p.`unit`=m.`id` AND f.`item_id`=p.`id` AND f.`food_id` =$id";
    	return $db->fetchAll($sql);
    }

    function getCeremonyDate($id){
    	$db=$this->getAdapter();
    	$sql="SELECT cc.`id`,cc.`ceremony_date` AS name FROM `ldc_customer_ceremony` AS cc WHERE cc.`cu_id`=$id";
    	return $db->fetchAll($sql);
    }
    
    function getAddress($id){
    	$db=$this->getAdapter();
    	$sql="SELECT cc.`address_1`,cc.`address_2`,cc.`address_3`,cc.`ceremony_date` FROM `ldc_customer_ceremony` AS cc WHERE cc.`id`=$id";
    	return $db->fetchRow($sql);
    }
    
    function getAllAddress($id){
    	$db=$this->getAdapter();
    	$sql="SELECT  cc.`address_1`,cc.`address_2`,cc.`address_3` FROM `ldc_customer_ceremony` AS cc WHERE cc.`id`=$id";
    	$row = $db->fetchRow($sql);
    	$arr = array();
    	$arr1 = array('id'=>$row["address_1"],'name'=>$row["address_1"]);
    	$arr2 = array('id'=>$row["address_2"],'name'=>$row["address_2"]);
    	$arr3 = array('id'=>$row["address_3"],'name'=>$row["address_3"]);
    	if(!empty($row["address_2"]) OR !empty($row["address_3"])){
    		$arr = array_merge(array($arr1,$arr2,$arr3));
    	}
//     	if(!empty($row["address_3"])){
//     		$arr = array_merge(array($arr,$arr3));
//     	}
    	return $arr;
    }
    
    function getAllService(){
    	$db = $this->getAdapter();
    	$sql = "SELECT s.id,s.`title` FROM `ldc_service` AS s";
    	return $db->fetchAll($sql);
    }
    
    function getFoodIngrediant($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT 
				  fi.`item_id`,
				  fi.`qty`,
				  fi.`su_id`,
				  fi.deliver_day,
				  m.`id` AS measure_id  
				FROM
				  `ldc_food_ingredients` AS fi,
				  `ldc_product` AS p,
				  ldc_measure AS m 
				WHERE fi.`food_id` = $id 
				  AND p.unit = m.`id` 
				  AND p.id = fi.`item_id` ";
    	
    	return $db->fetchAll($sql);
    }
    
    function getCeremony($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT `ceremony_id` FROM `ldc_quotation` WHERE id=$id";
    	return $db->fetchOne($sql);
    }
}  
	  

