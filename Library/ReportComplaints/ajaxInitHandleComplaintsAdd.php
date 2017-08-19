<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD SUBSTITUTE TEACHER
//
// Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HandleComplaints');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
		
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
		$handleComplaintsManager = ReportComplaintsManager::getInstance();
		$complaintId = $REQUEST_DATA['complaintId'];
		$complaintStatus = $REQUEST_DATA['complaintStatus'];
		$complaintOn = $REQUEST_DATA['complaintOn'];
		$completionDate = $REQUEST_DATA['completionDate'];
		$remarks = $REQUEST_DATA['remarks'];
		if ($completionDate != "") {
			if ($completionDate < $complaintOn) {
				echo WRONG_DATE;
				die();
		}
		}

		$handleComplaintDetail = $handleComplaintsManager -> updateHandleComplaints($complaintId,$complaintStatus,$completionDate,$remarks);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;
            }
    }
	else {
			echo $errorMessage;
	}
// $History: ajaxInitHandleComplaintsAdd.php $
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
//
//

?>