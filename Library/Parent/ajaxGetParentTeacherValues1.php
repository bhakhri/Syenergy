<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE get Parent message   
//
// Author : Parveen Sharma
// Created on : (04.02.2009 )
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();

    
if(trim($REQUEST_DATA['messageId'] ) != '') {
	require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");       
    $roleArr = explode('~',$REQUEST_DATA['messageId']);
    $messageId = $roleArr[0];
    ParentTeacherManager::getInstance()->changeMessageStatus($roleArr[0]);   
    if($sessionHandler->getSessionVariable('SuperUserId')==''){  
      ParentTeacherManager::getInstance()->changeMessageStatus($roleArr[0]);
    }
    $foundArray = ParentTeacherManager::getInstance()->getParentTeacherMessageList(' AND messageId="'.$messageId.'"');
	if(is_array($foundArray) && count($foundArray)>0 ) { 
		$foundArray[0]['messageDate'] = UtilityManager::formatDate($foundArray[0]['messageDate'],true);
		echo json_encode($foundArray[0]);
	}
	else {
		echo 0;
	}
}
