<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to parents
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
define('MODULE','SendMessageToParents');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');

$errorMessage ='';

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance();

$teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId    

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
    $errorMessage .= 'Select message medium <br/>';
}
if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= 'Enter message Body <br/>';
}

$smsNotSentArrayF=array();
$emailNotSentArrayF=array();
$dashBoardNotSentArrayF=array();

$smsNotSentArrayM=array();
$emailNotSentArrayM=array();
$dashBoardNotSentArrayM=array();

$smsNotSentArrayG=array();
$emailNotSentArrayG=array();
$dashBoardNotSentArrayG=array();


$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');
$sms=0;$email=0;$dashboard=0;

if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table    
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
       
       if($sessionHandler->getSessionVariable('TEACHER_SMS_PARENTS')!=1){
           $msgMedium[0]=0 ;      //if sms permission is not given
       }
       
       if($msgMedium[0]==1){
          //calculate and prepare smses based on sms_max_length
          $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);   
  	      copyHODSendSMS($smsArr[0]);
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
                if(trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!="" and trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!='NA' and strlen(trim($fatherEmailMobilesArr[$i]['fatherMobileNo']))>=10 ){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($fatherEmailMobilesArr[$i]['fatherMobileNo'],strip_tags($smsArr[$s]));
                 } 
              
                if($ret){$sms=1;}  //if sms sent successful
                else{$sms=0;$smsNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId']; }    
                    $visibleFromDate=$curDate;$visibleToDate=$curDate;
                }
                else{
                    $smsNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId']; 
                    $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
                } 
             }
             
             if($msgMedium[1]==1){ //if email
               if(trim($fatherEmailMobilesArr[$i]['fatherEmail'])!="" and HtmlFunctions::getInstance()->isEmail(trim($fatherEmailMobilesArr[$i]['fatherEmail']))!= 0 ){
                sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$fatherEmailMobilesArr[$i]['fatherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$fatherEmailMobilesArr[$i]['fatherTitle'].". ".ucwords($fatherEmailMobilesArr[$i]['fatherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                }
               else{
                 $emailNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
            else {
               $dashBoardNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId'];
               $dashboard=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
            }
            
            //bulid the insert query for father
            if($insQuery==""){
              $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Father')";
            }
            else{
              $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Father')";
            } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Father')";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$fatherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Father')";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery,"Father"); //add the record in database 
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
                if(trim($motherEmailMobilesArr[$i]['motherMobileNo'])!=""  and trim($motherEmailMobilesArr[$i]['motherMobileNo'])!='NA' and strlen(trim($motherEmailMobilesArr[$i]['motherMobileNo']))>=10  ){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($motherEmailMobilesArr[$i]['motherMobileNo'],strip_tags($smsArr[$s]));
                 }
                 
                if($ret){$sms=1;}  //if sms sent successful
                else{$sms=0;$smsNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId']; }    
                    $visibleFromDate=$curDate;$visibleToDate=$curDate;
                }
                else{
                    $smsNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId']; 
                    $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
                } 
             }
             if($msgMedium[1]==1){ //if email
               if(trim($motherEmailMobilesArr[$i]['motherEmail'])!="" and HtmlFunctions::getInstance()->isEmail(trim($motherEmailMobilesArr[$i]['motherEmail']))!= 0){
                sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$motherEmailMobilesArr[$i]['motherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$motherEmailMobilesArr[$i]['motherTitle'].". ".ucwords($motherEmailMobilesArr[$i]['motherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
               else{
                 $emailNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
            else {
               $dashBoardNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId'];
               $dashboard=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;    
            }
            
           //bulid the insert query for mother
           if($insQuery==""){
              $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Mother')";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Mother')";
          } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Mother')";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$motherEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Mother')";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery,"Mother"); //add the record in database 
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
                if(trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!=""  and trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!='NA' and strlen(trim($guardianEmailMobilesArr[$i]['guardianMobileNo']))>=10 ){
                 $ret=false;   
                 for($s=0 ; $s < $smsNo ;$s++){    
                  $ret=sendSMS($guardianEmailMobilesArr[$i]['guardianMobileNo'],strip_tags($smsArr[$s]));
                 }
                if($ret){$sms=1;}  //if sms sent successful
                else{$sms=0;$smsNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId']; }    
                    $visibleFromDate=$curDate;$visibleToDate=$curDate;
                }
                else{
                    $smsNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId']; 
                    $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
                } 
             }
             
             if($msgMedium[1]==1){ //if email
               if(trim($guardianEmailMobilesArr[$i]['guardianEmail'])!=""  and HtmlFunctions::getInstance()->isEmail(trim($guardianEmailMobilesArr[$i]['guardianEmail']))!= 0 ){
                sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$guardianEmailMobilesArr[$i]['guardianEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$guardianEmailMobilesArr[$i]['guardianTitle'].". ".ucwords($guardianEmailMobilesArr[$i]['guardianName']).",\r\n".$REQUEST_DATA['msgBody']); 
                $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
                else{
                 $emailNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }   
             
            if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
            }
            else {
               $dashBoardNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId'];
               $dashboard=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;    
            }
           //bulid the insert query for guardian
           if($insQuery==""){
              $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Guardian')";
           }
          else{
              $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Guardian')";
          } 
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=1 ; $s < $smsNo ;$s++){   
           if($insQuery==""){
               $insQuery="($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Guardian')";
            }
           else{
              $insQuery =$insQuery." , ($commentId,".$guardianEmailMobilesArr[$i]['studentId']." , '".$visibleFromDate."','".$visibleToDate."',$sms,$email,$dashboard,0,1,'Guardian')";
           } 
          } 
         } 
       }
    }  //end of forloop
    //run the query

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery,"Guardian"); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  } 
  //END OF GUARDIAN SECTION
  
  $smsNotSentF=implode(",",$smsNotSentArrayF);   
  $emailNotSentF=implode(",",$emailNotSentArrayF);
 
  $smsNotSentM=implode(",",$smsNotSentArrayM);   
  $emailNotSentM=implode(",",$emailNotSentArrayM);
 
  $smsNotSentG=implode(",",$smsNotSentArrayG);   
  $emailNotSentG=implode(",",$emailNotSentArrayG);
 
  if($smsNotSentF!='') {
    $sessionHandler->setSessionVariable('smsFatherIds',$smsNotSentF);
    $smsNotSentF ='1';   
  }
  else {
    $sessionHandler->setSessionVariable('smsFatherIds','-1');  
  }
     
  if($emailNotSentF!='') {
    $sessionHandler->setSessionVariable('emailFatherIds',$emailNotSentF);
    $emailNotSentF ='1';
  }
  else {
    $sessionHandler->setSessionVariable('emailFatherIds','-1');  
  }
  
  if($smsNotSentM!='') {
    $sessionHandler->setSessionVariable('smsMotherIds',$smsNotSentM);
    $smsNotSentM ='1';   
  }
  else {
    $sessionHandler->setSessionVariable('smsMotherIds','-1');  
  }
     
  if($emailNotSentM!='') {
    $sessionHandler->setSessionVariable('emailMotherIds',$emailNotSentM);
    $emailNotSentM ='1';
  }
  else {
    $sessionHandler->setSessionVariable('emailMotherIds','-1');  
  }
  
   if($smsNotSentG!='') {
    $sessionHandler->setSessionVariable('smsGuardianIds',$smsNotSentG);
    $smsNotSentG ='1';   
  }
  else {
    $sessionHandler->setSessionVariable('smsGuardianIds','-1');  
  }
     
  if($emailNotSentG!='') {
    $sessionHandler->setSessionVariable('emailGuardianIds',$emailNotSentG);
    $emailNotSentG ='1';
  }
  else {
    $sessionHandler->setSessionVariable('emailGuardianIds','-1');  
  }
  
  //echo $errorMessage;  
  echo $errorMessage.'!~!~!'.$emailNotSentF.'!~!~!'.$emailNotSentM.'!~!~!'.$emailNotSentG.'!~!~!'.$smsNotSentF.'!~!~!'.$smsNotSentM.'!~!~!'.$smsNotSentG;
} 
else {
       echo $errorMessage;
}
// $History: ajaxSendParentMessage.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/03/10    Time: 2:40p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//set sessionvariable base code updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/10/09    Time: 2:48p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//parentType filed added record save
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
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:11p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Use ADMIN_MSG_EMAIL variable in place of hard coded value
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