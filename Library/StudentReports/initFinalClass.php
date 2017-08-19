<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Ajinder Singh
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if ($roleId == 1) {
       $employeeId = trim($REQUEST_DATA['employeeId']);    
       $condition = " AND tt.timeTableLabelId='$timeTable' ";   
       if($employeeId!='all' && $employeeId!='') {
          $condition .= " AND tt.employeeId = '$employeeId' ";  
       }  
	   $classArray = $studentReportsManager->getFinalTransferredClass($condition);
	   echo json_encode($classArray);
	}
	elseif($roleId == 2) {
		$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
		$classArray = $studentReportsManager->getLabelMarksTransferredClassTeacher($timeTable, $employeeId);
		echo json_encode($classArray);
	}
	else {
		$employeeId = trim($REQUEST_DATA['employeeId']);    
        $condition = " AND tt.timeTableLabelId='$timeTable' ";   
        if($employeeId!='all' && $employeeId!='') {
          $condition .= " AND tt.employeeId = '$employeeId' ";  
        }  
		$classArray = $studentReportsManager->getFinalTransferredClass($condition);
		echo json_encode($classArray);
	}

?>