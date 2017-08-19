<?php
//-------------------------------------------------------
// Purpose: To delete hostel room detail through Id
// Name : id -> hostelRoomTypeId
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ReportComplaintsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['complaintId']) || trim($REQUEST_DATA['complaintId']) == '') {
        $errorMessage = INVALID_COMPLAINT_DETAIL;
    }
    if (trim($errorMessage) == '') {
		
        require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
        $reportComplaintsManager =  ReportComplaintsManager::getInstance();
            if($reportComplaintsManager->deleteReportComplaint($REQUEST_DATA['complaintId']) ) {
                echo DELETE;
            }
			else {
				echo DEPENDENCY_CONSTRAINT;
			}
	}
    else {
        echo $errorMessage;
    }
 
// $History: ajaxReportComplaintsDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//
?>