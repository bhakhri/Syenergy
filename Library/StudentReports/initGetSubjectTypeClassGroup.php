<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$classId = $REQUEST_DATA['class1'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	//fetching subject data only if any one class is selected

	$groupsArray = $studentReportsManager->getSubjectTypeClass($classId,$subjectTypeId);
	$periodCount = count($groupsArray);
	if(is_array($groupsArray) && $groupsArray>0) {
        $json_subject = '';
        for($s = 0; $s<$periodCount; $s++) {
            $json_subject .= json_encode($groupsArray[$s]). ( $s==($periodCount-1) ? '' : ',' );                }
    } 
	$groupAllArray = $studentReportsManager->getSubjectTypeGroup($classId,$subjectTypeId);
	$resultsCount = count($groupAllArray);
	if(is_array($groupAllArray) && $resultsCount>0) {
        $json_group  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $json_group .= json_encode($groupAllArray[$s]). ( $s==($resultsCount-1) ? '' : ',' );                }
    }
	
	 
	//echo json_encode($groupsArray);

	 echo '{"subjectinfo" : ['.$json_subject.'],"groupinfo" : ['.$json_group.']}'; 

// $History: initGetSubjectTypeClassGroup.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/08/09    Time: 5:51p
//Created in $/LeapCC/Library/StudentReports
//Intail checkin: Added test type distribution 
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:12p
//Created in $/LeapCC/Library/StudentReports
//intial checkin
?>