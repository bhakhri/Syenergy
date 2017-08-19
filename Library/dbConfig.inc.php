<?php
//-------------------------------------------------------
// Purpose: This will be included at the top of common.inc.php file. This file contains Global declaration of variables which are being used through out the application such as DB variables.
//
// Author : Vimal Sharma
// Created on : (12.11.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//echo "<h1>Please bear with us, Server is down for maintenance!!</h1>";die; 
define("SUBSCRIPTION_STATUS",'OK'); //Values are 'PENDING', 'OK'
define("SERVER",'MEDIA');
define("ACCOUNT_NAME",'leapcc');
define("DEBUG_SEVERITY",'');
 //database 

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "iiu");
define("CLIENT_NAME", '' ); //Client name for SMS Services  

define('ADMIN_MSG_EMAIL','webmaster@chalkpad.in'); // this email is used to send messages to employees and student in admin_messages
//define('ERROR_MAIL_TO','sachin.sharma@chalkpad.in,queryerror@chalkpad.in'); // send error 
define('ERROR_MAIL_TO','sachin.sharma@chalkpad.in,priya.sharma@chalkpad.in'); // send error

// Max file size to upload
define('MAXIMUM_FILE_SIZE',1048576*2);  // 1 mb

//Additional Modules Settings to Activate and Deactivate Module Services  
//false = Deactivate Module Services,   true = Activate Module Services
define('INCLUDE_ACCOUNTS',false);

// Multi Institute  
define('MULTI_INSTITUTE',1);        // 0 = No, 1 = Yes 

// SMS variables & max length detail
define('SMS_MAX_LENGTH','160');  // maximum length per sms
define('SMS_GATEWAY_USER_VARIABLE','usr');
define('SMS_GATEWAY_PASS_VARIABLE','pwd');
define('SMS_GATEWAY_NUMBER_VARIABLE','ph'); // ph takes input as mobile no
define('SMS_GATEWAY_MESSAGE_VARIABLE','text'); // text is the message
define('SMS_GATEWAY_SNDR_VARIABLE','sndr'); // Sender variable
define('SMS_GATEWAY_SNDR_VALUE','GNA-IMT'); // Sender value
// SMS account detail
define('SMS_GATEWAY_USERNAME','13022');
define('SMS_GATEWAY_PASSWORD','syenergy');
define('SMS_GATEWAY_URL','http://syenergy.noesisinfoway.com/send.php');  // Gateway URL that sends SMS
define('SMS_GATEWAY_VERIFICATION_URL','http://chaklpad.noesisinfoway.com/rep.php');  // Gateway URL that verifies sent SMSs


//Method to extract messageId. Modify this method as per the response structure of the SMS service provider
 function smsMsgId($response){
	$returnArray=explode(' ',trim($response));
	
    $msgId=trim($returnArray[0]);
	return $msgId;
}

define('NOT_APPLICABLE_STRING','---'); //define variable to show value on blank fields in student information module
define('NO_DATA_FOUND','No Data Found'); //define variable to show no data found in the data list if it will blank

define('ACCOUNT_MANAGER_NAME','Sachin Sharma'); // add account manager name on dbConfig
define('ACCOUNT_MANAGER_EMAIL','sachin.sharma@chalkpad.in'); // add account manager email on dbConfig


define('CLIENT_INSTITUTES','1');  // Number of institutes for which product was bought
define('lat_INSTITUTES','31.24315');  // latitude location for map
define('Lng_INSTITUTES','75.743865');  // langitude location for map
define('ZOOM_FACTOR','17');  // Zoom over  map

//for hostel type
define('GUEST_HOUSE_TYPE',4);
$hostelTypeArr = array("1"=>"Girls","2"=>"Boys", "3"=>"Mixed", GUEST_HOUSE_TYPE =>"Guest House");

// entrance exam name array
$results = array(1 => 'CET', 'AIEEE', 'CEET','NATA','OTHER');

// Class array for admit student
$classResults= array(1 => 'Matric', '10+2', 'Graduation', 'PG (if any)', 'Any Diploma');

// Registration Degree
define('REGISTRATION_DEGREE','PGPCM');
define("INCLUDE_INVENTORY_MANAGEMENT", true);
define('INCLUDE_LEAVE',true);





//used for generating employee codes
define('EMPLOYEE_CODE_PREFIX','VF1');
define('EMPLOYEE_CODE_LENGTH',10);
 
//used for generating user name codes
define('USER_CODE_PREFIX','UM');
define('USER_CODE_LENGTH',10);
// fee receipt print outs
$feeReceiptPrintArr = array("2"=>"College Copy", "3"=>"Student Copy");

//Field Names in Student Details Form
define('REG_NO','Registration No.');      //The Database column "regNo" in Student Table will be displayed in all the forms as second parameter(Registration no.)
define('ROLL_NO','Roll No.');
define('UNIV_REG_NO','University Reg No.');
define('UNIV_ROLL_NO','University Roll No.');
define('COLUMN_UNIV_ROLL_NO','Univ No.');// On clicking showlist find student page column heading
define('COLUMN_ROLL_NO','Roll No.');// On clicking showlist find student page column heading
define('COLUMN_REG_NO','Reg No.');// On clicking showlist find student page column heading

define("LOG_FILE_NAME", 'log.txt');        // The name of the log file
// query log
define("DB_QUERY_LOG", 1); // 0 = off, 1 = on
define("DB_QUERY_LEVEL", 3); //  1 = All, 2= read (SELECT only), 3= write (INSERT, UPDATE, DELETE)
define("DB_QUERY_LOG_DESTINATION", 1); //  1 = Database, 2= File





///////////////////////////////////////////////////////////////////////////////////////////////
// 								ONLINE TRANSACTION API INTEGRATION VAARIABLE AND FUNCTION	//
//////////////////////////////////////////////////////////////////////////////////////////////

						////////////////////////////////////////////////////////////////////
						// 	Variable Used to show message to user						//
						//////////////////////////////////////////////////////////////////

define("Invalid_Transaction", "Invalid Transaction ");
define("Success_Transaction", "Success Transaction ");
define("onlineTransaction_bank", "BillDesk");

						/////////////////////////////////////////////////////////////////////////
						// 	Variable Used for integration of api (only used in db config file) //
						////////////////////////////////////////////////////////////////////////

define("ONLINE_MERCHANT", "GNAIMT");
define("ONLINE_ACCESS_CODE", "gnaimt");

define("ONLINE_URL","https://pgi.billdesk.com/pgidsk/PGIMerchantPayment");
// define("ONLINE_URL","http://gnaimt.syenergy.in/Interface/Student/onlineFeePayment.php");

define("ONLINE_RETURN_URL","http://gnaimt.syenergy.in/Interface/studentPaymentSlip.php");

define("ONLINE_SECURE_SECRET", "FpfmlKT1i0na");

//////////////////////////////////////////////////////////////////////////////////////////////////
// 	function used in syenergy module to redirect or get response fron online transaction portal //
//                Return array And function Name should be have same pattern and index 		   //
//                      for save data in database successfully   								//
/////////////////////////////////////////////////////////////////////////////////////////////////


function onlineTransactionUrl($OrderId,$totalAmout){
		//$OrderId unique order id genrated by syenergy for each transaction
		//$totalAmout Amount of money user want to pay


	$str = ONLINE_MERCHANT."|".$OrderId."|NA|".$totalAmout."|NA|NA|NA|INR|NA|R|".ONLINE_ACCESS_CODE."|NA|NA|F|NA|NA|NA|NA|NA|NA|NA|".ONLINE_RETURN_URL;

	$checksum = hash_hmac('sha256',$str,ONLINE_SECURE_SECRET, false); 
	$checksum = strtoupper($checksum);
			
	$str.='|'.$checksum;
	$post['url']=ONLINE_URL."?msg=".$str; //final redirect url
	$post['data']=$str;					// final dat or variable vale post to portal
	return $post;
}


function onlineTransactionResponse($dataArray){
		//$dataArray is post data from portal may be string or array according to portal api
		$str=$dataArray['msg'];
		$lastVariable=strripos($str,'|');
		$responseStr=substr($str,0,$lastVariable);
		$checkSum=substr($str,$lastVariable+1);
		$response=explode('|',$responseStr);
		$checksum1 = hash_hmac('sha256',$responseStr,ONLINE_SECURE_SECRET, false); 
		$checksum1 = strtoupper($checksum1);
		
		if($checksum1==$checkSum){
			
			
			// $values['MerchantID']=$response['0'];
			// $values['BankID']=$response['5'];
			// $values['BankMerchantID']=$response['6'];
			// $values['AuthStatus']=$response['14'];
			
			
			$values['orderId']=$response['1']; // orderId provide by syenergy
			$values['TxnReferenceNo']=$response['2']; // transaction no provide by portal
			$values['TxnAmount']=intval($response['4']); // transaction amount provide by portal			
			$values['TxnDate']=$response['13'];			// transaction Date provide by portal
			$values['response']=$str;					// whole response data in the forn of string (not array)
			if($response['14']=='0300'){			
				$values['Status']='1';			
			}
			else{			
				$values['Status']='2';			
			}
			return $values;
			
		
		}
		else{
			
			return $checkSum;
		
		}
		
}
?>

