<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Ajinder Singh
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$timeTable = $REQUEST_DATA['timeTable'];
    if(trim($timeTable)==''){
      $timeTable='0';
    }
	
    $employeeArray = $studentReportsManager->getLabelMarksTransferredTeacher($timeTable);  
    
	echo json_encode($employeeArray);
	
?>