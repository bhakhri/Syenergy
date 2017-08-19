<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE salary head
//
// Author : Rajeev Aggarwal
// Created on : (24.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1); 
define('MODULE','COMMON');
define('ACCESS','view');
if($sessionHandler->getSessionVariable('RoleId')==2) {     
    UtilityManager::ifTeacherNotLoggedIn(true);
}
else {
    UtilityManager::ifNotLoggedIn(true);   
}
UtilityManager::headerNoCache();
    
require_once(MODEL_PATH . "/AdminMessageManager.inc.php");
$adminMessageManager = AdminMessageManager::getInstance();       
    
if(trim($REQUEST_DATA['messageId'] ) != '') {

    $roleArr = explode('~',$REQUEST_DATA['messageId']);   
    $messageId = $roleArr[0];
    
    // AdminMessageManager::getInstance()->changeMessageStatus($roleArr[0]);   
	//StudentTeacherManager::getInstance()->changeMessageStatus($roleArr[0]);
	//if($roleArr[1]==4){		 
	//	$foundArray = StudentTeacherManager::getInstance()->getStudentMessageDetail(' AND stc.messageId="'.$roleArr[0].'"');
	//}
    $foundArray = $adminMessageManager->getParentSentItemList(' AND stc.messageId="'.$messageId.'"');
    if(is_array($foundArray) && count($foundArray)>0 ) { 
      $foundArray[0]['messageDate'] = UtilityManager::formatDate($foundArray[0]['messageDate'],true);
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0;
    }
}
// $History: ajaxGetStudentTeacherValues.php $
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:15p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated with Session check
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/18/09    Time: 4:36p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//code update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/04/09    Time: 6:32p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//inital checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/03/09    Time: 2:38p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated Validations
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Intial checkin
?>