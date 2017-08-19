<?php
//-------------------------------------------------------
// Purpose: To delete a Salary head
//
// Author : Rajeev Aggarwal
// Created on : (25.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1); 
define('MODULE','COMMON');
define('ACCESS','delete');
if($sessionHandler->getSessionVariable('RoleId')==2) {     
    UtilityManager::ifTeacherNotLoggedIn(true);
}
else {
    UtilityManager::ifNotLoggedIn(true);   
}
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['messageId']) || trim($REQUEST_DATA['messageId']) == '') {
        $errorMessage = 'Invalid message Id';
    }

	if($sessionHandler->getSessionVariable('SuperUserId')!=''){
	  echo ACCESS_DENIED;
	  die;
	}

    if (trim($errorMessage) == '') {

		require_once(MODEL_PATH . "/Student/StudentMessageManager.inc.php");
		$studentTeacherManager = StudentTeacherManager::getInstance();

		//$recordArray = $studentTeacherManager->checkInStudentMessage(" WHERE messageId =".$REQUEST_DATA['messageId']);
        //if($recordArray[0]['totalRecords']==0) {
            if($studentTeacherManager->deleteStudentMessage($REQUEST_DATA['messageId']) ) {
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
// $History: ajaxInitStudentTeacherDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/08/10    Time: 4:43p
//Updated in $/Leap/Source/Library/ScStudent
//put check admin cannot add, edit during student login
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:10p
//Updated in $/Leap/Source/Library/ScStudent
//Updated with session check
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:26p
//Created in $/Leap/Source/Library/ScStudent
//Intial checkin
?>