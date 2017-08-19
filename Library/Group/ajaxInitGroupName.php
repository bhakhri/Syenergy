<?php
//-------------------------------------------------------
// Purpose: To get values of parent group name from the database
//
// Author : Jaineesh
// Created on : (14.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GroupMaster');
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/GroupManager.inc.php");
	$foundArray = null;
	$optional = $REQUEST_DATA['optional'];
	$optionalId = $REQUEST_DATA['optionalId'];

	$degree = $REQUEST_DATA['degree'];
	if (!empty($degree)) {
		if ($optionalId == 1 or $optional == 1) {
			$optional = 1;
		}
		$foundArray = GroupManager::getInstance()->getGroupName($optional, $degree);
		echo json_encode($foundArray);
		die;
	}



	if ($optionalId != '') {
		$optional = $optionalId;
		$foundArray = GroupManager::getInstance()->getGroupName($optional);
	}
	if ($optional != '') {
		$foundArray = GroupManager::getInstance()->getGroupName($optional);
	}
	if ($foundArray == null) {
		$foundArray = GroupManager::getInstance()->getGroupName($optional);
	}

	//$a = print_r($foundArray, true);
	//logError($a);
	echo json_encode($foundArray);

// $History: ajaxInitGroupName.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:19p
//Updated in $/LeapCC/Library/Group
//add field optional subjet for optional group
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/11/09    Time: 3:52p
//Updated in $/LeapCC/Library/Group
//added optional field functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:11p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/29/08    Time: 6:44p
//Updated in $/Leap/Source/Library/Group
//modified in group name
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/29/08    Time: 6:25p
//Updated in $/Leap/Source/Library/Group
//modified in parent group name
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/29/08    Time: 5:04p
//Updated in $/Leap/Source/Library/Group
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:43p
//Created in $/Leap/Source/Library/Group
//get the group name into dropdownlist of parent group
?>