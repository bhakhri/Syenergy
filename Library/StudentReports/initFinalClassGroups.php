<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 15-July-2008
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
	$reportManager = StudentReportsManager::getInstance();

	if (isset($REQUEST_DATA['degree'])) {
		$classId = $REQUEST_DATA['degree'];
	}
	elseif (isset($REQUEST_DATA['class1'])) {
		$classId = $REQUEST_DATA['class1'];
	}
	 
    if(isset($REQUEST_DATA['subjectId'])) {
      $subjectId = $REQUEST_DATA['subjectId'];
    }
     
	
	if (isset($REQUEST_DATA['labelId'])) {
		$timeTable = $REQUEST_DATA['labelId'];
	}
	elseif (isset($REQUEST_DATA['timeTable'])) {
		$timeTable = $REQUEST_DATA['timeTable'];
	}
    
    if(trim($classId)=='' or trim($timeTable)==''){
            
    }
    

	//fetching subject data only if any one class is selected

	if ($classId != 'all') {
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		if($roleId == 1) {
            $condition = " AND tt.classId = '$classId' AND tt.timeTableLabelId='$timeTable' AND tt.subjectId = '$subjectId' ";
            $employeeId = trim($REQUEST_DATA['employeeId']);    
            if($employeeId!='all' && $employeeId!='') {
              $condition .= " AND tt.employeeId = '$employeeId' ";  
            }
            $groupArray = $reportManager->getFinalTransferredGroup($condition);  
		}
		elseif ($roleId == 2) {
			$employeeId = $sessionHandler->getSessionVariable('EmployeeId');
            $condition = " AND tt.classId = '$classId' AND tt.timeTableLabelId='$timeTable' AND tt.subjectId = '$subjectId'  ";      
            $condition .= " AND tt.employeeId = '$employeeId' ";  
			$groupArray = $reportManager->getFinalTransferredGroup($condition);  
		}
		else {
			$condition = " AND tt.classId = '$classId' AND tt.timeTableLabelId='$timeTable' AND tt.subjectId = '$subjectId' ";
            $employeeId = trim($REQUEST_DATA['employeeId']);    
            if($employeeId!='all' && $employeeId!='') {
              $condition .= " AND tt.employeeId = '$employeeId' ";  
            }
            $groupArray = $reportManager->getFinalTransferredGroup($condition);  
		}
		echo json_encode($groupArray);
	}


?>