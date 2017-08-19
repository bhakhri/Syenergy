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

require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
$teacherManager = ScTeacherManager::getInstance();

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
     //$headers = 'From: webmaster@example.com' . "\r\n" ;    
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
       
       $studentEmailsArr=$teacherManager->getStudentEmailList(" WHERE studentId IN(".$REQUEST_DATA['student'].") and studentEmail!=''"); 
       $cnt=count($studentEmailsArr);
       
       $errorMessage = SUCCESS;
       
       if($cnt > 0 and is_array($studentEmailsArr)){
           for($i=0; $i < $cnt ; $i++){
              $email=0;
              if(trim($studentEmailsArr[$i]['studentEmail']) !=""){ 
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
      echo $errorMessage; 
  }
else {
       echo $errorMessage;
}
// $History: scAjaxSendStudentEmail.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
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
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/09/08    Time: 6:52p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
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