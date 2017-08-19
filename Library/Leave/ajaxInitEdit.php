<?php

//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
	
	$leaveTypeId = trim($REQUEST_DATA['leaveTypeId']);
	$leaveTypeId = add_slashes($REQUEST_DATA['leaveTypeId']);

	$leaveTypeName = trim($REQUEST_DATA['leaveTypeName']);
	$leaveTypeName = add_slashes(trim($REQUEST_DATA['leaveTypeName']));


    if($leaveTypeId==''){
        echo "Invalid Leave.";
        die;
    }
    if($leaveTypeName==''){
        echo "Invalid Leave.";
        die;
    }


	require_once(MODEL_PATH . "/LeaveManager.inc.php");
    
    //check for its usage in leave set mapping
    $usageArray=LeaveManager::getInstance()->checkLeaveTypeUsage($leaveTypeId);
    if($usageArray[0]['cnt']!=0){
        echo DEPENDENCY_CONSTRAINT_EDIT;
        die;
    }
    
    
    
    
	$foundArray = LeaveManager::getInstance()->getLeave(" WHERE leaveTypeId != '$leaveTypeId' and TRIM(leaveTypeName) = '$leaveTypeName'");
	if(trim($foundArray[0]['leaveTypeName'])=='') {  //DUPLICATE CHECK
		$returnStatus = LeaveManager::getInstance()->editLeave($leaveTypeId);
		if($returnStatus == false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;           
		}
	}
	else {
	   echo LEAVE_TYPE_EXIST;
	}

// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/09   Time: 10:15
//Updated in $/LeapCC/Library/Leave
//Done bug fixing.
//Bug ids---
//0002339,0002340
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
