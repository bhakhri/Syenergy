<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
}

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

$valueArray=array();
$error='';
if (trim($errorMessage) == '') {
    //calculate and prepare smses based on sms_max_length
    $smsNo=smsCalculation(strip_tags(trim($REQUEST_DATA['msgBody'])),SMS_MAX_LENGTH);
    $mobileNos=explode(',',trim($REQUEST_DATA['mobileNos']));
    $cnt=count($mobileNos);
    for($i=0; $i < $cnt ; $i++){
        if(trim($mobileNos[$i])=='' or strlen(trim($mobileNos[$i])<10) or !is_numeric(trim($mobileNos[$i])) ){
            $error=' ( Invalid Mobile No. )';
        }
        $valueArray[$i]['srNo']=($i+1);
        $valueArray[$i]['mobileNo']=trim($mobileNos[$i]).$error;
        $valueArray[$i]['sms']=$smsNo;
        $error='';
    }
}
else{
    echo $errorMessage;
    die;
}

	$csvData = '';
    $csvData .= "Sr, Mobile No., SMS(s)\n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['mobileNo']).', '.parseCSVComments($record['sms']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="testType.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: adminSendMessageCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:14
//Created in $/LeapCC/Templates/AdminMessage
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
?>