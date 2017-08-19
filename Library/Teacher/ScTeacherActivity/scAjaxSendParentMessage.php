<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to parents
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
       
       //gets father mobile and email
       if(trim($REQUEST_DATA['father'])!=""){
        $fatherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['father'].")"); 
        $cntF=count($fatherEmailMobilesArr);
       } 
       //gets mother mobile and email  
       if(trim($REQUEST_DATA['mother'])!=""){
        $motherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['mother'].")"); 
        $cntM=count($motherEmailMobilesArr);
       } 
      //gets guardian mobile and email   
       if(trim($REQUEST_DATA['guardian'])!=""){
        $guardianEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['guardian'].")"); 
        $cntG=count($guardianEmailMobilesArr);
       } 
       
       //calculate msg medium
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']); 
       if($msgMedium[0]==1){
        //calculate and prepare smses based on sms_max_length
        $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);   
       }
       $mCnt=count($msgMedium);
       
       $errorMessage = SUCCESS;
       
       //FATHER SECTION
       if($cntF > 0 and is_array($fatherEmailMobilesArr)){
           $insQuery=""; 
           for($i=0; $i < $cntF ; $i++){
             $sms=0;$email=0;$dashboard=0;  
             if($mCnt > 0 and is_array($msgMedium)){

               if($msgMedium[0]==1){ //if sms
                if(trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!==""){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($fatherEmailMobilesArr[$i]['fatherMobileNo'],strip_tags($smsArr[$s]));
                 } 
              if($ret){$sms=1;}  //if sms sent successful
              else{$sms=0;}
              $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              }
             
             if($msgMedium[1]==1){ //if email
               if(trim($fatherEmailMobilesArr[$i]['fatherEmail'])!==""){
                //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$fatherEmailMobilesArr[$i]['fatherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$fatherEmailMobilesArr[$i]['fatherTitle'].". ".ucwords($fatherEmailMobilesArr[$i]['fatherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                if($emailIds==''){
                   $emailIds=$fatherEmailMobilesArr[$i]['fatherEmail'];
               }
               else{
                   $emailIds=$emailIds.', '. $fatherEmailMobilesArr[$i]['fatherEmail'];
               }
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
           //bulid the insert query for father
           if($insQuery==""){
              $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
          } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  }
//END OF FATHER SECTION  
 
//MOTHER SECTION
 if($cntM > 0 and is_array($motherEmailMobilesArr)){
           $insQuery="";
           for($i=0; $i < $cntM ; $i++){
             $sms=0;$email=0;$dashboard=0;  
             if($mCnt > 0 and is_array($msgMedium)){

               if($msgMedium[0]==1){ //if sms
                if(trim($motherEmailMobilesArr[$i]['motherMobileNo'])!==""){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($motherEmailMobilesArr[$i]['motherMobileNo'],strip_tags($smsArr[$s]));
                 } 
                if($ret){$sms=1;}  //if sms sent successful
                else{$sms=0;}
                $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              }
             
             if($msgMedium[1]==1){ //if email
               if(trim($motherEmailMobilesArr[$i]['motherEmail'])!==""){
                //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$motherEmailMobilesArr[$i]['motherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$motherEmailMobilesArr[$i]['motherTitle'].". ".ucwords($motherEmailMobilesArr[$i]['motherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                if($emailIds==''){
                   $emailIds=$motherEmailMobilesArr[$i]['motherEmail'];
               }
               else{
                   $emailIds=$emailIds.', '. $motherEmailMobilesArr[$i]['motherEmail'];
               }
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
           //bulid the insert query for mother
           if($insQuery==""){
              $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
          } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  }
//END OF MOTHER SECTION
  
 //GUARDIAN SECTION
 if($cntG > 0 and is_array($guardianEmailMobilesArr)){
           $insQuery="";
           for($i=0; $i < $cntG ; $i++){
             $sms=0;$email=0;$dashboard=0;  
             if($mCnt > 0 and is_array($msgMedium)){

               if($msgMedium[0]==1){ //if sms
                if(trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!==""){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($guardianEmailMobilesArr[$i]['guardianMobileNo'],strip_tags($smsArr[$s]));
                 } 
                 if($ret){$sms=1;}  //if sms sent successful
                 else{$sms=0;}
                 $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              }
             
             if($msgMedium[1]==1){ //if email
               if(trim($guardianEmailMobilesArr[$i]['guardianEmail'])!==""){
                //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$guardianEmailMobilesArr[$i]['guardianEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$guardianEmailMobilesArr[$i]['guardianTitle'].". ".ucwords($guardianEmailMobilesArr[$i]['guardianName']).",\r\n".$REQUEST_DATA['msgBody']); 
                if($emailIds==''){
                   $emailIds=$guardianEmailMobilesArr[$i]['guardianEmail'];
               }
               else{
                   $emailIds=$emailIds.', '. $guardianEmailMobilesArr[$i]['guardianEmail'];
               }
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
           //bulid the insert query for guardian
           if($insQuery==""){
              $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
          } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1)";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  } 
//END OF GUARDIAN SECTION

    //Stores emailIds in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailIds',$emailIds);
    
    //Stores senderId in Session for using in mail sending
    $sessionHandler->setSessionVariable('SenderId',$sessionHandler->getSessionVariable('EmployeeEmail') .'< '.$sessionHandler->getSessionVariable('EmployeeName').' >');
    
    //Stores message subject in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailSubject',trim($REQUEST_DATA['msgSubject']));
    
    //Stores message in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailBody',trim($REQUEST_DATA['msgBody'])); 
     
 echo $errorMessage;  
} 
else {
       echo $errorMessage;
}
// $History: scAjaxSendParentMessage.php $
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
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/28/08    Time: 8:01p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/19/08    Time: 4:43p
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