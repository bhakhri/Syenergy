<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to student & parents
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
     ////$headers = 'From: webmaster@example.com' . "\r\n" ;    
$headers = 'From: '.$from.' '. "\r\n" ;
	 $headers = 'From: '.$from.' '. "\r\n" ;
     $headers .= 'Content-type: text/html;'; 
     return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
} 
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
        $errorMessage .= 'Enter E-mail Body <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['msgSubject']) || trim($REQUEST_DATA['msgSubject']) == '')) {
        $errorMessage .= 'Enter E-mail Subject <br/>';
    }

    
$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');   
if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table
       $errorMessage = SUCCESS; 
       //For Students
      if(trim($REQUEST_DATA['student'])!="") { //if student checboxes are checked
       $studentEmailsArr=$teacherManager->getStudentEmailList(" WHERE studentId IN(".$REQUEST_DATA['student'].")"); 
       $cnt=count($studentEmailsArr);
       if($cnt > 0 and is_array($studentEmailsArr)){
           $email=0;
           for($i=0; $i < $cnt ; $i++){
             if(trim($studentEmailsArr[$i]['studentEmail'])!=""){  
              sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$studentEmailsArr[$i]['studentEmail'],$REQUEST_DATA['msgSubject'],"Dear ".ucwords($studentEmailsArr[$i]['studentName']).",\r\n".$REQUEST_DATA['msgBody']); 
              $email=1;
             }
            else{
                $email=0;                                                                              
            }  
              
            if($insQuery==""){
                $insQuery="($commentId,".$studentEmailsArr[$i]['studentId']." , '".$curDate."','".$curDate."',0,$email,0,1,0)";
             }
            else{
                $insQuery =$insQuery." , ($commentId,".$studentEmailsArr[$i]['studentId']." , '".$curDate."','".$curDate."',0,$email,0,1,0)";
           }     
         }
         if($insQuery!=""){
            $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
         }
        if($returnStatus === false) {
                $errorMessage = FAILURE;
        } 
     }
  }
    //for Parents

 //FATHER SECTION    
   if(trim($REQUEST_DATA['father'])!="") { //if father checboxes are checked  
   $insQuery="";
   $email=0;   
   
    $fatherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['father'].")"); 
    $cntF=count($fatherEmailMobilesArr);
    if($cntF > 0 and is_array($fatherEmailMobilesArr)){
        for($i=0; $i < $cntF ; $i++){
           $email=0; 
           if(trim($fatherEmailMobilesArr[$i]['fatherEmail'])!==""){
              sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$fatherEmailMobilesArr[$i]['fatherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$fatherEmailMobilesArr[$i]['fatherTitle'].". ".ucwords($fatherEmailMobilesArr[$i]['fatherName']).",\r\n".$REQUEST_DATA['msgBody']); 
              $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
        if($insQuery==""){
            $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
         }
        else{
            $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
        }   
      }
     if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
     }
     if($returnStatus === false) {
        $errorMessage = FAILURE;
     }  
   }    
 }        
//FATHER SECTION ENDS 


//MOTHER SECTION    
   if(trim($REQUEST_DATA['mother'])!="") { //if mother checboxes are checked  
   $insQuery="";
   $email=0;   
   $motherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['mother'].")"); 
    $cntM=count($motherEmailMobilesArr);
    if($cntM > 0 and is_array($motherEmailMobilesArr)){
        for($i=0; $i < $cntM ; $i++){
           $email=0; 
           if(trim($motherEmailMobilesArr[$i]['motherEmail'])!==""){
              sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$motherEmailMobilesArr[$i]['motherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$motherEmailMobilesArr[$i]['motherTitle'].". ".ucwords($motherEmailMobilesArr[$i]['motherName']).",\r\n".$REQUEST_DATA['msgBody']); 
              $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
        if($insQuery==""){
            $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
         }
        else{
            $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
        }   
      }
     if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
     }
     if($returnStatus === false) {
        $errorMessage = FAILURE;
     }  
   }    
 }        
//MOTHER SECTION ENDS


//GUARDIAN SECTION    
   if(trim($REQUEST_DATA['guardian'])!="") { //if guardian checboxes are checked  
   $insQuery="";
   $email=0;   
   
    $guardianEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['guardian'].")"); 
    $cntG=count($guardianEmailMobilesArr);
    if($cntG > 0 and is_array($guardianEmailMobilesArr)){
        for($i=0; $i < $cntG ; $i++){
           $email=0; 
           if(trim($guardianEmailMobilesArr[$i]['guardianEmail'])!==""){
              sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$guardianEmailMobilesArr[$i]['guardianEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$guardianEmailMobilesArr[$i]['guardianTitle'].". ".ucwords($guardianEmailMobilesArr[$i]['guardianName']).",\r\n".$REQUEST_DATA['msgBody']); 
              $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
        if($insQuery==""){
            $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
         }
        else{
            $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',0,$email,0,0,1)";
        }   
      }
     if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
     }
     if($returnStatus === false) {
        $errorMessage = FAILURE;
     }  
   }    
 }        
//GUARDIAN SECTION ENDS

 
 
      echo $errorMessage; 
  }
else {
       echo $errorMessage;
}
// $History: ajaxSendBulkEmail.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:11p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Use ADMIN_MSG_EMAIL variable in place of hard coded value
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
//User: Dipanjan     Date: 8/09/08    Time: 6:05p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>