<?php
//-------------------------------------------------------
// Purpose: To get values of coursewise timetable from the database
//
// Author : Jaineesh
// Created on : 19.11.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LecturePercentManager.inc.php");    
require_once(MODEL_PATH.'/CommonQueryManager.inc.php');
define('MODULE','LecturePercent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	
global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');


if(trim($REQUEST_DATA['timeTableLabelId']) != '') {
	

    $degreeManager    = CommonQueryManager::getInstance();
    
	$timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];

	$degreeArray = $degreeManager->getTimeTableLabelDegreeData('degreeId',"AND ttl.timeTableLabelId = ".$timeTableLabelId);
    $resultsCount = count($degreeArray);
	
	if(is_array($degreeArray) && $resultsCount>0) {
        echo json_encode($degreeArray);
    }
	else {
		echo 0; //no record found
	}
}
// $History: ajaxAttendanceDegreeValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:24a
//Created in $/LeapCC/Library/LecturePercent
//new ajax file to get degree of time table
//
?>