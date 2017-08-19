<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
$teacherManager = ScTeacherManager::getInstance();

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
    
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgMedium']) || trim($REQUEST_DATA['msgMedium']) == '')) {
    $errorMessage .= 'Select message medium <br/>';
}
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= 'Enter message Body <br/>';
}



$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');
$sms=0;$email=0;$dashboard=0;

$emailIds='';

if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table    
       
       //Stores commentId in Session for using in file uploading
       $sessionHandler->setSessionVariable('IdToFileUpload',$commentId);
       
       $studentEmailMobilesArr=$teacherManager->getStudentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")"); 
       $cnt=count($studentEmailMobilesArr);
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']); 
       if($msgMedium[0]==1){
        //calculate and prepare smses based on sms_max_length
        $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);   
       }
       $mCnt=count($msgMedium);
       $errorMessage = SUCCESS;
       if($cnt > 0 and is_array($studentEmailMobilesArr)){
           for($i=0; $i < $cnt ; $i++){
             $sms=0;$email=0;$dashboard=0;  
             if($mCnt > 0 and is_array($msgMedium)){
               if($msgMedium[0]==1){ //if sms
                if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])!==""){
                $ret=false;    
                for($s=0 ; $s < $smsNo ;$s++){         
                  $ret=sendSMS($studentEmailsArr[$i]['studentMobileNo'],strip_tags($smsArr[$s]));
                 }  
                 if($ret){$sms=1;}  //if sms sent successful
                 else{$sms=0;}
                 $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              else{
                 $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
              } 
             }
             
            if($msgMedium[1]==1){ //if email
               if(trim($studentEmailMobilesArr[$i]['studentEmail'])!==""){   
               //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$studentEmailMobilesArr[$i]['studentEmail'],$REQUEST_DATA['msgSubject'],"Dear ".ucwords($studentEmailMobilesArr[$i]['studentName']).",\r\n".$REQUEST_DATA['msgBody']); 
               if($emailIds==''){
                   $emailIds=$studentEmailMobilesArr[$i]['studentEmail'];
               }
               else{
                   $emailIds=$emailIds.', '. $studentEmailMobilesArr[$i]['studentEmail'];
               }
               $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
             else{
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
              } 
            }   
             
           if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
           }
           
           
           //bulid the insert query
           if($insQuery==""){
              $insQuery="($commentId,".$studentEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,1,0)";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$studentEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,1,0)";
          }
         
         
         if($msgMedium[0]==1){
         //for multiple smss(started from 1 to leave the common record)    
         for($s=1 ; $s < $smsNo ;$s++){     
             if($insQuery==""){
               $insQuery="($commentId,".$studentEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,1,0)";
             }
            else{
               $insQuery =$insQuery." , ($commentId,".$studentEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,1,0)";
             }
          }
        }
               
      }
    }  //end of forloop
    //run the query

    //Stores emailIds in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailIds',$emailIds);
    
    //Stores senderId in Session for using in mail sending
    $sessionHandler->setSessionVariable('SenderId',$sessionHandler->getSessionVariable('EmployeeEmail') .'< '.$sessionHandler->getSessionVariable('EmployeeName').' >');
    
    //Stores message subject in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailSubject',trim($REQUEST_DATA['msgSubject']));
    
    //Stores message in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailBody',trim($REQUEST_DATA['msgBody'])); 
    
    
       
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
// $History: scAjaxSendStudentMessage.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/10/08   Time: 3:55p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Transfer email sending code from ajax files to fileUpload file for
//"Ajax Mail Attachment" functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/04/08   Time: 11:30a
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Added functionality for sending attachment with messages from teacher
//to students and parents
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:11p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Use EmployeeEmail  variable in place of hard coded value
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/28/08    Time: 8:01p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/09/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>