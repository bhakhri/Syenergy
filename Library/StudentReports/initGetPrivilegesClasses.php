<?php
//-------------------------------------------------------
//  This File is used for fetching class for 
//
//
// Author :Jaineesh
// Created on : 07.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn();
    }
    else {
      UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();
   
    $studentReportsManager = StudentReportsManager::getInstance();
     
	$labelId = trim($REQUEST_DATA['labelId']);
    
    if($labelId=='') {
      $labelId=-1; 
    }
    
   
    $classArray = $studentReportsManager->getPrivilegesClasses($labelId);
    
	echo json_encode($classArray);

?>