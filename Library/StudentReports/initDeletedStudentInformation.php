<?php
//-------------------------------------------------------
// Purpose: To show student personal information
//
// Author : Jaineesh
// Created on : (17.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
    global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();
    define('MODULE','DeletedStudentReport');
    define('ACCESS','view');
	
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
    
	$studentId = $REQUEST_DATA['studentId'];

	    
    $errorMessage='';
    
    
     $orderBy = " $sortField $sortOrderBy";

    if (trim($errorMessage=='')){
	
        $studentInformationArray = $studentManager->getDeletedStudentInformationList($studentId);
	}
    else{
        $errorMessage="The studentId has not been recognised";
    }
    
// for VSS
// $History: $
//
?>