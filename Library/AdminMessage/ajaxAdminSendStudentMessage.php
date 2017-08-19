<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$systemDatabaseManage=SystemDatabaseManager::getInstance();
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToStudents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');

$errorMessage ='';

require_once(MODEL_PATH . "/SendMessageManager.inc.php");
$sendMessageManager = SendMessageManager::getInstance();


$emailIdString='';
$lastInsertIdsString='';


$userId=$sessionHandler->getSessionVariable('UserId');
$instituteId=$sessionHandler->getSessionVariable('InstituteId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending email
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendEmail($from,$to,$msgSubject,$msgBody){
     //********some problem remains when we set $from varable to ours email(so $from is not used )*********
     //$headers = 'From: webmaster@example.com' . "\r\n" ;
     
     $headers = "MIME-Version: 1.0\r\n" ;
     $headers .= "Content-Type: text/html\r\n";
     $headers .= 'From: '.$from.' '. "\r\n" ;
     //$headers .= 'Content-type: text/html;'; 
     //$headers = 'From: '.$from.' '. "\r\n" ;
     //$headers .= 'Content-type: text/html;';
     return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
}

//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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

if ($errorMessage == '' && (!isset($REQUEST_DATA['msgMedium']) || trim($REQUEST_DATA['msgMedium']) == '')) {
    $errorMessage .= SELECT_MSG_MEDIUM."\n";
}
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= EMPTY_MSG_BODY."\n";
}




$insQuery="";
//$curDate=date('Y')."-".date('m')."-".date('d');
$curDate=date('Y-m-d h:i:s');
$currentDate= date('Y-m-d');
$sms=0;$email=0;

$smsNotSentArray=array();
$emailNotSentArray=array();

if (trim($errorMessage) == '') {
       $studentEmailMobilesArr=$sendMessageManager->getStudentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")");
       $cnt=count($studentEmailMobilesArr);
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']);

       if($msgMedium[0]==1){
          //calculate and prepare smses based on sms_max_length
          $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);
          copyHODSendSMS($smsArr[0]);
       }
       $mCnt=count($msgMedium);
       $errorMessage = SUCCESS;
       if($cnt > 0 and is_array($studentEmailMobilesArr)){
           for($i=0; $i < $cnt ; $i++){
             $sms=0;$email=0;
             if($mCnt > 0 and is_array($msgMedium)){
               if($msgMedium[0]==1){ //if sms
                if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])!="" and trim($studentEmailMobilesArr[$i]['studentMobileNo'])!='NA' and strlen(trim($studentEmailMobilesArr[$i]['studentMobileNo']))>=10){
                $ret=false;
                for($s=0 ; $s < $smsNo ;$s++){
                  $ret=sendSMS($studentEmailMobilesArr[$i]['studentMobileNo'],strip_tags($smsArr[$s]));
                 }
                if($ret){$sms=1;}
                else{$sms=0;$smsNotSentArray[]=$studentEmailMobilesArr[$i]['studentId']; }
                $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              else{
                 $smsNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
                 $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
             }
            if($msgMedium[1]==1){ //if email
               if(trim($studentEmailMobilesArr[$i]['studentEmail'])!="" and HtmlFunctions::getInstance()->isEmail(trim($studentEmailMobilesArr[$i]['studentEmail']))!= 0 ){
                  sendEmail(ADMIN_MSG_EMAIL,$studentEmailMobilesArr[$i]['studentEmail'],$REQUEST_DATA['msgSubject'],"Dear ".ucwords($studentEmailMobilesArr[$i]['studentName']).",\r\n".$REQUEST_DATA['msgBody']);
				  //sendEmail("nishu","nishu@cp.mail.local","checking","sdg");
                  $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                  if($emailIdString!=''){
                     $emailIdString .=',';
                  }
                  $emailIdString .=trim($studentEmailMobilesArr[$i]['studentEmail']);
              }
             else{
                 $emailNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
            }
        }
    }  //end of forloop
    //run the query

    $receiverIds="~".str_replace(",","~",$REQUEST_DATA['student'])."~";
    //build the insert query
     if($msgMedium[1]==1){  //if email
       $insQuery="('".$receiverIds."','Student','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Email','".$currentDate."','".$currentDate."', $userId,$instituteId,$sessionId)";
       $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database
       if($lastInsertIdsString!=''){
         $lastInsertIdsString .=',';
       }
       $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();
     }

     if($msgMedium[2]==1){  //if dashboard
        $insQuery="('".$receiverIds."','Student','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."', $userId,$instituteId,$sessionId)";
        $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database
        if($lastInsertIdsString!=''){
         $lastInsertIdsString .=',';
        }
        $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();
     }

     if($msgMedium[0]==1) {
       //for multiple smss(started from 1 to leave the common record)
        for($s=0 ; $s < $smsNo ;$s++){
          $insQuery="('".$receiverIds."','Student','".$curDate."',' ','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
          $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database
          if($lastInsertIdsString!=''){
            $lastInsertIdsString .=',';
          }
          $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();
        }
     }

     if($returnStatus === false) {
       $errorMessage = FAILURE;
     }
  }

	$sessionHandler->setSessionVariable('IdToFileUpload',$lastInsertIdsString);


    //Stores message medium
    $sessionHandler->setSessionVariable('MsgMedium',$REQUEST_DATA['msgMedium']);

    //Stores file upload info
    $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);

    //Stores emailIds in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailIds',$emailIdString);

    //Stores senderId in Session for using in mail sending
    $sessionHandler->setSessionVariable('SenderId',$sessionHandler->getSessionVariable('EmployeeEmail') .'< '.$sessionHandler->getSessionVariable('EmployeeName').' >');

    //Stores message subject in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailSubject',trim($REQUEST_DATA['msgSubject']));

    //Stores message in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailBody',trim($REQUEST_DATA['msgBody']));

	$smsNotSent=implode(",",$smsNotSentArray);
	$emailNotSent=implode(",",$emailNotSentArray);
	if($smsNotSent!='') {
	  $lastMsgId=$systemDatabaseManage->lastInsertId();
	  $result= $sendMessageManager->insertIntoAdminMsgsFailed($lastMsgId,'Student',$curDate,$smsNotSent,$REQUEST_DATA['msgSubject'],$REQUEST_DATA['msgBody'],'SMS');
	  if($result == false){
	  $errorMessage = FAILURE;
	  }
   }


  if($smsNotSent!='') {
    $sessionHandler->setSessionVariable('smsStudentIds',$smsNotSent);
    $smsNotSent ='1';
  }
  else {
     $sessionHandler->setSessionVariable('smsStudentIds','-1');
     $smsNotSent ='';
  }

  if($emailNotSent!='') {
    $sessionHandler->setSessionVariable('emailStudentIds',$emailNotSent);
    $emailNotSent ='1';
  }
  else {
     $sessionHandler->setSessionVariable('emailStudentIds','-1');
     $emailNotSent ='';
  }
  //if( !($smsNotSent == '' || $emailNotSent == '') ) {
	echo $errorMessage.'!~!~!'.$smsNotSent.'!~!~!'.$emailNotSent;
  //}
  //else {
	//echo $errorMessage;
  //}
}
else {
       echo $errorMessage;
}
// $History: ajaxAdminSendStudentMessage.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 3/03/10    Time: 2:40p
//Updated in $/LeapCC/Library/AdminMessage
//set sessionvariable base code updated
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminMessage
//Added Role Permission Variables
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/05/09    Time: 3:39p
//Updated in $/LeapCC/Library/AdminMessage
//mobile conditon update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/05/09    Time: 1:25p
//Updated in $/LeapCC/Library/AdminMessage
//validation added (phone length & valid email checks)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/04/09    Time: 7:17p
//Updated in $/LeapCC/Library/AdminMessage
//create document list (No messages send Information)
//
//*****************  Version 4  *****************
//User: Administrator Date: 1/06/09    Time: 17:32
//Updated in $/LeapCC/Library/AdminMessage
//Added the functionality : sms & email not sent to how many students
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/15/08   Time: 5:40p
//Updated in $/LeapCC/Library/AdminMessage
//added define('MANAGEMENT_ACCESS',1) Parameter
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Library/AdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AdminMessage
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:11p
//Updated in $/Leap/Source/Library/AdminMessage
//Use ADMIN_MSG_EMAIL variable in place of hard coded value
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/03/08   Time: 11:42a
//Updated in $/Leap/Source/Library/AdminMessage
//Corrected "From" field in mail sending and employee name field
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/22/08    Time: 12:17p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/28/08    Time: 3:47p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:52p
//Updated in $/Leap/Source/Library/AdminMessage
//Added Standard Messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:34p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/16/08    Time: 5:30p
//Updated in $/Leap/Source/Library/AdminMessage
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:04p
//Created in $/Leap/Source/Library/AdminMessage
?>