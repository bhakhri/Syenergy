<?php
//-------------------------------------------------------
// Purpose: To get values of report complaints from the database
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ReportComplaintsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['trackingNumber'] ) != '') {
    require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
    $foundArray = ReportComplaintsManager::getInstance()->getReportTrackingComplaintDetail(' AND c.complaintId="'.$REQUEST_DATA['trackingNumber'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxReportComplaintsTrackingValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//
?>