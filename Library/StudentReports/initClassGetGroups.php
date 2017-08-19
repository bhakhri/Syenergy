<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	 
	$classId = $REQUEST_DATA['class1'];
	//fetching subject data only if any one class is selected

	if ($classId != 'all') {
		$subjectsArray = $commonQueryManager->getLastLevelGroups('groupName', " AND a.classId = $classId");
		echo json_encode($subjectsArray);
	}



//// $History: initClassGetGroups.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:55p
//Updated in $/Leap/Source/Library/StudentReports
//applied code to make it working in IE
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/14/08    Time: 4:05p
//Created in $/Leap/Source/Library/StudentReports
//file added for test wise marks report
//

?>