<?php
//---------------------------------------------------------------------------------------------
// THIS FILE IS USED TO upload files when a teacher send message to students or parents
//
// Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//----------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");

UtilityManager::ifTeacherNotLoggedIn();
$fileUploadFlag=0;

  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
  
    $fileObj = FileUploadManager::getInstance('msgLogo');
    $filename = $sessionHandler->getSessionVariable('IdToFileUpload').'.'.$fileObj->name;
    if($fileObj->upload(IMG_PATH.'/Teacher/',$filename)) {
        $fileUploadFlag=1;
        //update file name in msg table table
        require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
        ScTeacherManager::getInstance()->updateTeacherCommentFile($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the file is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        $fileUploadFlag=0;  
        logError($fileObj->message); 
    }
  }
  else {
      $fileUploadFlag=0;  
      logError('Upload Error: Session ID missing.'); 
  }

//****************Now Email Sending******************
if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('EmailIds'))){
       
       //create normal headers
       $num  = md5(time());  
       $headers  = "From: ".$sessionHandler->getSessionVariable('SenderId')."\r\r\n";
       // Generate a boundary string
       $semi_rand = md5(time());
       $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
 
       // Add the headers for a file attachment
       $headers .= "\r\nMIME-Version: 1.0\r\n" .
             "Content-Type: multipart/mixed;\r\n" .
             " boundary=\"{$mime_boundary}\"";
       
       // Add a multipart boundary above the plain message
       $message = "This is a multi-part message in MIME format.\r\n\r\n" .
            "--{$mime_boundary}\r\n" .
            "Content-Type: text/html; charset=\"iso-8859-1\"\r\n" .
            "Content-Transfer-Encoding: 7bit\r\n\r\n" .
             trim($sessionHandler->getSessionVariable('EmailBody')) . "\r\n\r\n";      
    
    //create file attachment headers   
    if($fileUploadFlag==1 ){ 
        $fp   = fopen(IMG_PATH.'/Teacher/'.$filename, "rb");
        $file = fread($fp, filesize(IMG_PATH.'/Teacher/'.$filename));
        $fileData = chunk_split(base64_encode($file));
        
        // Add file attachment to the message 
        $message .= "--{$mime_boundary}\r\n" .
             "Content-Type: {$fileObj->type};\r\n" .
             " name=\"{$filename}\"\r\n" .
             "Content-Disposition: attachment;\r\n" .
             " filename=\"{$filename}\"\r\n" .
             "Content-Transfer-Encoding: base64\r\n\r\n" .
             $fileData . "\r\n\r\n" .
             "--{$mime_boundary}--\r\n"; 
    }
    
    echo $sessionHandler->getSessionVariable('EmailIds');
    //send the mail
    UtilityManager::sendMail($sessionHandler->getSessionVariable('EmailIds'), $sessionHandler->getSessionVariable('EmailSubject'), $message,$headers);
    
    //make these session variables null
    $sessionHandler->setSessionVariable('EmailIds',NULL);  
    $sessionHandler->setSessionVariable('EmailSubject',NULL);  
    $sessionHandler->setSessionVariable('EmailBody',NULL);  
    $sessionHandler->setSessionVariable('SenderId',NULL);  
}



//****************Email Sending Ends******************* 

// $History: fileUpload.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/10/08   Time: 3:55p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Transfer email sending code from ajax files to fileUpload file for
//"Ajax Mail Attachment" functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/04/08   Time: 11:31a
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created for providing functionality for sending attachment with
//messages from teacher
//to students and parents
//
?>