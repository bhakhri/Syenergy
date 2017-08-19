<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SendMessageToStudents');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(BL_PATH.'/HtmlFunctions.inc.php');

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance();

$teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
// $conditions :db clauses
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
  // $conditions :db clauses
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


$emailNotSentArray=array();
$dashBoardNotSentArray=array();

$insQuery=""; 
$curDate=date('Y')."-".date('m')."-".date('d');
$sms=0;$email=0;$dashboard=0;

if (trim($errorMessage) == '') {
       $commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table
	   if ($REQUEST_DATA['student'] == '') {
		   $REQUEST_DATA['student'] = 0;
	   }
       
       $studentEmailMobilesArr=$teacherManager->getStudentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")"); 
       $cnt=count($studentEmailMobilesArr);
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']);
       
       if($sessionHandler->getSessionVariable('TEACHER_SMS_STUDENTS')!=1){
           $msgMedium[0]=0 ;      //if sms permission is not given
       }
       
       if($msgMedium[0]==1){
        //calculate and prepare smses based on sms_max_length
        $smsNo=smsCalculation(strip_tags($REQUEST_DATA['msgBody']),SMS_MAX_LENGTH);   
		copyHODSendSMS($smsArr[0]);
       }
       $mCnt=count($msgMedium);
       $errorMessage = SUCCESS;
       if($cnt > 0 and is_array($studentEmailMobilesArr)){
		   
           for($i=0; $i < $cnt ; $i++){
             $sms=0;$email=0;$dashboard=0;  
             if($mCnt > 0 and is_array($msgMedium)){
               if($msgMedium[0]==1){ //if sms
                if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])!="" and trim($studentEmailMobilesArr[$i]['studentMobileNo'])!='NA' and strlen(trim($studentEmailMobilesArr[$i]['studentMobileNo']))>=10 ){
                $ret=false;    
                for($s=0 ; $s < $smsNo ;$s++){         
                  $ret=sendSMS($studentEmailsArr[$i]['studentMobileNo'],strip_tags($smsArr[$s]));
                 }  
                if($ret){$sms=1;}  //if sms sent successful
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
                 sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$studentEmailMobilesArr[$i]['studentEmail'],$REQUEST_DATA['msgSubject'],"Dear ".ucwords($studentEmailMobilesArr[$i]['studentName']).",\r\n".$REQUEST_DATA['msgBody']); 
                 $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
               else{
                 $emailNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
            }   
           if($msgMedium[2]==1){ //if dashboard
               $dashboard=1;$visibleFromDate=$REQUEST_DATA['visibleFrom'];$visibleToDate=$REQUEST_DATA['visibleTo'];
           }
           else {
               $dashBoardNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
               $dashboard=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
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

    if($insQuery!=""){
        $teacherManager->studentEmailSMSRecord($insQuery); //add the record in database 
    }
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  }
  
  $emailNotSent=implode(",",$emailNotSentArray);
  
  $sessionHandler->setSessionVariable('smsStudentIds','-1');  
  if($emailNotSent!='') {
    $sessionHandler->setSessionVariable('emailStudentIds',$emailNotSent);
    $emailNotSent ='1';
  }
  else {
     $sessionHandler->setSessionVariable('emailStudentIds','-1');  
     $emailNotSent =''; 
  }   

  echo $errorMessage.'!~!~!'.$emailNotSent; 
} 
else {
       echo $errorMessage;
}
// $History: ajaxSendStudentMessage.php $
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
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/03/08   Time: 6:11p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Use ADMIN_MSG_EMAIL variable in place of hard coded value
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