<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to parents
//
//
// Author : Jaineesh
// Created on : (20.01.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToParents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(BL_PATH.'/HtmlFunctions.inc.php');
require_once(MODEL_PATH . "/SendMessageManager.inc.php");
$sendMessageManager = SendMessageManager::getInstance();
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$systemDatabaseManage=SystemDatabaseManager::getInstance();
$userId=$sessionHandler->getSessionVariable('UserId');  
$instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
$sessionId=$sessionHandler->getSessionVariable('SessionId'); 

$emailIdString='';
$lastInsertIdsString='';


//$teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (20.01.2009)
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
// Author :Jaineesh 
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------  
function sendEmail($from,$to,$msgSubject,$msgBody){
     //********some problem remains when we set $from varable to ours email(so $from is not used )*********
    // $headers = 'From: webmaster@example.com' . "\r\n" ;    
     $headers = "MIME-Version: 1.0\r\n" ;
     $headers .= "Content-Type: text/html\r\n";
     $headers .= 'From: '.$from.' '. "\r\n" ;
     //$headers .= 'Content-type: text/html;'; 
     //$headers = 'From: '.$from.' '. "\r\n" ;    
     //$headers .= 'Content-type: text/html;'; 
     return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
}
//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (20.01.2009)
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


$insQuery=""; 
$curDate=date('Y-m-d h:i:s');
$sms=0;$email=0;$dashboard=0;

$emailIds='';

$smsNotSentArrayF=array();
$emailNotSentArrayF=array();

$smsNotSentArrayM=array();
$emailNotSentArrayM=array();

$smsNotSentArrayG=array();
$emailNotSentArrayG=array();

if (trim($errorMessage) == '') {
       //$commentId=$teacherManager->addTeacherComment(); //add comment in "teacher_comment" table    
       
       //Stores commentId in Session for using in file uploading
      // $sessionHandler->setSessionVariable('IdToFileUpload',$commentId);
       
       //gets father mobile and email
       if(trim($REQUEST_DATA['father'])!=""){
        $fatherEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['father'].")"); 
        $cntF=count($fatherEmailMobilesArr);

       } 
       //gets mother mobile and email  
       if(trim($REQUEST_DATA['mother'])!=""){
        $motherEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['mother'].")"); 
        $cntM=count($motherEmailMobilesArr);
       } 
      //gets guardian mobile and email   
       if(trim($REQUEST_DATA['guardian'])!=""){
        $guardianEmailMobilesArr=$sendMessageManager->getParentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['guardian'].")"); 
        $cntG=count($guardianEmailMobilesArr);
       } 
       
       //calculate msg medium
       $msgMedium=split("," , $REQUEST_DATA['msgMedium']); 
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
                if(trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!="" and trim($fatherEmailMobilesArr[$i]['fatherMobileNo'])!='NA' and strlen(trim($fatherEmailMobilesArr[$i]['fatherMobileNo']))>=10){    
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
               if(trim($fatherEmailMobilesArr[$i]['fatherEmail'])!="" and HtmlFunctions::getInstance()->isEmail(trim($fatherEmailMobilesArr[$i]['fatherEmail']))!= 0){   
                 //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$fatherEmailMobilesArr[$i]['fatherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$fatherEmailMobilesArr[$i]['fatherTitle'].". ".ucwords($fatherEmailMobilesArr[$i]['fatherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                 $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                 if($emailIdString!=''){
                     $emailIdString .=',';
                 }
                 $emailIdString .=trim($fatherEmailMobilesArr[$i]['fatherEmail']);
               }
               else{
                 $emailNotSentArrayF[]=$fatherEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }
               /* if(trim($fatherEmailMobilesArr[$i]['fatherEmail'])!==""){
                     sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$fatherEmailMobilesArr[$i]['fatherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$fatherEmailMobilesArr[$i]['fatherTitle'].". ".ucwords($fatherEmailMobilesArr[$i]['fatherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                     if($emailIds==''){
                        $emailIds=$fatherEmailMobilesArr[$i]['fatherEmail'];
                     }
                     else{
                        $emailIds=$emailIds.', '. $fatherEmailMobilesArr[$i]['fatherEmail'];
                     }
                     $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                  } 
               */
             }
		   } //end of forloop
             
			$receiverIds="~".str_replace(",","~",$REQUEST_DATA['father'])."~";
           if($msgMedium[1]==1){  //if email
			  $insQuery="('".$receiverIds."','Father','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Email','".$curDate."','".$curDate."', $userId,$instituteId,$sessionId)";
              $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();  
		   } 
     
		   if($msgMedium[2]==1){  //if dashboard
			  $insQuery="('".$receiverIds."','Father','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."', $userId,$instituteId,$sessionId)";
			  $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();  
			} 
           //bulid the insert query for father
            
                            
         if($msgMedium[0]==1){
           //for multiple smss(started from 1 to leave the common record)    
           for($s=0 ; $s < $smsNo ;$s++){   
              $insQuery="('".$receiverIds."','Father','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
           } 
         } 
       
    
    //run the query

    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
 }
 $smsNotSent=implode(",",$smsNotSentArrayF); 
 if(trim($REQUEST_DATA['father'])!=""){
	if($msgMedium[0]==1){
		$lastMsgId=$systemDatabaseManage->lastInsertId();
		$result= $sendMessageManager->insertIntoAdminMsgsFailed($lastMsgId,'Father',$curDate,$smsNotSent,$REQUEST_DATA['msgSubject'],$REQUEST_DATA['msgBody'],'SMS');
		if($result == false){
			$errorMessage = FAILURE;
		}
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
                if(trim($motherEmailMobilesArr[$i]['motherMobileNo'])!="" and trim($motherEmailMobilesArr[$i]['motherMobileNo'])!='NA' and strlen(trim($motherEmailMobilesArr[$i]['motherMobileNo']))>=10){ 
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
                 //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$motherEmailMobilesArr[$i]['motherEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$motherEmailMobilesArr[$i]['motherTitle'].". ".ucwords($motherEmailMobilesArr[$i]['motherName']).",\r\n".$REQUEST_DATA['msgBody']); 
                 $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                  if($emailIdString!=''){
                     $emailIdString .=',';
                 }
                 $emailIdString .=trim($motherEmailMobilesArr[$i]['motherEmail']);
               }
               else{
                 $emailNotSentArrayM[]=$motherEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }
             /* if($msgMedium[1]==1){ //if email
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
             }   */
			 }
			 }  //end of forloop
            
			$receiverIds="~".str_replace(",","~",$REQUEST_DATA['mother'])."~";
            if($msgMedium[1]==1){  //if email
				$insQuery="('".$receiverIds."','Mother','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Email','".$curDate."','".$curDate."', $userId,$instituteId,$sessionId)";
                $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
                if($lastInsertIdsString!=''){
                  $lastInsertIdsString .=','; 
                }
                $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
        	} 
     
			if($msgMedium[2]==1){  //if dashboard
			  $insQuery="('".$receiverIds."','Mother','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."', $userId,$instituteId,$sessionId)";
              $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
			} 
           //bulid the insert query for father
            
                            
         if($msgMedium[0]==1){
          //for multiple smss(started from 1 to leave the common record)    
          for($s=0 ; $s < $smsNo ;$s++){   
              $insQuery="('".$receiverIds."','Mother','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
          } 
         } 
       
    
    //run the query
   if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
  }
   
   $smsNotSentM=implode(",",$smsNotSentArrayM); 
   if(trim($REQUEST_DATA['mother'])!=""){
		if($msgMedium[0]==1){
			$lastMsgId=$systemDatabaseManage->lastInsertId();
			$result= $sendMessageManager->insertIntoAdminMsgsFailed($lastMsgId,'Mother',$curDate,$smsNotSentM,$REQUEST_DATA['msgSubject'],$REQUEST_DATA['msgBody'],'SMS');
			if($result == false){
				$errorMessage = FAILURE;
			}
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
                if(trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!="" and trim($guardianEmailMobilesArr[$i]['guardianMobileNo'])!="NA" and strlen(trim($guardianEmailMobilesArr[$i]['guardianMobileNo']))>=10){
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
               if(trim($guardianEmailMobilesArr[$i]['guardianEmail'])!=="" and HtmlFunctions::getInstance()->isEmail(trim($guardianEmailMobilesArr[$i]['guardianEmail']))!= 0){   
                 //sendEmail($sessionHandler->getSessionVariable('EmployeeEmail'),$guardianEmailMobilesArr[$i]['guardianEmail'],$REQUEST_DATA['msgSubject'],"Dear ".$guardianEmailMobilesArr[$i]['guardianTitle'].". ".ucwords($guardianEmailMobilesArr[$i]['guardianName']).",\r\n".$REQUEST_DATA['msgBody']); 
                 $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
                 if($emailIdString!=''){
                     $emailIdString .=',';
                 }
                 $emailIdString .=trim($guardianEmailMobilesArr[$i]['guardianEmail']);
               }
               else{
                 $emailNotSentArrayG[]=$guardianEmailMobilesArr[$i]['studentId'];
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate; 
               } 
             }             
           /*  if($msgMedium[1]==1){ //if email
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
             } */   
			 }
			 }  //end of forloop
             $receiverIds="~".str_replace(",","~",$REQUEST_DATA['guardian'])."~";
            if($msgMedium[1]==1){  //if email
			   $insQuery="('".$receiverIds."','Guardian','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Email','".$curDate."','".$curDate."', $userId,$instituteId,$sessionId)";
               $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
               if($lastInsertIdsString!=''){
                 $lastInsertIdsString .=','; 
               }
               $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
			} 
     
			if($msgMedium[2]==1){  //if dashboard
			  $insQuery="('".$receiverIds."','Guardian','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($REQUEST_DATA['msgBody']))."','Dashboard','".$REQUEST_DATA['visibleFrom']."','".$REQUEST_DATA['visibleTo']."', $userId,$instituteId,$sessionId)";
			  $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
			} 
           //bulid the insert query for father
            
                            
         if($msgMedium[0]==1){
           //for multiple smss(started from 1 to leave the common record)    
           for($s=0 ; $s < $smsNo ;$s++){   
              $insQuery="('".$receiverIds."','Guardian','".$curDate."','".htmlentities(add_slashes($REQUEST_DATA['msgSubject']))."','".htmlentities(add_slashes($smsArr[$s]))."','SMS','".$curDate."','".$curDate."',$userId,$instituteId,$sessionId)";
              $sendMessageManager->adminMessageEmailSMSRecord($insQuery); //add the record in database 
              if($lastInsertIdsString!=''){
                $lastInsertIdsString .=','; 
              }
              $lastInsertIdsString .=$systemDatabaseManage->lastInsertId();   
           } 
         } 
     
    //run the query
    if($returnStatus === false) {
       $errorMessage = FAILURE;
    }
     
  }
   $smsNotSentG=implode(",",$smsNotSentArrayG); 
	if(trim($REQUEST_DATA['guardian'])!=""){
		if($msgMedium[0]==1){
			$lastMsgId=$systemDatabaseManage->lastInsertId();
			$result= $sendMessageManager->insertIntoAdminMsgsFailed($lastMsgId,'Guardian',$curDate,$smsNotSentG,$REQUEST_DATA['msgSubject'],$REQUEST_DATA['msgBody'],'SMS');
			if($result == false){
				$errorMessage = FAILURE;
			}
		}
	}
//END OF GUARDIAN SECTION

     $sessionHandler->setSessionVariable('IdToFileUpload',$lastInsertIdsString);
  
  
    //Stores message medium
    $sessionHandler->setSessionVariable('MsgMedium',$REQUEST_DATA['msgMedium']);
    
    //Stores file upload info
    $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
    
    //Stores emailIds in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailIds',$emailIdString);
    
    //Stores senderId in Session for using in mail sending
    $sessionHandler->setSessionVariable('SenderId',$sessionHandler->getSessionVariable('EmployeeEmail') .'< '.$sessionHandler->getSessionVariable('EmployeeName').' >');
    
    //Stores message subject in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailSubject',trim($REQUEST_DATA['msgSubject']));
    
    //Stores message in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailBody',trim($REQUEST_DATA['msgBody'])); 


/*  //Stores emailIds in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailIds',$emailIds);
    
    //Stores senderId in Session for using in mail sending
    $sessionHandler->setSessionVariable('SenderId',$sessionHandler->getSessionVariable('EmployeeEmail') .'< '.$sessionHandler->getSessionVariable('EmployeeName').' >');
    
    //Stores message subject in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailSubject',trim($REQUEST_DATA['msgSubject']));
    
    //Stores message in Session for using in mail sending
    $sessionHandler->setSessionVariable('EmailBody',trim($REQUEST_DATA['msgBody'])); 
*/ 
 $smsNotSentG=implode(",",$smsNotSentArrayG);
 $smsNotSentM=implode(",",$smsNotSentArrayM);   
 $smsNotSentF=implode(",",$smsNotSentArrayF); 
 $emailNotSentF=implode(",",$emailNotSentArrayF);
 $emailNotSentM=implode(",",$emailNotSentArrayM);   
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
  
  echo $errorMessage.'!~!~!'.$smsNotSentF.'!~!~!'.$emailNotSentF.'!~!~!'.$smsNotSentM.'!~!~!'.$emailNotSentM.'!~!~!'.$smsNotSentG.'!~!~!'.$emailNotSentG;
} 
else {
  echo $errorMessage;
}
// $History: scAjaxAdminSendParentMessage.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 3/03/10    Time: 2:40p
//Updated in $/LeapCC/Library/AdminMessage
//set sessionvariable base code updated 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/10/09    Time: 2:48p
//Updated in $/LeapCC/Library/AdminMessage
//parentType filed added record save
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:14
//Updated in $/LeapCC/Library/AdminMessage
//Done bug fixing.
//Bug ids---
//00001201,00001207,00001208,00001216
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/05/09    Time: 3:39p
//Updated in $/LeapCC/Library/AdminMessage
//mobile conditon update 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/05/09    Time: 1:25p
//Updated in $/LeapCC/Library/AdminMessage
//validation added (phone length & valid email checks)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/04/09    Time: 7:17p
//Updated in $/LeapCC/Library/AdminMessage
//create document list (No messages send Information)
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/26/09    Time: 5:44p
//Updated in $/LeapCC/Library/AdminMessage
//Updated with Management access so that it can be accessed from
//management login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:28
//Created in $/LeapCC/Library/AdminMessage
//Created module  "Send Message for Parents"
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/09    Time: 6:16p
//Created in $/Leap/Source/Library/ScAdminMessage
//new library to show parent list or insert into data
//

?>