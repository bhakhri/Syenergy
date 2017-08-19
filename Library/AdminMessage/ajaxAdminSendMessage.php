<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
// Author : Dipanjan Bhattacharjee
// Created on : (25.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToNumbers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');

$errorMessage ='';


$userId=$sessionHandler->getSessionVariable('UserId');  
$instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
$sessionId=$sessionHandler->getSessionVariable('SessionId'); 

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (19.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
} 


//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (21.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------  
$smsArr=array();  //will contain smss(each of sms_max_length or less)
function smsCalculation($value,$limit){
 $temp1=$value;
 $nos=1;
 global $smsArr;
 $smsArr[0]=substr($value,0,$limit);
 while(strlen($temp1) > $limit){
     $temp1=substr($temp1,$limit);
     $smsArr[$nos]=substr($temp1,0,$limit);
     $nos=$nos+1;
 }
 return $nos;
} 
    
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= EMPTY_MSG_BODY."\n";   
}
if ($errorMessage == '' && (!isset($REQUEST_DATA['mobileNos']) || trim($REQUEST_DATA['mobileNos']) == '')) {
    $errorMessage .= EMPTY_MOBILE_NOS."\n";   
}

//function for checking duplicate mobile nos
$mArray=array();
function checkDuplicate($val){
   global $mArray;
   $len=count($mArray);
   for($i=0;$i<$len;$i++){
       if($mArray[$i]==$val){
           return 1;
       }
   }
   return 0;
}

if (trim($errorMessage) == '') {
    
	//calculate and prepare smses based on sms_max_length
    $smsNo=smsCalculation(strip_tags(trim($REQUEST_DATA['msgBody'])),SMS_MAX_LENGTH);
	copyHODSendSMS($smsArr[0]);
    $mobileNos=explode(',',trim($REQUEST_DATA['mobileNos']));
    $cnt=count($mobileNos);
    $k=0;
    for($i=0; $i < $cnt ; $i++){
        if(trim($mobileNos[$i])=='' or strlen(trim($mobileNos[$i])<10) or !is_numeric(trim($mobileNos[$i])) ){
            continue;
        }
        
        if(checkDuplicate(trim($mobileNos[$i]))){
            continue;
        }
        else{
          $mArray[$k]=trim($mobileNos[$i]);
          $k++;  
        } 
        for($s=0 ; $s<$smsNo; $s++){         
          $ret=sendSMS(trim($mobileNos[$i]),strip_tags($smsArr[$s]));
        }
    }
    echo SUCCESS;
}
else{
    echo $errorMessage;
}
// $History: ajaxAdminSendMessage.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:14
//Created in $/LeapCC/Library/AdminMessage
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
?>