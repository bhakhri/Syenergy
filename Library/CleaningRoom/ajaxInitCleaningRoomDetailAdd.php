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
define('MODULE','CleaningMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['safaiwala']) || trim($REQUEST_DATA['safaiwala']) == '')) {
        $errorMessage .= CHOOSE_SAFAIWALA. "\n";
    }
    /*if ($errorMessage == '' && (!isset($REQUEST_DATA['date']) || trim($REQUEST_DATA['date']) == '')) {
        $errorMessage .= SELECT_DATE. "\n";
    }*/
	if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelName']) || trim($REQUEST_DATA['hostelName']) == '')) {
        $errorMessage .= CHOOSE_HOSTEL. "\n";
    }
	/*if ($errorMessage == '' && (!isset($REQUEST_DATA['toiletsNo']) || trim($REQUEST_DATA['toiletsNo']) == '')) {
        $errorMessage .= ENTER_TOILET_NO. "\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['roomsNo']) || trim($REQUEST_DATA['roomsNo']) == '')) {
        $errorMessage .= ENTER_ROOM. "\n";
    }*/
	if ($errorMessage == '' && (!isset($REQUEST_DATA['noOfhrs']) || trim($REQUEST_DATA['noOfhrs']) == '')) {
        $errorMessage .= ENTER_NO_HRS. "\n";
    }
    

    if (trim($errorMessage) == '') {

        require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");

        $foundArray = CleaningRoomManager::getInstance()->getCleaningRoom('AND et.tempEmployeeId ="'.add_slashes(trim($REQUEST_DATA['safaiwala'])).'" AND hs.hostelId="'.add_slashes(trim($REQUEST_DATA['hostelName'])).'" AND hcr.Dated ="'.add_slashes(trim($REQUEST_DATA['date'])).'"');
			if(trim($foundArray[0]['tempEmployeeId'])=='') {  //DUPLICATE CHECK
				$returnStatus = CleaningRoomManager::getInstance()->addCleaningRoom();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;
				}
			}
			else {
				echo SAFAIWALA_ALREADY_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitCleaningRoomDetailAdd.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Library/CleaningRoom
//fixed bugs
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:32p
//Updated in $/LeapCC/Library/CleaningRoom
//remove mendatory fields 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:27p
//Created in $/LeapCC/Library/CleaningRoom
//all ajax files for cleaning room
//
//
?>