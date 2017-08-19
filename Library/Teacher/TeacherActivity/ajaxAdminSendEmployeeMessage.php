<?php
//------------------------------------------------------------------------
// THIS FILE IS USED TO send message to emloyees by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMessageMaster');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(BL_PATH.'/HtmlFunctions.inc.php');    

require_once(MODEL_PATH . "/SendMessageManager.inc.php");
$sendMessageManager = SendMessageManager::getInstance();

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


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending email 
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
function sendEmail($from,$to,$msgSubject,$msgBody){
     //********some problem remains when we set $from varable to ours email(so $from is not used )*********
     //$headers = 'From: webmaster@example.com' . "\r\n" ;    
     $headers = 'From: '.$from.' '. "\r\n" ;
     $headers .= 'Content-type: text/html;'; 
     return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
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
    
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgMedium']) || trim($REQUEST_DATA['msgMedium']) == '')) {
    $errorMessage .= SELECT_MSG_MEDIUM."\n";   
}
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= EMPTY_MSG_BODY."\n";   
}


$smsNotSentArray=array();
$emailNotSentArray=array();
$dashBoardNotSentArray=array();

$insQuery=""; 
//$curDate=date('Y')."-".date('m')."-".date('d');
$curDate=date('Y-m-d h:i:s');
$currentDate=date('Y-m-d');
$sms=0;$email=0;

if (trim($errorMessage) == '') {
       $employeeEmailMobilesArr=$sendMessageManager->getEmployeeEmailMobileNoList(" WHERE employeeId IN(".$REQUEST_DATA['emp'].")"); 
       $cnt=count($employeeEmailMobilesArr);
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']);
       
       if($sessionHandler->getSessionVariable('TEACHER_SMS_COLLEAGUES')!=1){
           $msgMedium[0]=0 ;      //if sms permission is not given
       }
       
       if($msgMedium[0]==1){
          //calculate and prepare smses based on sms_max_length
          $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);   
		  copyHODSendSMS($smsArr[0]);
       }
       $mCnt=count($msgMedium);
       $errorMessage = SUCCESS;
       if($cnt > 0 and is_array($employeeEmailMobilesArr)){
           for($i=0; $i < $cnt ; $i++){
             $sms=0;$email=0;
             if($mCnt > 0 and is_array($msgMedium)){
               if($msgMedium[0]==1){ //if sms
                if(trim($employeeEmailMobilesArr[$i]['mobileNumber'])!="" and trim($employeeEmailMobilesArr[$i]['mobileNumber'])!='NA'  and strlen(trim($employeeEmailMobilesArr[$i]['mobileNumber']))>=10){
                $ret=false;    
                for($s=0 ; $s < $smsNo ;$s++){         
                  //sendSMS($employeeEmailMobilesArr[$i]['mobileNumber'],strip_tags($REQUEST_DATA['msgBody']));
                  $ret=sendSMS($employeeEmailMobilesArr[$i]['mobileNumber'],strip_tags($smsArr[$s]));
                 }  
                if($ret){$sms=1;}
                else{$sms=0;$smsNotSentArray[]=$employeeEmailMobilesArr[$i]['employeeId']; }       
                $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              else{
                 $smsNotSentArray[]=$employeeEmailMobilesArr[$i]['employeeId'];   
                 $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
              } 
            }
             
            if($msgMedium[1]==1){ //if email
               if(trim($employeeEmailMobilesArr[$i]['emailAddress'])!="" and HtmlFunctions::getInstance()->isEmail(trim($employeeEmailMobilesArr[$i]['emailAddress']))!= 0 ){   
               sendEmail(ADMIN_MSG_EMAIL,$employeeEmailMobilesArr[$i]['emailAddress'],$REQUEST_DATA['msgSubject'],"Dear ".ucwords($employeeEmailMobilesArr[$i]['employeeName']).",\r\n".$REQUEST_DATA['msgBody']); 
               $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
              else{
                 $emailNotSentArray[]=$employeeEmailMobilesArr[$i]['employeeId'];  
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
              } 
             } 
             
             if($msgMedium[2]==1){ //if dashboard
                $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
             }
             else {
               $dashBoardNotSentArray[]=$employeeEmailMobilesArr[$i]['employeeId'];
               $dashboard=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
             }
        }
    }  //end of forloop
    //run the query
    
    $receiverIds="~".str_replace(",","~",$REQUEST_DATA['emp'])."~";
    //build the insert query
     if($msgMedium[1]==1){  //if email
      $insQuery="('".$receiverIds."','Employee','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Email','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
     }  
     
     if($msgMedium[2]==1){  //if dashboard
     if($insQuery==""){   
      $insQuery="('".$receiverIds."','Employee','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."',$userId,$instituteId,$sessionId)";
      } 
     else{
         $insQuery =$insQuery." ,('".$receiverIds."','Employee','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."',$userId,$instituteId,$sessionId)";
      }  
    }  
     
     if($msgMedium[0]==1){
     //for multiple smss(started from 1 to leave the common record)    
     for($s=0 ; $s < $smsNo ;$s++){     
        if($insQuery==""){
            $insQuery="('".$receiverIds."','Employee','".$curDate."',' ','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
         }
       else{
            $insQuery =$insQuery." ,('".$receiverIds."','Student','".$curDate."',' ','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
         }
       }
     }

    if($insQuery!=""){
        $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  }
  
  $smsNotSent=implode(",",$smsNotSentArray);   
  $emailNotSent=implode(",",$emailNotSentArray);
  if($smsNotSent!='') {
    $sessionHandler->setSessionVariable('smsEmployeeIds',$smsNotSent);
    $smsNotSent ='1';   
  }
  else {
    $sessionHandler->setSessionVariable('smsEmployeeIds','-1');
    $smsNotSent ='';   
  }                                                                                          
     
  if($emailNotSent!='') {
    $sessionHandler->setSessionVariable('emailEmployeeIds',$emailNotSent);
    $emailNotSent ='1';
  }
  else {
     $sessionHandler->setSessionVariable('emailEmployeeIds','-1');  
     $emailNotSent ='';
  }   
  
  echo $errorMessage.'!~!~!'.$emailNotSent.'!~!~!'.$smsNotSent;  
} 
else {
       echo $errorMessage;
}
// $History: ajaxAdminSendEmployeeMessage.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/03/10    Time: 2:40p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//set sessionvariable base code updated 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/08/09    Time: 2:57p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//notSendMessageDetail functionality added
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 18:30
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "SMS" restriction codes
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:49
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added the functioanality "Send Message to Colleagues" In Teacher
//Section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:14
//Created in $/SnS/Library/Teacher/TeacherActivity
//Added the functionaility of send message from teacher end
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Library/AdminMessage
//Added Sns System to VSS(Leap for Chitkara International School)
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