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
	global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $attendanceSetId = add_slashes(trim($REQUEST_DATA['attendanceSetId']));    
    
	if (trim($errorMessage) == '') {
		
		$totalUGDegree = count($REQUEST_DATA['ugDegree']);
		$totalPGDegree = count($REQUEST_DATA['pgDegree']);
		$totalHighestQual = count($REQUEST_DATA['highestQual']);
		$totalOtherQual = count($REQUEST_DATA['otherQual']);
		$employeeId = $REQUEST_DATA['employeeId'];

		//echo($totalUGDegree);
		//die;

		
        // Check Validations
        for($i = 0; $i < $totalUGDegree; $i++) {
			if(trim($REQUEST_DATA['ugDegree'][$i]) == '' && trim($REQUEST_DATA['pgDegree'][$i]) == '' && trim($REQUEST_DATA['highestQual'][$i]) == '' && trim($REQUEST_DATA['otherQual'][$i]) == '') {
					echo "Please fill the value of empty box"; 
					die;   
				}
			for($j = $i+1; $j < $totalUGDegree; $j++) {
				if($REQUEST_DATA['ugDegree'][$i] != '') {
					if(strtolower($REQUEST_DATA['ugDegree'][$i]) == strtolower($REQUEST_DATA['ugDegree'][$j])) {
						echo "Already given the value UG Degree";
						die;
					}
					if(is_numeric($REQUEST_DATA['ugDegree'][$i])) {
						echo "Accept only alphabets";
						die;
					}
				}

				if($REQUEST_DATA['pgDegree'][$i] != '') {
					if(strtolower($REQUEST_DATA['pgDegree'][$i]) == strtolower($REQUEST_DATA['pgDegree'][$j])) {
						echo "Already given the value for PG Degree";
						die;
					}
				}
				if($REQUEST_DATA['highestQual'][$i] != '') {
					if(strtolower($REQUEST_DATA['highestQual'][$i]) == strtolower($REQUEST_DATA['highestQual'][$j])) {
						echo "Already given the value for Highest Qualification";
						die;
					}
				}
				if($REQUEST_DATA['otherQual'][$i] != '') {
					if(strtolower($REQUEST_DATA['otherQual'][$i]) == strtolower($REQUEST_DATA['otherQual'][$j])) {
						echo "Already given the value for Other Qualification";
						die;
					}
				}
			 }
          }

        // Delete all Records
        $returnDeleteStatus = $employeeManager->deleteEmployeeQualification($employeeId);
        if($returnDeleteStatus === false) {
           $errorMessage = FAILURE;
        }
        $str = '';
		if($totalUGDegree > 0 ) {
        for($i = 0; $i < $totalUGDegree; $i++) {
			$ugDegree = addslashes($REQUEST_DATA['ugDegree'][$i]);
            $pgDegree = addslashes($REQUEST_DATA['pgDegree'][$i]);
			$highestQual = addslashes($REQUEST_DATA['highestQual'][$i]);
            $otherQual = addslashes($REQUEST_DATA['otherQual'][$i]);
			//if($lectureAttendedFrom <= $lectureAttendedTo) {
				if(!empty($str)) {
					$str .= ',';
				}
				$str .= "($employeeId, '$ugDegree', '$pgDegree', '$highestQual', '$otherQual')";
				//$str .= "($lectureDelivered, $lectureAttendedFrom, $marksScored, $subjectTypeId, $timeTableLabelId, $instituteId, $degreeId)";
			//}
        }
	
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$returnStatus = $employeeManager->addQualification($str);
			if($returnStatus === false) {
				$errorMessage = FAILURE;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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
	  }
	  else {
		echo SUCCESS;
		die;
	  }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitQualificationAdd.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:32p
//Created in $/LeapCC/Library/Employee
//new files for employee experience, education & gap analysis
//
?>