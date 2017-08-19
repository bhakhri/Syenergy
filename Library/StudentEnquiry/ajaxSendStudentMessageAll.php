<?php
//----------------------------------------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin through BACKGROUD PROCESS
//
//
// Author : Parveen Sharma
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
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(BL_PATH.'/HtmlFunctions.inc.php');

$errorMessage = SUCCESS;


global $sessionHandler;

$cInstructions = $sessionHandler->getSessionVariable('COUNSELING_INSTRUCTION');
$cAddress      = $sessionHandler->getSessionVariable('COUNSELING_ADDRESS');

$counselingId  = trim($REQUEST_DATA['counselor']);
$startDate  = trim($REQUEST_DATA['startDate']);
$endDate    = trim($REQUEST_DATA['endDate']);
$studentId = trim($REQUEST_DATA['studentId']);
$msgMedium = "0,1";


if($studentId=='') {
  $studentId = 0;
}

$argStr='';
//Create Argument List
$argStr =  "\"".$sessionHandler->getSessionVariable('UserId')."\"  ";
$argStr .= "\"".$sessionHandler->getSessionVariable('InstituteId')."\"  ";
$argStr .= "\"".$sessionHandler->getSessionVariable('SessionId')."\"  ";
$argStr .= "\"".$studentId."\"  ";
$argStr .= "\"".$msgMedium."\"  ";
$argStr .= "\"".$msgBody."\"  ";
$argStr .= "\"".$msgSubject."\"  ";
$argStr .= "\"".add_slashes(ADMIN_MSG_EMAIL)."\"  ";
$argStr .= "\"".DB_HOST."\"  ";
$argStr .= "\"".DB_USER."\"  ";
$argStr .= "\"".DB_PASS."\"  ";
$argStr .= "\"".DB_NAME."\"  ";
$argStr .= "\"".SMS_MAX_LENGTH."\"  ";
$argStr .= "\"".SMS_GATEWAY_USER_VARIABLE."\"  ";
$argStr .= "\"".SMS_GATEWAY_PASS_VARIABLE."\"  ";
$argStr .= "\"".SMS_GATEWAY_NUMBER_VARIABLE."\"  ";
$argStr .= "\"".SMS_GATEWAY_MESSAGE_VARIABLE."\"  ";
$argStr .= "\"".SMS_GATEWAY_SNDR_VARIABLE."\"  ";
$argStr .= "\"".SMS_GATEWAY_SNDR_VALUE."\"  ";
$argStr .= "\"".SMS_GATEWAY_USERNAME."\"  ";
$argStr .= "\"".SMS_GATEWAY_PASSWORD."\"  ";
$argStr .= "\"".SMS_GATEWAY_URL."\"  ";
$argStr .= "\"".NUMBER_OF_LOOPS_BEFORE_A_SLEEP."\"  ";
$argStr .= "\"".AMOUNT_OF_SLEEP."\"  ";
$argStr .= "\"".$startDate."\"  ";
$argStr .= "\"".$endDate."\"  ";
$argStr .= "\"".$sessionHandler->getSessionVariable('UserId')."\"  ";
$argStr .= "\"".IMG_HTTP_PATH."\"  ";
$argStr .= "\"".$cInstructions."\"  ";
$argStr .= "\"".$cAddress."\"  ";

$studentEmailMobilesArr=$sendMessageManager->getStudentEnquiryData(" WHERE studentId IN(".$studentId.")");
$cnt=count($studentEmailMobilesArr);

if($argStr!=''){
    $msgMedium[0] = 0;
    $msgMedium[1] = 1;

    $studentNos=count(explode(",",$studentId));
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
       if(HtmlFunctions::getInstance()->isEmail(trim($studentEmailMobilesArr[$i]['studentEmail']))== 0 ){
         $emailNotSentArray[]=$studentEmailMobilesArr[$i]['studentId'];
       }
    }


    $smsNotSent=implode(",",$smsNotSentArray);
    $emailNotSent=implode(",",$emailNotSentArray);

    if($smsNotSent!='') {
        $sessionHandler->setSessionVariable('smsStudentIds',$smsNotSent);
        $smsNotSent ='1';
    }
    else {
        $smsNotSent ="";
    }


    if($emailNotSent!='') {
        $sessionHandler->setSessionVariable('emailStudentIds',$emailNotSent);
        $emailNotSent ='1';
    }
    else {
       $emailNotSent ="";
    }

    echo $errorMessage.'!~!~!'.$smsNotSent.'!~!~!'.$emailNotSent;

    //$ret=system("php ".LIB_PATH."/Library/StudentEnquiry/bulkMessageProcess.php ".$argStr." > /dev/null &",$output);
    $ret=system("php ".LIB_PATH."/Library/StudentEnquiry/bulkMessageProcess.php ".$argStr,$output);
   //echo $output;

   //*******FOR WINDOWS********/
   //$ret=exec("C:/wamp/bin/php/php5.2.5/php.exe  ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ".$argStr." &");
   //$ret=pclose(popen("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ". $argStr, "r"));
   //$WshShell = new COM("WScript.Shell");
   //$oExec = $WshShell->Run("C:/wamp/bin/php/php5.2.5/php.exe ".LIB_PATH."/Library/ScAdminMessage/bulkMessageProcess.php ". $argStr, 0, false);
   exit;

}
else{
   echo FAILURE;
}

// $History: ajaxSendStudentMessageAll.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/16/10    Time: 2:51p
//Created in $/LeapCC/Library/StudentEnquiry
//initial checkin
//

?>
