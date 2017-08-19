<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Jaineesh
// Created on : 17-02-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UploadStudentExternalMarks');
    define('ACCESS','view');
    global $sessionHandler;   
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') {      // Teacher Login
      UtilityManager::ifTeacherNotLoggedIn(true); 
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
    
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    
	$timeTable = $REQUEST_DATA['timeTable'];
    if($timeTable=='') {
      $timeTable='0';  
    }
    
    $groupsArray = $studentManager->getLabelClassNew($timeTable);
	echo json_encode($groupsArray);
	
	 
?>