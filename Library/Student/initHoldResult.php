<?php
//-------------------------------------------------------
// Purpose: to design the layout for add Hold/Unhold student result
//
// Author : Jaineesh
// Created on : (15.05.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','HoldUnholdStudentResult');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager  = StudentManager::getInstance();
//print_r($REQUEST_DATA);
//die;
$errorMessage ='';

$hold = $REQUEST_DATA['hold'];

    if (trim($errorMessage) == '') {
		$rollNos = addslashes($REQUEST_DATA['rollNos']);
		$rollNosArray = explode(',',$rollNos);
		$cnt = count($rollNosArray);
		$invalidRollNoArray = array();
		if($cnt > 0 AND is_array($rollNosArray)) {
			for($i=0;$i<$cnt;$i++) {
				$rollNo = trim($rollNosArray[$i]);
				if($rollNo != ''){
					$checkRollNo = $studentManager->checkExistRollNo($rollNo);
					if($checkRollNo[0]['cnt'] == 0) {
						$invalidRollNoArray[] = $rollNo;
					}
				}
				else {
					echo ROLL_NO_CANNOT_BLANK;
					die;
				}
			}

			$countInvalidRollNo = count($invalidRollNoArray);
			if($countInvalidRollNo > 0 AND is_array($invalidRollNoArray)) {
				$invalidStudentList = implode(",",$invalidRollNoArray);
				echo json_encode($invalidStudentList);
				die;
			}
		}

		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			if($cnt > 0 AND is_array($rollNosArray)) {
				for($i=0;$i<$cnt;$i++) {
					$rollNo = trim($rollNosArray[$i]);
					$studentIdArray = $studentManager->getStudentIDs($rollNo);
					$studentId = $studentIdArray[0]['studentId'];
					
					if($studentId != '') {
						if($hold == 1) {
							$studentHoldResult = $studentManager->studentHoldResult($studentId);
							if($studentHoldResult==false){
								echo FAILURE;
								die;
							}
						}
						else {
							$studentUnHoldResult = $studentManager->studentUnHoldResult($studentId);
							if($studentUnHoldResult==false){
								echo FAILURE;
								die;
							}
						}
					}
				}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				if($hold == 1) {
					echo STUDENT_HOLD_RESULT;
					die;
				}
				else {
					echo STUDENT_UNHOLD_RESULT;
					die;
				}
			 }
			 else {
				echo FAILURE;
				die;
			}
		  }
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
        echo $errorMessage;
    }

// $History: $
//
?>