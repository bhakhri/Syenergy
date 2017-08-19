<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ApproveStudentMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/ApproveStudentManager.inc.php");
$studentManager = ApproveStudentManager::getInstance();

$studentString=trim($REQUEST_DATA['studentString']);
if($studentString==''){
    die(NO_DATA_SUBMIT);
}

$studentArray=explode(',',$studentString);
$cnt=count($studentArray);

$studentDupArray=array();

$approavedMailIds='';
$rejectdMailIds='';
$commonMailFrom='admin@univ18.com';
function sendAcknowledgeMail($toEmailIds,$mode){
      global $sessionHandler,$commonMailFrom;
      $to  = $toEmailIds;
      $instituteName=$sessionHandler->getSessionVariable('InstituteName');
      if($mode==1){
       $subject = "Registration Information For $instituteName : Your account is verified";
       $text='Your account is verified by the administrator';
      }
      else{
       $subject = "Registration Information For $instituteName : Your request is rejected";   
       $text='Your request is rejected by the administrator';
      }
      $message = '
            <html>
            <head>
                <title>Registration Information : '.$text.'</title>
            </head>
            <body bgcolor="#E6E6E6">
             Dear Applicant <br/>
              &nbsp;'.$text.'.<br/>
              <b>Note</b> : It is system generated mail.Please do not reply to it.
            </body>
           </html>
      ';

      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      // Additional headers
      //$headers .= "To : dipanjan@im.syenergy.in \r\n";
      $headers .= 'From: '.$commonMailFrom. "\r\n";
      $headers .= "Bcc: ".$to."\r\n";
      // Mail it
      $ret=UtilityManager::sendMail($commonMailFrom, $subject, $message, $headers);
  }

if(SystemDatabaseManager::getInstance()->startTransaction()) { //start transaction
for($i=0;$i<$cnt;$i++){
   $studentInfo=explode('_',$studentArray[$i]);
   $studentId=$studentInfo[0]; 
   $isApproved=$studentInfo[1];
   if($studentId==''){
       die('Student Information Missing');
   }
   if($isApproved==''){
       die('Approval Information Missing');
   }
   
   //check for approved/rejected
   $approaveArray=$studentManager->checkStudentStatus($studentId);
   $studentStatus=$approaveArray[0]['isApproved'];
   if($studentStatus=='1' or $studentStatus=='0'){
       die('Record can not be edited');
   }
   
   $emailId=$approaveArray[0]['emailId'];
   
   if(in_array($studentId,$studentDupArray)){
       die('Duplicate Student Information Found');
   }
   else{
       $studentDupArray[]=$studentId;
   }
   if($isApproved==1){
      //copy data from information table to student table and change his/her role to "Student" 
      $userArray=$studentManager->getUserIdOfStudent($studentId);
      $userId=$userArray[0]['userId'];
      if($userId==''){
          die('User Information Missing');
      }
      $ret=$studentManager->changeStudentRole($userId);
      if($ret==false){
          die(FAILURE);
      }
      //copy data to user_role table
      $ret=$studentManager->copyDataToUserRole($userId);
      if($ret==false){
          die(FAILURE);
      }
      $ret=$studentManager->copyStudentData($studentId,$userId);
      if($ret==false){
          die(FAILURE);
      }
   }
   //update information table
   $ret=$studentManager->updateAdmApplData($studentId,$isApproved);
   if($ret==false){
       die(FAILURE);
   }
   if($isApproved==1){
     if($emailId!=''){
         if($approavedMailIds!=''){
             $approavedMailIds .=', ';
         }
         $approavedMailIds .=$emailId;
     }  
   }
   if($isApproved==0){
      if($emailId!=''){
         if($rejectdMailIds!=''){
             $rejectdMailIds .=', ';
         }
         $rejectdMailIds .=$emailId;
     } 
   }
   
}

if(SystemDatabaseManager::getInstance()->commitTransaction()) {//commit transaction
   
   //**************now send mails to students***************
    if($approavedMailIds!=''){
      sendAcknowledgeMail($approavedMailIds,1);
    }
    if($rejectdMailIds!=''){
      sendAcknowledgeMail($rejectdMailIds,0); 
    }
  //**************now send mails to students***************
   
   die(SUCCESS);
 }
 else {
    die(FAILURE);
  }
}
else{ //if transaction could not start
  die(FAILURE);
}

?>