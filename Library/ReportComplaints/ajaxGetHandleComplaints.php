<?php
//-------------------------------------------------------
// Purpose: To get sections
// Author : Pushpender Kumar Chauhan
// Created on : (20.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HandleComplaints');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['complaintId'] ) != '') {

    require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
	
	$complaintId = $REQUEST_DATA['complaintId'];
	$foundArray = ReportComplaintsManager::getInstance()->getReportComplaint($complaintId);
	$cnt = count($foundArray);

	if(is_array($foundArray) && count($foundArray)>0) {
		echo json_encode($foundArray[0]);
	}
		else {
			echo 0;
		}
	}
// $History: ajaxGetHandleComplaints.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Library/ReportComplaints
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Library/ReportComplaints
//make the changes as per discussion with pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/20/09    Time: 1:59p
//Updated in $/SnS/Library/TimeTable
//make substitution report & print
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/17/09    Time: 4:45p
//Created in $/SnS/Library/TimeTable
//
?>