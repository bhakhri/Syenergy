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
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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

//check for missing request data    
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
     $errorMessage .= 'Enter Message Body <br/>';
}

//calculate and prepare smses based on sms_max_length
$smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);
copyHODSendSMS($smsArr[0]);

	
$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');   
if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table
       $errorMessage = SUCCESS; 
       //For Students
      if(trim($REQUEST_DATA['student'])!="") { //if student checboxes are checked
       $studentEmailsArr=$teacherManager->getStudentMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student']." )"); 
       //print_r($studentEmailsArr);
       $cnt=count($studentEmailsArr);
       $sms=0;
       if($cnt > 0 and is_array($studentEmailsArr)){
           for($i=0; $i < $cnt ; $i++){
             if(trim($studentEmailsArr[$i]['studentMobileNo'])!="") {
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
  }

//FATHER SECTION
   if(trim($REQUEST_DATA['father'])!="") { //if father checboxes are checked  
     $insQuery="";
     $sms=0;    
     
    $fatherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['father'].")"); 
    $cntF=count($fatherEmailMobilesArr);
    if($cntF > 0 and is_array($fatherEmailMobilesArr)){
        for($i=0; $i < $cntF ; $i++){
           $sms=0; 
           if(trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!==""){
              $ret=false; 
              for($s=0 ; $s < $smsNo ;$s++){      
               $ret=sendSMS($fatherEmailMobilesArr[$i]['fatherMobileNo'],strip_tags($smsArr[$s])); 
              } 
              if($ret){$sms=1;}  //if sms sent successful
              else{$sms=0;}
              $visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
         for($s=0 ; $s < $smsNo ;$s++){
         if($insQuery==""){
            $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
         }
         else{
            $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
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
 }        
//FATHER SECTION ENDS

//MOTHER SECTION
   if(trim($REQUEST_DATA['mother'])!="") { //if mother checboxes are checked  
     $insQuery="";
     $sms=0;    
     
    $motherEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['mother'].")"); 
    $cntM=count($motherEmailMobilesArr);
    if($cntM > 0 and is_array($motherEmailMobilesArr)){
        for($i=0; $i < $cntM ; $i++){
           $sms=0; 
           if(trim($motherEmailMobilesArr[$i]['motherMobileNo'])!==""){
              $ret=false; 
              for($s=0 ; $s < $smsNo ;$s++){      
               $ret=sendSMS($motherEmailMobilesArr[$i]['motherMobileNo'],strip_tags($smsArr[$s])); 
              }
              if($ret){$sms=1;}  //if sms sent successful
              else{$sms=0;} 
              $visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
         for($s=0 ; $s < $smsNo ;$s++){
         if($insQuery==""){
            $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
         }
         else{
            $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
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
 }        
//MOTHER SECTION ENDS 

//GUARDIAN SECTION
   if(trim($REQUEST_DATA['guardian'])!="") { //if guardian checboxes are checked  
     $insQuery="";
     $sms=0;    
     
    $guardianEmailMobilesArr=$teacherManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['guardian'].")"); 
    $cntG=count($guardianEmailMobilesArr);
    if($cntG > 0 and is_array($guardianEmailMobilesArr)){
        for($i=0; $i < $cntG ; $i++){
           $sms=0; 
           if(trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!==""){
              $ret=false; 
              for($s=0 ; $s < $smsNo ;$s++){      
               $ret=sendSMS($guardianEmailMobilesArr[$i]['guardianMobileNo'],strip_tags($smsArr[$s])); 
              } 
              if($ret){$sms=1;}  //if sms sent successful
              else{$sms=0;}
              $visibleFromDate=$curDate;$visibleToDate=$curDate;
             }
         for($s=0 ; $s < $smsNo ;$s++){
         if($insQuery==""){
            $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
         }
         else{
            $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,0,0,0,1)";
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
 }        
//guardian SECTION ENDS  

      echo $errorMessage; 
  }
else {
       echo $errorMessage;
}
// $History: ajaxSendBulkSMS.php $
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