<?php
//---------------------------------------------------------------------------------------------
// THIS FILE IS USED TO upload files when an user send message to employees
// Author : Dipanjan Bhattacharjee
// Created on : (11.05.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(MODEL_PATH . "/SendMessageManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');

UtilityManager::ifNotLoggedIn();
$fileUploadFlag=0;
$msgMedium=explode(',',$sessionHandler->getSessionVariable('MsgMedium'));
$errMsg=MSG_SENT_OK;

$sendMessageManager = SendMessageManager::getInstance();


  if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('IdToFileUpload'))) {
    $fileObj = FileUploadManager::getInstance('msgLogo');
    $filename =str_replace(',','_',$sessionHandler->getSessionVariable('IdToFileUpload')).'.'.$fileObj->name;
    if($fileObj->upload(IMG_PATH.'/AdminMessage/',$filename)) {
        $fileUploadFlag=1;
        //update file name in msg table table
        $sendMessageManager->updateAdminMessageFile($sessionHandler->getSessionVariable('IdToFileUpload'),$filename);
        // set null afer the file is uploaded
        $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
        logError($fileObj->message);
    }
    else {
        $fileUploadFlag=0;
        logError($fileObj->message);
        //we could use $fileObj->name ,but this will be blank when we try to upload files whose size is
        //more than what "Apache" permits and so our logic will fail.
        if(trim($sessionHandler->getSessionVariable('HiddenFile'))!=''){        
            $errMsg='';
            if($msgMedium[0]==1){
              $errMsg='SMS has been sent';
            }
            if($msgMedium[1]==1 and $msgMedium[2]==1){
             if($errMsg!=''){ 
               $errMsg .=',';
             }
             //$errMsg .='Email and Dashboard message could not be sent';
             $errMsg .='Email could not be sent';
            }
            if($msgMedium[1]==1 and $msgMedium[2]!=1){
             if($errMsg!=''){ 
               $errMsg .=',';
             }
             $errMsg .='Email could not be sent';
            }
            if($msgMedium[1]!=1 and $msgMedium[2]==1){
             if($errMsg!=''){ 
               $errMsg .=',';
             }
             $errMsg .='No file attached with dashboard message';
            }
            $errMsg .='\nReason : Invalid file extension or maximum upload size exceedes ';
            //make email and dashboard=0 on failed file upload
            //$sendMessageManager->updateAdminMessageOnFailedUpload($sessionHandler->getSessionVariable('IdToFileUpload'));
        }
    }
  }
  else {
      $fileUploadFlag=0;  
      logError('Upload Error: Session ID missing.'); 
  }
echo '<script language="javascript">parent.fileUploadError("'.$errMsg.'!~!~!'.$sessionHandler->getSessionVariable('smsNotSent').'!~!~!'.$sessionHandler->getSessionVariable('emailNotSent').'");</script>';
if($errMsg!=MSG_SENT_OK){
    //make these session variables null
    $sessionHandler->setSessionVariable('EmailIds',NULL);
    $sessionHandler->setSessionVariable('EmailSubject',NULL);  
    $sessionHandler->setSessionVariable('EmailBody',NULL);  
    $sessionHandler->setSessionVariable('SenderId',NULL);
    $sessionHandler->setSessionVariable('MsgMedium',NULL);
    $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
    $sessionHandler->setSessionVariable('HiddenFile',NULL);
    die;
}

//****************Now Email Sending******************
if(UtilityManager::notEmpty($sessionHandler->getSessionVariable('EmailIds'))){
        $to = $sessionHandler->getSessionVariable('EmailIds');
        $subject = $sessionHandler->getSessionVariable('EmailSubject');
        $random_hash = md5(date('r', time()));
if($fileUploadFlag==0){        
        $headers = "From: ".ADMIN_MSG_EMAIL."\r\nReply-To: ".ADMIN_MSG_EMAIL;
        $headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
        ob_start(); //Turn on output buffering
?>
--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<?php echo $sessionHandler->getSessionVariable('EmailBody');?>

--PHP-alt-<?php echo $random_hash; ?>--
<?php
}
else{
    $headers = "From: ".ADMIN_MSG_EMAIL."\r\nReply-To: ".ADMIN_MSG_EMAIL;
    $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
    $attachment = chunk_split(base64_encode(file_get_contents(IMG_PATH.'/AdminMessage/'.$filename)));
    ob_start(); //Turn on output buffering
?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<?php echo $sessionHandler->getSessionVariable('EmailBody');?>

--PHP-alt-<?php echo $random_hash; ?>--

--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: <?php echo $fileObj->type; ?>; name="<?php echo $filename;?>" 
Content-Transfer-Encoding: base64 
Content-Disposition: attachment 

<?php echo $attachment; ?>
--PHP-mixed-<?php echo $random_hash; ?>--
<?php    
}
$message = ob_get_clean();
//$mail_sent = mail( $to, $subject, $message, $headers );
    UtilityManager::sendMail($to, $subject, $message,$headers);
    
    //make these session variables null
    $sessionHandler->setSessionVariable('EmailIds',NULL);
    $sessionHandler->setSessionVariable('EmailSubject',NULL);  
    $sessionHandler->setSessionVariable('EmailBody',NULL);  
    $sessionHandler->setSessionVariable('SenderId',NULL);
    $sessionHandler->setSessionVariable('MsgMedium',NULL);
    $sessionHandler->setSessionVariable('IdToFileUpload',NULL);
    $sessionHandler->setSessionVariable('HiddenFile',NULL);    
}



//****************Email Sending Ends******************* 
// $History: fileUpload.php $
?>