<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ReportComplaintsMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$roomId = $REQUEST_DATA['room'];
    require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
    $reportComplaintsManager = ReportComplaintsManager::getInstance();
	$roomStudentArray = $reportComplaintsManager->getStudent($roomId);

	echo json_encode($roomStudentArray);


// $History: getStudent.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//

?>