<?php

class Application_Model_DbTable_DbSendEmail extends Zend_Db_Table_Abstract
{
	public function init()
	{
		/* Initialize action controller here */
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public function baseUrl(){
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}
	public function setName($name){
		$this->_name=$name;
	}
	public static function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	
	}
function forwardEmail($data){
		try{
		$from_email = $data["your_email"];
		$to_Email   = $data["friend_email"]; //Replace with recipient email address
		$url 		= $data["txt_url"];
		$first_name = $data["first_name"];
		$last_name 	= $data["last_name"];
		$comment = $data["comment"];
		$subject    = $first_name." ".$last_name.': Forward URL From Lyna-Carrental.Com'; //Subject line for emails
		$advice 	= "Hi Friend i want to recommend this URL to you:\r\n".$url."\r\n".$comment;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		$headers .= 'From: '.$from_email."\r\n".
				'Reply-To: '.$to_Email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		//$message='<p style="color:#080;font-size:18px;">'.$user_Message.'</p>';
		//$urls='<p>Thank You For Support Our Service!</p><br /><a href="http://lyna-carrental.com'.$url.'">Click Here for Go to reset password</a>';
	
		$sentMail = @mail($to_Email, $subject,$advice, $headers);
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
		}catch (Exception $e){
			echo $e->getMessage();exit();
		}
	}
function sendOtherBookEmail($data){
		$to_Email       = "info@lyna-carrental.com"; //Replace with recipient email address
		$subject        = 'General Customer Booking'; //Subject line for emails
		$user_Email = $data["email"];
		$user_name = $data["first_name"]." ".$data["last_name"];
		if($data["gender"]==1){
			$gender = "Male";
		}else{
			$gender="Female";
		}
		
		$tel = $data["phone"];
		$card_name = $data["card_name"];
		$card_num = $data["card_id"];
		$cvv = $data["secu_code"];
		$card_exp = $data["card_exp_date"];
 
                $occupation = $data["occupation"];
		$book_fee = $data["amount_book"];
		$amount = $data["amount"];
		$service = $data["service_charge"];
		$total = $data["total"];
		$pupose = $data["purpose"];
		
		//$db = new Application_Model_DbTable_DbGlobal();
		 
		//$note = $db->getAllParameter("General Booking Note");
		
		$user_info='<div style="font-family:Arial;font-size:10px">
	<table align="left" width="45%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid rgb(204, 204, 204);width:45% !important;font-family:Arial;font-size:10px;margin-right:10px">
		<tr style="background: #4484F1;height: 30px" >
			<td colspan="2" style="padding-left: 10px;color: white;">Customer Info</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Customer Name :</td>
			<td>'.$user_name.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Gender :</td>
			<td>'.$gender.'</td>
		</tr>
                <tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Occupation :</td>
			<td>'.$occupation.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Tel :</td>
			<td>'.$tel.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Email :</td>
			<td>'.$user_Email.'</td>
		</tr>
	</table>';
	$user_info.='<table align="left" width="45%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid rgb(204, 204, 204);width:45% !important;font-family:Arial;font-size:10px;">
		<tr style="background: #4484F1;height: 30px" >
			<td colspan="2" style="padding-left: 10px;color: white;">Credit Card Info</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Card Name :</td>
			<td>'.$card_name.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Card No. :</td>
			<td>'.$card_num.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Secure Code/CVV :</td>
			<td>'.$cvv.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;">Expiration Date :</td>
			<td>'.$card_exp.'</td>
		</tr>
	</table>
					
	<table align="left" width="60%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid rgb(204, 204, 204);width:60% !important;font-family:Arial;font-size:10px; margin:10px auto;">
		<tr style="background: #4484F1;height: 30px" >
			<td colspan="2" style="padding-left: 10px;color: white;">Payment Info</td>
		</tr>
		<tr style="height: 25px">
			<td style="padding-left: 10px;width: 40%;">Amount In US$ (50%) :</td>
			<td>$'.$book_fee.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;width: 40%;">Amount In US$ (100%) :</td>
			<td>$'.$amount.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;width: 40%;">Service Charge (3%) :</td>
			<td>$'.$service.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;width: 40%;">Net Total In US$ :</td>
			<td>$'.$total.'</td>
		</tr>
		<tr style="padding-left: 10px;height: 25px">
			<td style="padding-left: 10px;width: 40%;">Purposes :</td>
			<td>'.$pupose.'</td>
		</tr>
	</table>
</div>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	$headers .= 'From: '.$to_Email."\r\n".
			'Reply-To: '.$user_Email."\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$sentMail = @mail($to_Email, $subject, $user_info, $headers);
		//$sentMail = @mail($user_Email, $subject, $for_user, $headers);
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi '.$user_name.', Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
	}
function resetPassEmail($data){
		$email = $data["email"];
		$to_Email       = $email; //Replace with recipient email address
		$url = $this->baseUrl()."/customer/forgetpass/url/".$data["pass_link"];
		$subject        = 'Reset Password From Lyna-Carrental.Com'; //Subject line for emails
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		$headers .= 'From: '.$to_Email."\r\n".
				'Reply-To: '.$to_Email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		//$message='<p style="color:#080;font-size:18px;">'.$user_Message.'</p>';
$urls='<p>Thank You For Support Our Service!</p><br /><a href="http://lyna-carrental.com'.$url.'">Click Here for Go to reset password</a>';
	
		$sentMail = @mail($to_Email, $subject, $urls, $headers);
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
	}
	function sendContactEmail(){
		if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			die();
		}
		
		$to_Email       = "info@lyna-carrental.com"; //Replace with recipient email address
		$subject        = 'Message from contact form require further information'; //Subject line for emails
		
		//check $_POST vars are set, exit if any missing
		
		//Sanitize input data using PHP filter_var().
		$user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
		$user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
		$user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
		
		//additional php validation
		if(strlen($user_Name) < 4) {
			header('HTTP/1.1 500 Name is too short or empty!');
			exit();
		}
		if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) {
			header('HTTP/1.1 500 Please enter a valid email!');
			exit();
		}
		if(strlen($user_Message) < 5) {
			header('HTTP/1.1 500 Too short message! Please enter something.');
			exit();
		}
		
		//proceed with PHP email.
		//$headers = 'From: '.$user_Email.'' . "rn" .
				'Reply-To: '.$user_Email.'' . "rn" .
				'X-Mailer: PHP/' . phpversion();

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$headers .= 'From: '.$user_Email."\r\n".
						    'Reply-To: '.$user_Email."\r\n" .
						    'X-Mailer: PHP/' . phpversion();
            $message='<p style="color:#080;font-size:18px;">'.$user_Message.'</p>';

		$sentMail = @mail($to_Email, $subject, $message .'  -'.$user_Name, $headers);
		
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi '.$user_Name .', Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
	}
	
	
	
	function sendInvoiceEmail($row_booking_id){
		$db = new Application_Model_DbTable_DbGlobal();
		$row_booking = $db->getBookingById($row_booking_id, 1);
		$row_booking_detail = $db->getBookingById($row_booking_id, 2);
		$pickup_date = date_create($row_booking["pickup_date"]);
		$return_date =date_create($row_booking["return_date"]);
		$diff=date_diff($pickup_date,$return_date);
		$total_day = $diff->format("%a%")+1;
                
		if($row_booking["rental_type"]==1){
			$duration = " Day(s)";
			$total_duration = $total_day;
		}elseif($row_booking["rental_type"]==2){
			$duration = " Month(s)"; $total_duration=$row_booking["total_duration"];
		}else{$duration = " Day(s)";}
$htmlContent ='<div style="font-family:Arial;font-size:10px">
	<table align="center" style="width:100%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100% !important;font-family:Arial;font-size:12px">
						<tbody>
							<tr>
								<td>
									<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial;font-size:12px">
										<tbody>
											<tr>
												<td>
													<table width="100%" align="center" style="background:#FBFF00">
														<tr align="center" style="height:12px;font-size: 30px;">
															<td><span style="color:red;font-family:"Kh Kangrey">លីណា-ជួលរថយន្តទេសចរណ៍</span></td>
														</tr>
														<tr align="center" style="height: 12px;font-size: 30px;">
															<td><span style="color:red; font-family:"Bocci Regular ttnorm">Lyna-CarRental.Com</span></td>
														</tr>
														<tr align="center" style="height: 12px;font-size: 30px;background:#0C109E;border-top:2px solid #ffffff;">
															<td><span style="color:red;"></span></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr style="height:30px"><td><hr /></td></tr>
											<tr bgcolor="#ffffff">
												<td>
													<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial;font-size:12px">
														<tbody>';
															
											if($row_booking["item_type"]==1){
											$htmlContent.='<tr border="1">
																<td width="25%" align="left">Bill For : </td>
																<td width="25%"align="left">'. $row_booking["customer_name"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right"></td>
																<td width="30%" align="right"></td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">Phone No. : </td>
																<td width="25%"align="left">'. $row_booking["customer_phone"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right"></td>
																<td width="30%" align="right"></td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">E-mail Address : </td>
																<td width="25%"align="left">'. $row_booking["customer_email"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight No.:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>';
											$htmlContent .='<tr>
																<td width="25%" align="left">Pickup Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["pickup_date"]." ".$row_booking["pickup_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight Date:</td>
																<td width="30%" align="right">'.$row_booking["fly_date"].'</td>
															</tr>
															<tr>
																<td width="25%" align="left">Pickup Location:</td>
																<td width="25%" align="left">'.$row_booking["pickup_location"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right"> Time of Arrival:</td>
																<td width="30%" align="right">'.$row_booking["fly_time_of_arrival"].'</td>
															</tr>
															<tr>
																<td width="25%" align="left">Return Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["return_date"]." ".$row_booking["return_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Destination:</td>
																<td>'.$row_booking["fly_destination"].'</td>
															</tr>
															 <tr>
																<td width="25%" align="left">Return Location:</td>
																<td width="25%" align="left">'.$row_booking["return_location"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Booking No.: </td>
																<td width="30%" align="right" style="color:red;"><b style="color:red">'.$row_booking["booking_no"].'</b></span></td>
															</tr>
															<tr>
																<td align="left">Duration :</td>
																<td align="left">'.$total_day.$duration.'</td>
																<td></td>
																<td width="15%" align="right">Booking Date: </td>
																<td width="30%" align="right">'.$row_booking["date_book"].'</td>
															</tr>';
											}elseif ($row_booking["item_type"]==2){
											$htmlContent.='<tr border="1">
																<td width="25%" align="left">Bill For : </td>
																<td width="25%"align="left">'. $row_booking["customer_name"].'</td>
																<td width="15%" align="right"></td>
																<td width="30%" align="right"></td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">Phone No. : </td>
																<td width="25%"align="left">'. $row_booking["customer_phone"].'</td>
																<td width="15%" align="right">Flight No.:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">E-mail Address : </td>
																<td width="25%"align="left">'. $row_booking["customer_email"].'</td>
																<td width="15%" align="right">Flight Date:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>';
											$htmlContent .='<tr>
																<td width="25%" align="left">Pickup Date and Time:</td>
																<td width="30%" align="left">'.$row_booking["pickup_date"]." ".$row_booking["pickup_time"].'</td>
																<td width="15%" align="right">Distination:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>
															<tr>
																<td width="25%" align="left">Pickup Location:</td>
																<td width="30%" align="left">'.$row_booking["pickup_location"].'</td>
																<td width="15%" align="right"> Time of Arrival:</td>
																<td width="30%" align="right">'.$row_booking["fly_time_of_arrival"].'</td>
															</tr>
															 <tr>
																<td width="25%" align="left">Return Location:</td>
																<td width="30%" align="left">'.$row_booking["return_location"].'</td>
																<td width="15%" align="right">Books No: </td>
																<td width="30%" align="right"><b style="color:red">'.$row_booking["booking_no"].'</b></td>
															</tr>
															<tr>
																<td align="left">Trip Way :</td>
																<td align="left">'.$row_booking["trip_type"].'</td>
																<td width="15%" align="right">Books Date: </td>
																<td width="30%" align="right">'.$row_booking["date_book"].'</td>
															</tr>';
											}elseif ($row_booking["item_type"]==3){
											$htmlContent.='<tr border="1">
																<td width="25%" align="left">Bill For : </td>
																<td width="25%"align="left">'. $row_booking["customer_name"].'</td>
																<td width="15%" align="right"></td>
																<td width="30%" align="right"></td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">Phone No. : </td>
																<td width="25%"align="left">'. $row_booking["customer_phone"].'</td>
																<td width="15%" align="right">Flight No.:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">E-mail Address : </td>
																<td width="25%"align="left">'. $row_booking["customer_email"].'</td>
																<td width="15%" align="right">Flight Date:</td>
																<td width="30%" align="right">'.$row_booking["fly_date"].'</td>
															</tr>';
											$htmlContent .='<tr>
																<td width="25%" align="left">Package Name :</td>
																<td width="30%" align="left">'.$row_booking["package_location"].'</td>
																<td width="15%" align="right">Distination:</td>
																<td width="30%" align="right">'.$row_booking["fly_destination"].'</td>
															</tr>
															<tr>
																<td width="25%" align="left">Pickup Date and Time:</td>
																<td width="30%" align="left">'.$row_booking["pickup_date"]." ".$row_booking["pickup_time"].'</td>
																<td width="15%" align="right"> Time of Arrival:</td>
																<td width="30%" align="right">'.$row_booking["fly_time_of_arrival"].'</td>
															</tr>
															<tr>
																<td width="25%" align="left"></td>
																<td width="30%" align="left"></td>
																<td width="15%" align="right">Booking No: </td>
																<td width="30%" align="right"><b style="color:red">'.$row_booking["booking_no"].'</b></td>
															</tr>
															 <tr style="border-bottom:1px solid #000000;">
																<td width="25%" align="left"></td>
																<td width="30%" align="left"></td>
																<td width="15%" align="right">Booking Date: </td>
																<td width="30%" align="right">'.$row_booking["date_book"].'</td>
															</tr>';
											}elseif ($row_booking["item_type"]==4){
												$htmlContent.='<tr border="1">
																<td width="25%" align="left">Bill For : </td>
																<td width="25%"align="left">'. $row_booking["customer_name"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight No.:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">Phone No. : </td>
																<td width="25%"align="left">'. $row_booking["customer_phone"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight Date:</td>
																<td width="30%" align="right">'.$row_booking["fly_date"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">E-mail Address : </td>
																<td width="25%"align="left">'. $row_booking["customer_email"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Distination:</td>
																<td width="30%" align="right">'.$row_booking["fly_destination"].'</td>
															</tr>';
												$htmlContent .='<tr>
																<td width="25%" align="left">Pickup Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["pickup_date"]." ".$row_booking["pickup_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right"> Time of Arrival:</td>
																<td width="30%" align="right">'.$row_booking["fly_time_of_arrival"].'</td>
															</tr>
															
															<tr>
																<td width="25%" align="left">Return Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["return_date"]." ".$row_booking["return_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Books No: </td>
																<td width="30%" align="right" style="color:red;"><b style="color:red">'.$row_booking["booking_no"].'</b></span></td>
															</tr>
															<tr>
																<td align="left">Duration :</td>
																<td align="left">'.$total_day.'</td>
																<td></td>
																<td width="15%" align="right">Books Date: </td>
																<td width="30%" align="right">'.$row_booking["date_book"].'</td>
															</tr>';
											}elseif ($row_booking["item_type"]==5){
												$htmlContent.='<tr border="1">
																<td width="25%" align="left">Bill For : </td>
																<td width="25%"align="left">'. $row_booking["customer_name"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight No.:</td>
																<td width="30%" align="right">'.$row_booking["fly_no"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">Phone No. : </td>
																<td width="25%"align="left">'. $row_booking["customer_phone"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Flight Date:</td>
																<td width="30%" align="right">'.$row_booking["fly_date"].'</td>
															</tr>
															<tr border="1">
																<td width="25%" align="left">E-mail Address : </td>
																<td width="25%"align="left">'. $row_booking["customer_email"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Distination:</td>
																<td width="30%" align="right">'.$row_booking["fly_destination"].'</td>
															</tr>';
												$htmlContent .='<tr>
																<td width="25%" align="left">Pickup Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["pickup_date"]." ".$row_booking["pickup_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right"> Time of Arrival:</td>
																<td width="30%" align="right">'.$row_booking["fly_time_of_arrival"].'</td>
															</tr>
															
															<tr>
																<td width="25%" align="left">Return Date and Time:</td>
																<td width="25%" align="left">'.$row_booking["return_date"]." ".$row_booking["return_time"].'</td>
																<td width="5%"></td>
																<td width="15%" align="right">Books No: </td>
																<td width="30%" align="right" style="color:red;"><b style="color:red">'.$row_booking["booking_no"].'</b></span></td>
															</tr>
															<tr>
																<td align="left">Duration :</td>
																<td align="left">'.$row_booking["total_duration"].$duration.'</td>
																<td></td>
																<td width="15%" align="right">Books Date: </td>
																<td width="30%" align="right">'.$row_booking["date_book"].'</td>
															</tr>';
											}
											$htmlContent.='<tr height="30px" bgcolor="#ffffff" style="border-bottom:1px solid #000000;"><td colspan="5"><hr /></td> </tr>';
										$htmlContent .='</tbody>
													</table>
												</td>
											</tr>
											<tr bgcolor="#ffffff">
												<td>
													<table style="width:100%; border-collapse: collapse;font-family:Arial;font-size:12px" border="0" align="center" cellspacing="0" cellpadding="0">
														<tbody>
															<tr style="background:rgb(82, 132, 253);    height: 40px; border: 1px solid #000000; color:#ffffff">
																<td align="center" style="border: 1px solid #000000;">No. </td>
																<td align="center" style="border: 1px solid #000000;">Items Description</td>
																<td align="center" style="border: 1px solid #000000;">QTY</td>
																<td align="center" style="border: 1px solid #000000;">Price</td>
																<td align="center" style="border: 1px solid #000000;">VAT</td>
																<td align="center" style="border: 1px solid #000000;">Discount</td>
																<td align="center" style="border: 1px solid #000000;">Amount</td>
															</tr>';
															$refun_de=0;
															foreach($row_booking_detail as $key=>$rs){ 
																$i = $key+1;
																if($row_booking["item_type"]==1 OR $row_booking["item_type"]==4 OR $row_booking["item_type"]==6){ 
																	$amount = ($rs["total"]);
																}elseif ($row_booking["item_type"]==2 OR $row_booking["item_type"]==3){
																	$amount = ((($rs["price"]*$rs["rent_num"])+($rs["price"]*$rs["rent_num"]*$rs["VAT"])));
																}elseif ($row_booking["item_type"]==5){
																	$amount = ((($rs["price"]*$rs["rent_num"])+($rs["price"]*$rs["rent_num"]*$rs["VAT"]))*$row_booking["total_duration"]);
																}
																$refun_de += $rs["refund_deposit"];
																$htmlContent.='<tr style="height: 30px;border: 1px solid #000000;">';
																	$htmlContent.='<td align="center" style="padding-left:8px;width:5%; border: 1px solid #000000;">'.$i.'</td>';
																	$htmlContent.='<td align="left" style="padding-left:8px;width:52%;border: 1px solid #000000;">'.$rs["item_name"].'</td>';
																	$htmlContent.='<td align="center" style="border: 1px solid #000000;width:8%">'.$rs["rent_num"].'</td>';
																	$htmlContent.='<td align="right" style="border: 1px solid #000000; width:10%; padding-right:5px;" nowrap="nowrap">$'.number_format($rs["price"],2).'</td>';
																	$htmlContent.='<td align="right" style="border: 1px solid #000000; width:10%; padding-right:5px;" nowrap="nowrap">%'.number_format($rs["VAT"],2).'</td>';
																	$htmlContent.='<td align="right" style="border: 1px solid #000000; width:10%; padding-right:5px;" nowrap="nowrap">%'.number_format($rs["discount"],2).'</td>';
																	$htmlContent.='<td align="right" style="border: 1px solid #000000; width:15%; padding-right:5px;" nowrap="nowrap">$'.number_format($amount,2).'</td>';
																$htmlContent.='</tr>';
															}
															$net_total = $row_booking["total_fee"]+$refun_de;
															$due_total = $net_total-$row_booking["deposite_fee"];
																$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	$htmlContent.='<td align="center" style="padding-left:0px;" colspan="3"></td>';
																	$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">Rental Fee:</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000;padding-right:5px;" nowrap="nowrap">US$'.number_format($row_booking["total_fee"],2).'</td>';
																$htmlContent.='</tr>';
															if($row_booking["item_type"]==1){
																$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	$htmlContent.='<td align="center" style="padding-left:8px;" colspan="3"></td>';
																	$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000;padding-right:5px;" nowrap="nowrap">Refundable Deposit:</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000;padding-right:5px;" nowrap="nowrap">US$'.number_format($refun_de,2).'</td>';
																$htmlContent.='</tr>';
																$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	$htmlContent.='<td align="center" style="padding-left:8px;" colspan="3"></td>';
																	$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000;padding-right:5px;" nowrap="nowrap">Amount Paid (<span style="font-size:10px;">50% + Bank Charge 3%</span>):</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000;padding-right:5px;" nowrap="nowrap">US$'.number_format($row_booking["deposite_fee"],2).'</td>';
																$htmlContent.='</tr>';
															}
																$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	$htmlContent.='<td align="left" style="padding-left:0px;" colspan="3" rowspan="2"><span style="font-size: 11px"><b style="color:red">ចំណាំ:</b> សូមបង្ហាញនូវសារអេឡិចត្រូនិច ឬ ក្រដាស់ដែលបានថតចម្លង ពីការកក់រថយន្តរបស់អ្នក នៅពេលការមកដល់របស់អ្នក។</span><br/>
																			<span style="font-size: 12px"><b style="color:red">Note:</b> Please present either an electronic or paper copy of your vehicle booking sheet upon your arrival.</span>
																			</td>';
																	$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">Net Total:</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">US$'.number_format($net_total,2).'</td>';
																$htmlContent.='</tr>';
																$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	//$htmlContent.='<td align="center" style="padding-left:0px;" border="0" colspan="3"><span style="font-size: 12px"><b style="color:red">Note:</b> Please present either an electronic or paper copy of your vehicle booking sheet upon your arrival.</span></td>';
																	$htmlContent.='<td  align="right" colspan="2" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">Due Total:</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">US$'.number_format($due_total,2).'</td>';
																$htmlContent.='</tr>';
															
														$htmlContent.='</tbody>
													</table>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>';
														//print_r($htmlContent);exit();
	if($row_booking["payment_type"]==1){
		$payment_type="VISA CARD";
	}elseif($row_booking["payment_type"]==2){
		$payment_type="WESTERN UNION";
	}else{
		$payment_type="ACLEDA UNITY";
	}
$user_info='<div style="font-family:Arial;font-size:10px">
	<table align="left" width="50%" border="0" cellspacing="0" cellpadding="0" style="width:50% !important;font-family:Arial;font-size:10px;">
		<tr style="background: #4484F1;height: 30px" >
			<td colspan="2" style="padding-left: 10px;color: white;">
				Customer Payment Info
			</td>
		</tr>
		<tr style="height: 25px">
			<td>
				Payment Tye :
			</td>
			<td>
				'.$payment_type.'
			</td>
		</tr>';
if($row_booking["payment_type"]==1){
		$user_info.='<tr style="height: 25px">
			<td>
				Card Name  :
			</td>
			<td>
				'.$row_booking["visa_name"].'
			</td>
		</tr>
		<tr style="height: 25px">
			<td>
				Card Number  :
			</td>
			<td>
				'.$row_booking["card_id"].'
			</td>
		</tr>
		<tr style="height: 25px">
			<td>
				CVV  :
			</td>
			<td>
				'.$row_booking["secu_code"].'
			</td>
		</tr>
		<tr style="height: 25px">
			<td>
				Expiration Date  :
			</td>
			<td>
				'.$row_booking["card_exp_date"].'
			</td>
		</tr>';
}elseif ($row_booking["payment_type"]==2){
	$user_info.='<tr style="height: 25px">
			<td>
				Code Number  :
			</td>
			<td>
				'.$row_booking["card_id"].'
			</td>
		</tr>';
}
	$user_info.='</table>
</div>';
		$to_Email_user       = $row_booking["customer_email"]; //Replace with recipient email address
		$to_Email_admin       = "info@lyna-carrental.com"; //Replace with recipient email address
		$subject        = 'Lyna-Carrental.Com - Reservation Invoice'; //Subject line for emails
	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
		$headers .= 'From: '.$to_Email_admin."\r\n".
				'Reply-To: '.$to_Email_user."\r\n" .
				'X-Mailer: PHP/' . phpversion();
	
		$sentMail = @mail($to_Email_admin, $subject, $htmlContent .'<br />'.$user_info , $headers);
		$sentMail = @mail($to_Email_user, $subject, $htmlContent, $headers);
		
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi '.$row_booking["customer_name"] .', Thank you for your Booking! ';
			$return.='Your email has been delivered.If do not delivery contact admin! ';
		}
		return $return;
	}
	function sendOrderInvoice($order_id){
		$db = new Application_Model_DbTable_DbOrder();
		$rows_ordering = $db->getOrderhistorydetail($order_id);
		$order = $db->getOrderInvoice($order_id);
		$name='';
		if(!empty($order['branch'])){
			$name= $order['branch'];
		}else{
			$name = $order["name"];
		}
		$htmlContent ='<div style="font-family:Arial;font-size:10px">
	<table align="center" style="width:100%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100% !important;font-family:Arial;font-size:12px">
						<tbody>
							<tr>
								<td>
									<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial;font-size:12px">
										<tbody>
											<tr>
												<td>
													<table width="100%" align="center" style="background:#5284FD">
														<tr align="center" style="height: 12px;font-size: 30px;">
															<td><span style="color:#fff; font-family:"Bocci Regular ttnorm">Aplush Fresh Shop</span></td>
														</tr>
														<tr align="center" style="height: 12px;font-size: 30px;background:#E91E31;border-top:2px solid #ffffff;">
															<td><span style="color:#fff;"></span></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td>&nbsp;</td></tr>
											<tr>
												<td>
													<table>
														<tr>
															<td>Invoice No : </td>
															<td>'.$order["invoice_no"].'</td>
															<td>Order Date : </td>
															<td>'.$order["order_date"].'</td>
														</tr>
														<tr>
															<td>Name : </td>
															<td>'.$name.'</td>
															<td>Phone : </td>
															<td>'.$order["phone"].'</td>
														</tr>
														<tr>
															<td>Email : </td>
															<td>'.$order["mail"].'</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr bgcolor="#ffffff">
												<td>
													<table style="width:100%; border-collapse: collapse;font-family:Arial;font-size:12px" border="0" align="center" cellspacing="0" cellpadding="0">
														<tbody>';
		$htmlContent .='<tr style="background:rgb(82, 132, 253);    height: 40px; border: 1px solid #000000; color:#ffffff">
																<td align="center" style="border: 1px solid #000000;">No. </td>
																<td align="center" style="border: 1px solid #000000;">Product Code</td>
																<td align="center" style="border: 1px solid #000000;">Product Name</td>
																<td align="center" style="border: 1px solid #000000;">Qty</td>
																<td align="center" style="border: 1px solid #000000;">Price</td>
																<td align="center" style="border: 1px solid #000000;">Amount</td>
															</tr>';
																 if (!empty($rows_ordering))  $i=0; foreach ($rows_ordering as $row){$i++;
															$htmlContent.='<tr style=" height: 25px; border: 1px solid #000000; color:#000">';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.$i.'</td>';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.$row['pro_no'].'</td>';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.$row['pro_name'].'</td>';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.$row['qty'].'</td>';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.number_format($row['price'],2).'</td>';
																$htmlContent.='<td align="center" style="border: 1px solid #000000;">'.number_format($row['amount'],2).'</td>'; 
																
															$htmlContent.='</tr>';
																}
															$htmlContent.='<tr style="height: 25px;border:0px !important;">';
																	$htmlContent.='<td align="left" style="padding-left:0px;" colspan="2" rowspan="2"><span style="font-size: 11px"><b style="color:red">ចំណាំ:</b> សូមបង្ហាញនូវសារអេឡិចត្រូនិច ឬ ក្រដាស់ដែលបានថតចម្លង ពីការបញ្ជារទិញទំនិញរបស់អ្នក</span><br/>';
																			$htmlContent.='<span style="font-size: 12px"><b style="color:red">Note:</b> Please present either an electronic or paper copy of your products ordering.</span>';
																			$htmlContent.='</td>';
																			$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">Discount(%):</td>';
																			$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">'.$order["discount_login"].'%</td>';
															$htmlContent.='</tr>';
															$htmlContent.='<tr style="height: 25px;border:0px !important;">';																		
																	$htmlContent.='<td align="right" colspan="2" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">Total:</td>';
																	$htmlContent.='<td colspan="2" align="right" style="border: 1px solid #000000; padding-right:5px;" nowrap="nowrap">US$'.number_format($order["grand_total"],2).'</td>';
															$htmlContent.='</tr>';
														$htmlContent.='</tbody>';
													$htmlContent.='</table>';
		$htmlContent .='</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>';
		$db_mail = $this->getAdapter();
		$sql = "SELECT p.`title`,p.`value` FROM `ldc_paramater`AS p WHERE p.`title`='email'";
		$serverEmail = $db_mail->fetchRow($sql);
		
		$to_Email_user       = $order["mail"]; //Replace with recipient email address
//echo $to_Email_user;exit();
		$to_Email_admin       = $serverEmail['value']; //Replace with recipient email address
		$subject        = 'www.apitgroups.com - Reservation Invoice'; //Subject line for emails
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		$headers .= 'From: '.$to_Email_admin."\r\n".
				'Reply-To: '.$to_Email_user."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		
		$sentMail = @mail($to_Email_admin, $subject, $htmlContent .'<br />', $headers);
		$sentMail = @mail($to_Email_user, $subject, $htmlContent, $headers);
		//print_r ($sentMail);exit();
		
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi '.$order["name"] .', Thank you for your Booking! ';
			$return.='Your email has been delivered.If do not delivery contact admin! ';
		}
		return $return;
	}
function resetPasswordEmail($data){
		$email = $data["email"];
		$to_Email       = $email; //Replace with recipient email address
		$url = $this->baseUrl()."/customer/forgetpassword/url/".$data["pass_link"];
		$subject        = 'Reset Password From apitgroups.com/'; //Subject line for emails
	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		$headers .= 'From: '.$to_Email."\r\n".
				'Reply-To: '.$to_Email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		//$message='<p style="color:#080;font-size:18px;">'.$user_Message.'</p>';
		$urls='<p>Thank You For Support Our Service!</p><br /><a href="http://www.apitgroups.com'.$url.'">Click Here for Go to reset password</a>';
	
		$sentMail = @mail($to_Email, $subject, $urls, $headers);
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
	}
function ContactFormSendmail($data){
		
$db = $this->getAdapter();
		$sql = "SELECT p.`title`,p.`value` FROM `ldc_paramater`AS p WHERE p.`title`='email'";
		$serverEmail = $db->fetchRow($sql);
		
				
		$email = $data["email"];
		$from_email =$email;
		$to_Email       = $serverEmail['value']; //Replace with recipient email address
		

		$subject        = $data["subject"]; //Subject line for emails
		$order_refer        = $data["order_reference"];
		$messsage        = $data["message"];
		
		$content ='<h3>'.$order_refer.'</h3><p>'.$messsage.'</p>';
$userconten='<p>Thanks you for your email.</p>';
$subjectuser='Thanks for sending';
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		$headers .= 'From: '.$from_email."\r\n".
				'Reply-To: '.$to_Email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		//$message='<p style="color:#080;font-size:18px;">'.$user_Message.'</p>';
		
		$sentMail = @mail($to_Email, $subject, $content, $headers);
$sentMail = @mail($from_email, $subjectuser, $userconten, $headers);
	
		if(!$sentMail)  {
			$return="HTTP/1.1 500 Could not send mail! Sorry..";
		} else {
			$return='Hi Thank you for your email! ';
			$return.='Your email has been delivered.';
		}
		return $return;
	}
	
 
}
?>