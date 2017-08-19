<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HandleComplaints');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$hostelId = $REQUEST_DATA['hostel'];
    require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
    $reportComplaintsManagerr = ReportComplaintsManager::getInstance();
	$complaintArray = $reportComplaintsManagerr->getRoomData($hostelId);

	echo json_encode($complaintArray);


// $History: getRoom.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Library/ReportComplaints
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//

?>