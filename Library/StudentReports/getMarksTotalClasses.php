<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label
//
//
// Author :Ajinder Singh
// Created on : 28-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TotalMarksReport');
	define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = $REQUEST_DATA['labelId'];
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
	$classArray = $studentReportsManager->getMarksTotalClasses($labelId);
	echo json_encode($classArray);

// $History: scGetMarksTotalClasses.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/27/09    Time: 12:12p
//Updated in $/Leap/Source/Library/ScStudentReports
//added Management Define.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:43p
//Created in $/Leap/Source/Library/ScStudentReports
//file added for total marks report
//


?>