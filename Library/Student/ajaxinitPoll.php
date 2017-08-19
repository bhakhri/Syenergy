<?php 
//  This File calls changes the password used in CHANGE PASSWORD Records
//
// Author :Jaineesh
// Created on : 09-Sept-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/StudentManager.inc.php");
	
    $studentManager = StudentManager::getInstance();
	
    $studentId= $sessionHandler->getSessionVariable('StudentId');
	$classId = $sessionHandler->getSessionVariable('ClassId');
    
	$employeeId1=$REQUEST_DATA['employeeId1'];
	$employeeId2=$REQUEST_DATA['employeeId2'];
	$employeeId3=$REQUEST_DATA['employeeId3'];
	$employeeId4=$REQUEST_DATA['employeeId4'];
	$employeeId5=$REQUEST_DATA['employeeId5'];
	
    
    if($employeeId1=='' || $employeeId2=='' || $employeeId3=='' || $employeeId4=='' || $employeeId5=='' || $studentId == '' || $classId == '') {
       echo FAILURE; 
       die;
    }
    
    
    $filter = "";
    if($studentId != '' && $classId != '') { 
      $filter = "$studentId,$classId,$employeeId1,$employeeId2,$employeeId3,$employeeId4,$employeeId5";  
    }
	
    if($filter!='') {
      if(SystemDatabaseManager::getInstance()->startTransaction()) {        
        $foundArray = $studentManager->addPollDetails($filter);
        if($foundArray === false) {
          echo FAILURE;
          die;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
          echo SUCCESS;           
        }
      }
    }
    else {
      echo FAILURE;  
    }
?>