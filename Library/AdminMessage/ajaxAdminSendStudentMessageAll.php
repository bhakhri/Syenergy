<?php
//----------------------------------------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin through BACKGROUD PROCESS
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (28.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/SendMessageManager.inc.php");
$sendMessageManager = SendMessageManager::getInstance();
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToStudents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  
$errorMessage = SUCCESS;

$argStr='';
//Create Argument List
$argStr =  $sessionHandler->getSessionVariable('UserId')." ";
$argStr .= $sessionHandler->getSessionVariable('InstituteId')." ";
$argStr .= $sessionHandler->getSessionVariable('SessionId')." ";
$argStr .= $REQUEST_DATA['student']." ";
$argStr .=add_slashes($REQUEST_DATA['msgMedium'])." ";
$argStr .= "\"".add_slashes($REQUEST_DATA['msgBody'])."\"  ";
$argStr .= "\"".add_slashes($REQUEST_DATA['msgSubject'])."\" ";
$argStr .= "\"".add_slashes(ADMIN_MSG_EMAIL)."\" ";
$argStr .= "\"".DB_HOST."\" ";   
$argStr .= "\"".DB_USER."\" ";   
$argStr .= "\"".DB_PASS."\" ";   
$argStr .= "\"".DB_NAME."\" ";   
$argStr .="\"".SMS_MAX_LENGTH."\" ";   
$argStr .="\"".SMS_GATEWAY_USER_VARIABLE."\" ";   
$argStr .="\"".SMS_GATEWAY_PASS_VARIABLE."\" ";   
$argStr .="\"".SMS_GATEWAY_NUMBER_VARIABLE."\" ";   
$argStr .="\"".SMS_GATEWAY_MESSAGE_VARIABLE."\" ";   
$argStr .="\"".SMS_GATEWAY_SNDR_VARIABLE."\" ";   
$argStr .="\"".SMS_GATEWAY_SNDR_VALUE."\" ";   
$argStr .="\"".SMS_GATEWAY_USERNAME."\" ";   
$argStr .="\"".SMS_GATEWAY_PASSWORD."\" ";   
$argStr .="\"".SMS_GATEWAY_URL."\" ";   
$argStr .="\"".NUMBER_OF_LOOPS_BEFORE_A_SLEEP."\" ";   
$argStr .="\"".AMOUNT_OF_SLEEP."\" ";   
$argStr .="\"".$REQUEST_DATA['visibleFrom']."\" "; 
$argStr .="\"".$REQUEST_DATA['visibleTo']."\" "; 
$studentEmailMobilesArr=$sendMessageManager->getStudentEmailMobileNoList(" WHERE studentId IN(".$REQUEST_DATA['student'].")"); 
$cnt=count($studentEmailMobilesArr);

if($argStr!=''){
    $msgMedium =explode(",",$REQUEST_DATA['msgMedium']);

    $studentNos=count(explode(",",$REQUEST_DATA['student']));
    $mailStr='';
    if($msgMedium[0]==1 and $msgMedium[1]==1){
        $mailStr="Mail and SMS sent to $studentNos students";
    }
    else if($msgMedium[0]==1){
           $mailStr="SMS sent to $studentNos students";
    }
    else if($msgMedium[1]==1){
           $mailStr="Email sent to $studentNos students";
    }
    if(ADMIN_MSG_EMAIL!=''){
        //UtilityManager::sendMail(ADMIN_MSG_EMAIL,"Message Details",$mailStr,ADMIN_MSG_EMAIL);
    }
  $smsNotSentArray=array();
  $emailNotSentArray=array();  
  for($i=0;$i<$cnt;$i++){
       if($msgMedium[0]==1){ //if sms
         if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])=="" OR trim($studentEmailMobilesArr[$i]['studentMobileNo'])=='NA' OR strlen(trim($studentEmailMobilesArr[$i]['studentMobileNo']))<10){ 
             $smsNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
         }
       }
      
      if($msgMedium[1]==1){ //if email
        if(trim($studentEmailMobilesArr[$i]['studentEmail'])=="" or HtmlFunctions::getInstance()->isEmail(trim($studentEmailMobilesArr[$i]['studentEmail']))== 0 ){
            $emailNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
       }
      }
    }
	//$lastMsgId=SystemDatabaseManager::getInstance()->lastInsertId();
    $smsNotSent=implode(",",$smsNotSentArray);    
    $emailNotSent=implode(",",$emailNotSentArray);
	//$smsNotDelivered = implode("~",$smsNotSentArray);
    if($smsNotSent!='') { 
		
		$sessionHandler->setSessionVariable('smsStudentIds',$smsNotSent);
        $smsNotSent ='1'; 
	}
    else {
        $sessionHandler->setSessionVariable('smsStudentIds','-1');
        $smsNotSent ='';   
    }
        
    if($emailNotSent!='') {
        $sessionHandler->setSessionVariable('emailStudentIds',$emailNotSent);
        $emailNotSent ='1';
    }
    else {
         $sessionHandler->setSessionVariable('emailStudentIds','-1');  
         $emailNotSent =''; 
    } 
    
    echo $errorMessage.'!~!~!'.$smsNotSent.'!~!~!'.$emailNotSent;
    
    /*******FOR LINUX/UNIX********/
    $ret=system("php ".LIB_PATH."/Library/AdminMessage/bulkMessageProcess.php ".$argStr." > /dev/null &",$output);
	
    /*******FOR WINDOWS********/                    
    /*$ret=exec("C:/wamp/bin/php/php5.2.5/php.exe  ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ".$argStr." &");
    $ret=pclose(popen("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ". $argStr, "r"));
    $WshShell = new COM("WScript.Shell");
    $oExec = $WshShell->Run("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ". $argStr, 0, false);
	
	$ret=exec("C:/wamp/bin/php/php5.2.5/php.exe  ".LIB_PATH."/Library/AdminMessage/bulkMessageProcess.php ".$argStr." &");
    $ret=pclose(popen("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/AdminMessage/bulkMessageProcess.php ". $argStr, "r"));
    $WshShell = new COM("WScript.Shell");
    $oExec = $WshShell->Run("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/AdminMessage/bulkMessageProcess.php ". $argStr, 0, false);
	*/
    exit;
}
else{
    echo FAILURE;
}

// $History: ajaxAdminSendStudentMessageAll.php $ 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 3/09/10    Time: 2:26p
//Updated in $/LeapCC/Library/AdminMessage
//session variable set
//
//*****************  Version 7  *****************
//User: Parveen      Date: 3/09/10    Time: 2:15p
//Updated in $/LeapCC/Library/AdminMessage
//session variable variable set 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/03/10    Time: 2:40p
//Updated in $/LeapCC/Library/AdminMessage
//set sessionvariable base code updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/05/09    Time: 1:57p
//Updated in $/LeapCC/Library/AdminMessage
//studentId update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/05/09    Time: 1:25p
//Updated in $/LeapCC/Library/AdminMessage
//validation added (phone length & valid email checks)
//
//*****************  Version 3  *****************
//User: Administrator Date: 1/06/09    Time: 17:32
//Updated in $/LeapCC/Library/AdminMessage
//Added the functionality : sms & email not sent to how many students
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Library/AdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:17p
//Created in $/LeapCC/Library/AdminMessage
//Create Send Message to All
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/29/08   Time: 12:45p
//Updated in $/Leap/Source/Library/ScAdminMessage
//Added the functionality of sending mail to USER informing him/her
//how many email/+sms sent to students
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/29/08   Time: 11:47a
//Updated in $/Leap/Source/Library/ScAdminMessage
//trying for windows background process
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/29/08   Time: 11:18a
//Created in $/Leap/Source/Library/ScAdminMessage
//Added BackGround Process Capability
?>
