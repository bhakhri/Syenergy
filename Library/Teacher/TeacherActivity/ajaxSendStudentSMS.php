<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to student & parents
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to overcome the time taken for sending Email
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance();

    $errorMessage ='';
 
 $teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId

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

//check for missing request data      
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= 'SMS Cannot be Empty <br/>';
}

//calculate and prepare smses based on sms_max_length
$smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);
    
$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');   
if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table    
    
       $studentEmailsArr=$teacherManager->getStudentMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")"); 
       $cnt=count($studentEmailsArr);
       
       $errorMessage = SUCCESS;
       
       if($cnt > 0 and is_array($studentEmailsArr)){
		   copyHODSendSMS($smsArr[0]);
           for($i=0; $i < $cnt ; $i++){
              $sms=0;
              if(trim($studentEmailsArr[$i]['studentMobileNo']) !="" ){ 
               $ret=false;   
               for($s=0 ; $s < $smsNo ;$s++){     
                $ret=sendSMS($studentEmailsArr[$i]['studentMobileNo'],strip_tags($smsArr[$s])); 
               } 
               if($ret){$sms=1;}  //if sms sent successful
               else{$sms=0;}
              }
             else{
               $sms=0;  
             }  
           //db entry will made upon no of smses 
           for($s=0 ; $s < $smsNo ;$s++){   
             if($insQuery==""){
                $insQuery="($commentId,".$studentEmailsArr[$i]['studentId']." , '".$curDate."','".$curDate."',$sms,0,0,1,0)";
              }
             else{
                $insQuery =$insQuery." , ($commentId,".$studentEmailsArr[$i]['studentId']." , '".$curDate."','".$curDate."',$sms,0,0,1,0)";
            }     
           } 
         }
         if($insQuery!=""){
            $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
         }
        if($returnStatus === false) {
                $errorMessage = FAILURE;
        } 
       }
      echo $errorMessage; 
  }
else {
       echo $errorMessage;
}
// $History: ajaxSendStudentSMS.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 8:01p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/09/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/19/08    Time: 6:24p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>