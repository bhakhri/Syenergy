<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['leaveTypeId']) || trim($REQUEST_DATA['leaveTypeId']) == '') {
	 echo $REQUEST_DATA['leaveTypeId'];
        $errorMessage = 'Invalid Leave Type';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LeaveManager.inc.php");
        $leaveManager =  leaveManager::getInstance();
        //check for its usage in leave set mapping
        $usageArray=$leaveManager->checkLeaveTypeUsage($REQUEST_DATA['leaveTypeId']);
        if($usageArray[0]['cnt']!=0){
           echo DEPENDENCY_CONSTRAINT;
           die;
        }
         if($leaveManager->deleteLeave($REQUEST_DATA['leaveTypeId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
   }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:04
//Created in $/LeapCC/Library/Leave
//Created 'Leave' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Library/Leave
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Library/Leave
//Created module 'Leave'
?>

