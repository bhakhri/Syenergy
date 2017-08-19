<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSSTOP 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStaffMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['staffName']) || trim($REQUEST_DATA['staffName']) == '') {
        echo ENTER_STAFF_NAME;
		die;
    }
    if (!isset($REQUEST_DATA['staffCode']) || trim($REQUEST_DATA['staffCode']) == '') {
        echo ENTER_STAFF_CODE;
		die;
    }
    if (!isset($REQUEST_DATA['dlNo']) || trim($REQUEST_DATA['dlNo']) == '') {
        echo ENTER_DRIVING_LICENSE;
		die;
    }
    if (!isset($REQUEST_DATA['staffType']) || trim($REQUEST_DATA['staffType']) == '') {
        echo SELECT_STAFF_TYPE;
		die;
    }
    if (!isset($REQUEST_DATA['dlAuthority']) || trim($REQUEST_DATA['dlAuthority']) == '') {
        echo ENTER_DRIVING_LICENSE_AUTHORITY;
		die;
    }
	if(strtotime($REQUEST_DATA['dob']) > strtotime("now") ){
		echo DATE_OF_BIRTH_VALIDATION;
		die;
	}
	if(strtotime($REQUEST_DATA['dob']) > strtotime($REQUEST_DATA['join']) ){
		echo BIRTH_DATE_VALIDATION;
		die;
	}
	if(strtotime($REQUEST_DATA['join']) > strtotime("now")){
		echo JOINING_DATE_VALIDATION;
		die;
	}
	
	if(strtotime($REQUEST_DATA['issueDate']) > strtotime("now") ){
		echo ISSUE_DATE_VALIDATION;
		die;
	}

	if(strtotime($REQUEST_DATA['dlExp']) <= strtotime($REQUEST_DATA['issueDate']) ){
		echo EXPIRY_DATE_VALIDATION;
		die;
	}
    
	$sessionHandler->setSessionVariable('DUPLICATE_USER','');
	$sessionHandler->setSessionVariable('OPERATION_MODE',1);
	$sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
	$sessionHandler->setSessionVariable('HiddenDLFile',$REQUEST_DATA['hiddenDLFile']);

	require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
    $foundArray = TransportStaffManager::getInstance()->getTransportStaff(' WHERE UCASE(staffCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['staffCode']))).'" OR dlNo = "'.add_slashes(trim(strtoupper($REQUEST_DATA['dlNo']))).'"');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		if(trim($foundArray[0]['staffCode'])=='') {  //DUPLICATE CHECK
			$returnStatus = TransportStaffManager::getInstance()->addTransportStaff();
			if($returnStatus === false) {
				echo FAILURE;
				die;
			}
				$staffId=SystemDatabaseManager::getInstance()->lastInsertId();
		 }
		else {
			if(strtoupper($foundArray[0]['staffCode'])==trim(strtoupper($REQUEST_DATA['staffCode']))) {
			 echo STAFF_CODE_ALREADY_EXIST;
			 $sessionHandler->setSessionVariable('DUPLICATE_USER',STAFF_CODE_ALREADY_EXIST);
			 die;
			 }
		   else if(strtoupper($foundArray[0]['dlNo'])==trim(strtoupper($REQUEST_DATA['dlNo']))) {
			   echo DRIVING_LICENSE_EXIST;
			   $sessionHandler->setSessionVariable('DUPLICATE_USER',DRIVING_LICENSE_EXIST);
			   die;
		   }
		 }
		   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			   //echo 'hi';
				$sessionHandler->setSessionVariable('IdToFileUpload',$staffId);
				echo SUCCESS;
				die;
			 }
			 else {
				echo FAILURE;
				die;
			}
		}
		else {
			echo FAILURE;
			die;
		}
    

// $History: ajaxInitAdd.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Library/TransportStaff
//fixed bug during self testing
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Library/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Library/TransportStaff
//add new fields and upload image
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Library/TransportStaff
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 2  *****************
//User: Administrator Date: 4/06/09    Time: 11:39
//Updated in $/Leap/Source/Library/TransportStuff
//Fixed bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>
