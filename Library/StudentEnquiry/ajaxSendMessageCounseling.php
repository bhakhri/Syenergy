<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to student & parents
//
//
// Author : Parveen Sharma
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';

    $counselingId  = trim($REQUEST_DATA['counselor']);
    $startDate  = trim($REQUEST_DATA['startDate']);
    $endDate    = trim($REQUEST_DATA['endDate']);
    $studentArr = trim($REQUEST_DATA['studentId']);
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending sms to parent
//
//$conditions :db clauses
// Author :Parveen Sharma 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
function sendSms($toMobileNo,$msgBody){
     //to be implemented
     return true;
} 

    if ($errorMessage == '' && (!isset($REQUEST_DATA['msgMedium']) || trim($REQUEST_DATA['msgMedium']) == '')) {
        $errorMessage .= 'Select message medium <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
        $errorMessage .= 'Enter message Body <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['visibleFrom']) || trim($REQUEST_DATA['visibleFrom']) == '')) {
        $errorMessage .= 'Enter visible from date <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['visibleTo']) || trim($REQUEST_DATA['visibleTo']) == '')) {
        $errorMessage .= 'Enter visible to date <br/>';
    }
    
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SendMessageManager.inc.php");
        $sendMessageManager = SendMessageManager::getInstance();  
        
        $parent=split(",",$REQUEST_DATA['parent']);
        $student=split(",",$REQUEST_DATA['student']);
        $msgMedium=split(",",$REQUEST_DATA['msgMedium']);
        
        if(!empty($REQUEST_DATA['student'])){
           $studentInfo=$sendMessageManager->getStudentInfo($REQUEST_DATA['student']); //get mobilno and email of students
         }
        else{
           $studentInfo=$sendMessageManager->getStudentInfo(0); //get mobilno and email of students
          }
    
        if(!empty($REQUEST_DATA['parent'])){ 
          $parentInfo=$sendMessageManager->getParentInfo($REQUEST_DATA['parent']);        //get mobilno and email of parents 
         }
        else{
           $parentInfo=$sendMessageManager->getParentInfo(0);   //get mobilno and email of parents 
        }
       
        $pCount=count($parentInfo);
        $sCount=count($studentInfo);
        
        //sent sms
     if($msgMedium[0]=="1"){
        if(count($parent)>0 && is_array($parent)){
            for($i=0;$i<$pCount;$i++){
             if(!empty($parentInfo[$i]['fatherMobileNo'])){
                 sendSms($parentInfo[$i]['fatherMobileNo'],strip_tags($REQUEST_DATA['msgBody']));
             }   
            if(!empty($parentInfo[$i]['motherMobileNo'])){
                 sendSms($parentInfo[$i]['motherMobileNo'],strip_tags($REQUEST_DATA['msgBody']));
             }   
            if(!empty($parentInfo[$i]['guardianMobileNo'])){
                 sendSms($parentInfo[$i]['guardianMobileNo'],strip_tags($REQUEST_DATA['msgBody']));
             }     
          }
        }
     
        if(count($student)>0 && is_array($student)){
            for($i=0;$i<$sCount;$i++){
             if(!empty($studentInfo[$i]['studentMobileNo'])){
                 sendSms($studentInfo[$i]['studentMobileNo'],strip_tags($REQUEST_DATA['msgBody']));
              }   
            }
        }
    }
    
    //sendMail($to, $subject, $body, $from);
    
    //sent email
    $from ="dipanjan.bhattacharjee@graymattersolution.in"; //hardCoded  
    if($msgMedium[1]=="1"){
         UtilityManager::sendMail($email,$subject,$email,$from);
        }
     
        if(count($student)>0 && is_array($student)){
            for($i=0;$i<$sCount;$i++){
             if(!empty($studentInfo[$i]['studentEmail'])){
                 sendEmail($from,$studentInfo[$i]['studentEmail'],$REQUEST_DATA['msgBody']);
              }   
            }
        }
    }
       
    //sent to dashboard(because data must go to techer comments table so no checking is needed)
    //creating unique studentIds
    if(empty($REQUEST_DATA['student'])){
        $ar=$parent;
    }
    else if(empty($REQUEST_DATA['parent'])){
        $ar=$student;
    }
    else{
       $ar=array_unique(array_merge($parent,$student));
    }
    
    $j=count($ar);
    $studentIdUnique=array();
    for($i=0;$i<$j;$i++){
         array_push($studentIdUnique,$ar[$i]);
    }

    $siqCount=count($studentIdUnique);
    $query="";
    $teacherId=1; //hardCoded  
    for($i=0; $i < $siqCount ;$i++){
       if(!empty($studentIdUnique[$i])){
        
       if($query==""){
           $query=" ($teacherId,$studentIdUnique[$i],'$REQUEST_DATA[visibleFrom]','$REQUEST_DATA[visibleTo]','".$REQUEST_DATA[msgBody]."',".($msgMedium[0]==1?1:0) .",".($msgMedium[1]==1?1:0) .",".($msgMedium[2]==1?1:0) .",".(in_array($studentIdUnique[$i],$student)?1:0) .",".(in_array($studentIdUnique[$i],$parent)?1:0) .") ";
       }
      else{
          
           $query .=" , ($teacherId,$studentIdUnique[$i],'$REQUEST_DATA[visibleFrom]','$REQUEST_DATA[visibleTo]','".$REQUEST_DATA[msgBody]."',".($msgMedium[0]==1?1:0) .",".($msgMedium[1]==1?1:0) .",".($msgMedium[2]==1?1:0) .",".(in_array($studentIdUnique[$i],$student)?1:0) .",".(in_array($studentIdUnique[$i],$parent)?1:0) .") ";
      }
     }
   }
   $query ="INSERT INTO teacher_comment (teacherId,studentId,visibleFromDate,visibleToDate,comments,sms,email,dashboard,toStudent,toParent)  VALUES ".$query;  

   //send message to database
   $returnStatus = $sendMessageManager->sendMessage($query);
   if($returnStatus === false) {
        $errorMessage = FAILURE;
    }
   else{
       echo SUCCESS;           
    }
        
 
// $History: ajaxSendMessageCounseling.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/31/10    Time: 5:08p
//Updated in $/LeapCC/Library/StudentEnquiry
//condition format udpated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/24/10    Time: 4:05p
//Created in $/LeapCC/Library/StudentEnquiry
//initial checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SendMessage
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/09/08    Time: 10:29a
//Updated in $/Leap/Source/Library/SendMessage
//Added HTML Mime type for sending html formatted mails
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:44p
//Updated in $/Leap/Source/Library/SendMessage
//Added html formatted mail but not working
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Library/SendMessage
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:49p
//Updated in $/Leap/Source/Library/SendMessage
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/08/08    Time: 11:10a
//Created in $/Leap/Source/Library/SendMessage
//Initial Checkin

?>