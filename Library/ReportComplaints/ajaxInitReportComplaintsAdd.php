<?php
//-------------------------------------------------------
// Purpose: To add hostel room detail
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ReportComplaintsMaster');
define('ACCESS','add');
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

        $foundArray = ReportComplaintsManager::getInstance()->getReportComplaintDetail('AND c.subject="'.add_slashes(trim($REQUEST_DATA['subject'])).'" AND hs.hostelId="'.add_slashes(trim($REQUEST_DATA['hostel'])).'" AND c.complaintCategoryId="'.add_slashes(trim($REQUEST_DATA['category'])).'"');
		
			if(trim($foundArray[0]['subject'])=='') {  //DUPLICATE CHECK
				/*if ($REQUEST_DATA['complaintStatus'] == 2) {
				$foundTrackingNumberArray = ReportComplaintsManager::getInstance()->getReportComplaintDetail('AND c.subject="'.add_slashes(trim($REQUEST_DATA['subject'])).'" AND hs.hostelId="'.add_slashes(trim($REQUEST_DATA['hostel'])).'" AND c.complaintId="'.add_slashes(trim($REQUEST_DATA['trackingNumber'])).'"');
				//AND c.complaintStatus="'.add_slashes($REQUEST_DATA['complaintStatus']).'"
				
				  if(count($foundTrackingNumberArray) == 0 ) {
					 echo NO_COMPLAINT_FOUND;
					 die();
					}
				}*/
				$checkDuplicateTrackArray = ReportComplaintsManager::getInstance()->checkDuplicateTrackDetail('WHERE c.trackingNumber="'.$REQUEST_DATA['trackingNumber'].'"');
				if($checkDuplicateTrackArray[0]['trackingNumber'] > 0 ) {
					echo NO_TRACKING_NUMBER_FOUND;
					die();
				}
				
				if ($REQUEST_DATA['trackingNumber'] != "" ) {
					$returnStatus = ReportComplaintsManager::getInstance()->UpdateReportComplaints('WHERE complaintId ="'.$REQUEST_DATA['trackingNumber'].'"');
				}
				if ($REQUEST_DATA['complaintStatus'] == 2) {
					$REQUEST_DATA['complaintStatus'] = 1;
					$returnStatus = ReportComplaintsManager::getInstance()->addReportEscalateComplaints($REQUEST_DATA['complaintStatus']);
				}
				else {
					$returnStatus = ReportComplaintsManager::getInstance()->addReportComplaints();
				}
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else { 
				echo RECORD_ALREADY_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitReportComplaintsAdd.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 6:59p
//Updated in $/LeapCC/Library/ReportComplaints
//changes done as per discussed with Pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//
?>