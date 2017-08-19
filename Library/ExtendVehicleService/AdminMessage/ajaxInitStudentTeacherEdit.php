<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit a salary head
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1); 
define('MODULE','COMMON');
define('ACCESS','edit');
if($sessionHandler->getSessionVariable('RoleId')==2) {     
    UtilityManager::ifTeacherNotLoggedIn(true);
}
else {
    UtilityManager::ifNotLoggedIn(true);   
}
UtilityManager::headerNoCache();


require_once(MODEL_PATH . "/AdminMessageManager.inc.php");   
$adminMessageManager = AdminMessageManager::getInstance();

$errorMessage ='';

$messageSubject=$REQUEST_DATA['messageSubject'];
$messageText=$REQUEST_DATA['messageText'];

$messageId=$REQUEST_DATA['messageId'];
$receiverId=$REQUEST_DATA['receiverId'];
$senderId=$REQUEST_DATA['senderId'];
 
$errorMessage ='';

if (trim($errorMessage) == '') {
    $returnStatus = $adminMessageManager->addAdminMessageStudent();
    if($returnStatus === false) {
	    echo FAILURE;
    }
    else {
       $sessionHandler->setSessionVariable('OperationMode',2);
       //Stores file upload info
       $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
	   echo SUCCESS;           
    }
}
else {
	echo $errorMessage;
}
// $History: ajaxInitStudentTeacherEdit.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:15p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated with Session check
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Intial checkin
?>