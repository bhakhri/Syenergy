<?php
//-------------------------------------------------------
// THIS FILE IS USED TO delete A message
//
// Author : Rajeev Aggarwal
// Created on : (25.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifParentNotLoggedIn(true);       
UtilityManager::headerNoCache();

if($sessionHandler->getSessionVariable('SuperUserId')!=''){
  echo ACCESS_DENIED;
  die;
}
//UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['messageId']) || trim($REQUEST_DATA['messageId']) == '') {
        $errorMessage = 'Invalid message Id';
    }
    
    if (trim($errorMessage) == '') {

        require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");
        $parentTeacherManager = ParentTeacherManager::getInstance();
		//$recordArray = $parentTeacherManager->checkInParentMessage(" WHERE messageId =".$REQUEST_DATA['messageId']);
        //if($recordArray[0]['totalRecords']==0) {
            if($parentTeacherManager->deleteParentMessage($REQUEST_DATA['messageId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
 
        //}
        //else {
        //    echo DEPENDENCY_CONSTRAINT;
       // }

   }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitParentTeacherDelete.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/08/10    Time: 6:33p
//Updated in $/Leap/Source/Library/ScParent
//access right added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:27p
//Created in $/Leap/Source/Library/ScParent
//initial checkin 
//

?>