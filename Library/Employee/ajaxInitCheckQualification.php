<?php
//-------------------------------------------------------
// Purpose: To add in lecture
// Author : Jaineesh
// Created on : (30.03.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/EmployeeManager.inc.php");   
	define('MODULE','EmployeeMaster');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
    $employeeManager  = EmployeeManager::getInstance();

    
	$errorMessage ='';
	//print_r($REQUEST_DATA);
	//die;

	$employeeId = $REQUEST_DATA['employeeId'];
    
	if (trim($errorMessage) == '') {

		// Delete all Records
		if($employeeId != '') {
			$returnDeleteStatus = $employeeManager->deleteEmployeeQualification($employeeId);
			if($returnDeleteStatus === false) {
			   $errorMessage = FAILURE;
			}
			echo SUCCESS;
			die;
		}
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitCheckQualification.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:17p
//Created in $/LeapCC/Library/Employee
//new files to check duplicate of records
//
//
?>