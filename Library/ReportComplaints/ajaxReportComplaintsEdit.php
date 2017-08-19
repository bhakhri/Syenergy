<?php
//-------------------------------------------------------
// Purpose: To update hostel room table data
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
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
     if ($errorMessage == '' && (!isset($REQUEST_DATA['subject']) || trim($REQUEST_DATA['subject']) == '')) {
        $errorMessage .= ENTER_SUBJECT. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['category']) || trim($REQUEST_DATA['category']) == '')) {
        $errorMessage .= ENTER_CATEGORY. "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['hostel']) || trim($REQUEST_DATA['hostel']) == '')) {
        $errorMessage .= CHOOSE_HOSTEL. "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['room']) || trim($REQUEST_DATA['room']) == '')) {
        $errorMessage .= CHOOSE_ROOM. "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['reportedBy']) || trim($REQUEST_DATA['reportedBy']) == '')) {
        $errorMessage .= CHOOSE_STUDENT. "\n";
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
		$foundArray = ReportComplaintsManager::getInstance()->getReportComplaintDetail('AND c.subject ="'.$REQUEST_DATA['subject'].'" AND c.complaintId !='.$REQUEST_DATA['complaintId']);
		
			if(trim($foundArray[0]['subject'])=='') { //DUPLICATE CHECK
				$returnStatus = ReportComplaintsManager::getInstance()->editReportComplaints($REQUEST_DATA['complaintId']);
					if($returnStatus === false) {
						echo FAILURE;
					}
					else {
						echo SUCCESS;           
					}
			}
			else {
				RECORD_ALREADY_EXIST;
			}
    }
    else {
       echo $errorMessage;
    }

// $History: ajaxReportComplaintsEdit.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Library/ReportComplaints
//fixed bugs during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//
?>