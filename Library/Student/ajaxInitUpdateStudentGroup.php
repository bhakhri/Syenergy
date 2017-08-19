<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Ajinder Singh
// Created on : 07-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdateStudentGroups');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");

global $sessionHandler;
$queryDescription =''; 
$studentManager = StudentManager::getInstance();

$rollNo = $REQUEST_DATA['rollNo'];

$studentDetailArray = $studentManager->getStudentDetail($rollNo);
if (!isset($studentDetailArray[0]['studentId']) or empty($studentDetailArray[0]['studentId'])) {
	echo INVALID_ROLL_NO;
	echo ' or this student does not belong to current session/institute';
	die;
}
$studentId = $studentDetailArray[0]['studentId'];
$studentName = $studentDetailArray[0]['studentName'];
$classId = $studentDetailArray[0]['classId'];
$subjectsArray = $studentManager->getClassSubjects($classId);

$classThGroups = $studentManager->getClassGroupTypeGroups($classId,3);//Theory Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$thGroupId = 0;
foreach($classThGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$thGroupId = $classRecord['groupId'];
		$thGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}
$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,3);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo THEORY_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}
/*
if ($noGroupSelected == true) {
	echo NO_THEORY_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_THEORY_GROUP_SELECTED;
	die;
}
*/
$classTutGroups = $studentManager->getClassGroupTypeGroups($classId,1);//Tut Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$tutGroupId = 0;
foreach($classTutGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$tutGroupId = $classRecord['groupId'];
		$tutGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}
$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,1);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo TUTORIAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}
/*
if ($noGroupSelected == true) {
	echo NO_TUTORIAL_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_TUTORIAL_GROUP_SELECTED;
	die;
}
*/
if ($cnt > 0) {
	$relationArray = $studentManager->isGroupRelatedToTheory($thGroupId,$tutGroupId);
	$relation = $relationArray[0]['relation'];
	if ($relation == 0) {
		echo TUT_GROUP_NOT_RELATED_TO_THEORY;
		die;
	}
}

$classPrGroups = $studentManager->getClassGroupTypeGroups($classId,2);//Pr Groups
$noGroupSelected = true;
$totalGroupSelected = 0;
$prGroupId = 0;
foreach($classPrGroups as $classRecord) {
	if (isset($REQUEST_DATA['chk_'.$classRecord['groupId']])) {
		$prGroupId = $classRecord['groupId'];
		$prGroupShort = $classRecord['groupShort'];
		$noGroupSelected = false;
		$totalGroupSelected++;
	}
}
$oldGroupCountArray = $studentManager->countOldGroups($studentId, $classId,2);
$cnt = $oldGroupCountArray[0]['cnt'];
if ($cnt != $totalGroupSelected) {
	echo PRACTICAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH;
	die;
}
/*
if ($noGroupSelected == true) {
	echo NO_PRACTICAL_GROUP_SELECTED;
	die;
}
if ($totalGroupSelected > 1) {
	echo MORE_THAN_ONE_PRACTICAL_GROUP_SELECTED;
	die;
}
*/
if ($cnt > 0) {
	$relationArray = $studentManager->isGroupRelatedToTheory($thGroupId,$prGroupId);
	$relation = $relationArray[0]['relation'];
	if ($relation == 0) {
		echo PRACTICAL_GROUP_NOT_RELATED_TO_THEORY;
		die;
	}
}


$studentThGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 3)");
$studentThGroupId = $studentThGroups[0]['groupId'];
$studentThGroupShort = $studentThGroups[0]['groupShort'];

$studentTutGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 1)");
$studentTutGroupId = $studentTutGroups[0]['groupId'];
$studentTutGroupShort = $studentTutGroups[0]['groupShort'];

$studentPrGroups = $studentManager->getStudentCurrentGroups($studentId,$classId," AND a.groupId in (select groupId from `group` where groupTypeId = 2)");
$studentPrGroupId = $studentPrGroups[0]['groupId'];
$studentPrGroupShort = $studentPrGroups[0]['groupShort'];

$presentArray = $studentManager->getSingleField('attendance_code', 'attendanceCodeId', 'where attendanceCodePercentage = 100');
$presentAttendanceCode = $presentArray[0]['attendanceCodeId'];
$absentArray = $studentManager->getSingleField('attendance_code', 'attendanceCodeId', 'where attendanceCodePercentage = 0');
$absentAttendanceCode = $absentArray[0]['attendanceCodeId'];



require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	foreach($subjectsArray as $subjectRecord) {
		$subjectId = $subjectRecord['subjectId'];
		$subjectCode = $subjectRecord['subjectCode'];

		if ($studentThGroupId != $thGroupId) {
			$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$thGroupId);
			$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
			$newLectureAttended = $REQUEST_DATA['att_'.$thGroupId.'_'.$subjectId];
			if (!isset($newLectureAttended) or !is_numeric($newLectureAttended)) {
				$newLectureAttended = 0;
			}

            if($newLectureAttended>$newLectureDelivered){
                echo MAX_LECTURE_DELIVERED_ERROR;
                die;
            }

			if ($newLectureDelivered > 0) {
				$attDetailsArray = $studentManager->getGroupAttendanceDetails($classId,$subjectId,$thGroupId);
				$totalAttended = 0;
				foreach($attDetailsArray as $attRecod) {
					$newEmployeeId = $attRecod['employeeId'];
					$attendanceType = $attRecod['attendanceType'];
					$attendanceCodeId = $attRecod['attendanceCodeId'];
					$periodId = $attRecod['periodId'];
					$attFromDate = $attRecod['fromDate'];
					$attToDate = $attRecod['toDate'];
					$attLectureDelivered = $attRecod['lectureDelivered'];
					$topicsTaughtId = $attRecod['topicsTaughtId'];
					if ($attendanceType == '1' or $attendanceType == 1) {
						if (($totalAttended + $attLectureDelivered) < $newLectureAttended) {
							$totalAttended += $attLectureDelivered;
							$return = $studentManager->addAttendanceInTransaction($classId,$thGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $attLectureDelivered, $topicsTaughtId);
							$thisAttendanceValue = "\n".'total attended '.$totalAttended.' this delivered '.  $attLectureDelivered. 'new lecture attended '.$newLectureAttended;
							//echo "\n +++ Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :'. $attLectureDelivered;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$thGroupShort;
								die;
							}
						}
						else {
							$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$thGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $totalAttended + $attLectureDelivered - $newLectureAttended, $topicsTaughtId);

							$thisValue = $totalAttended + $attLectureDelivered - $newLectureAttended;

							//echo "\n +++ Subject Code: $subjectCode Delivered: $attLectureDelivered  newAtteded: ".$newLectureAttended." Attended : ".$thisValue;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$thGroupShort;
								die;
							} 
						}
					}
					else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
						if (($totalAttended + 1) <= $newLectureAttended) {
							$totalAttended += 1;
							$return = $studentManager->addAttendanceInTransaction($classId,$thGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $presentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ---- Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Present';
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$thGroupShort;
								die;
							}
						}
						else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$thGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $absentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Absent';
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$thGroupShort;
								die;
							}
						}
					}
				}
			}
			$return = $studentManager->quarantineAttendanceInTransaction($classId,$studentThGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_.$thGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$return = $studentManager->deleteAttendanceInTransaction($classId,$studentThGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_DELETING_ATTENDANCE_FOR_.$thGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$thGroupId);
			foreach($newTestArray as $newTestRecord) {
				$newTestId = $newTestRecord['testId'];
				$newTestName = $newTestRecord['testName'];
				$newMaxMarks = $newTestRecord['maxMarks'];
				$newMarksScored = $REQUEST_DATA['test_'.$newTestId.'_'.$subjectId];
				if (!isset($newMarksScored) or !is_numeric($newMarksScored)) {
					$newMarksScored = 0;
				}

                if($newMarksScored>$newMaxMarks){
                   echo MAX_MARKS_SCORED_ERROR;
                   die;
                }

				if ($newMaxMarks > 0) {
					$return = $studentManager->addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, 1, 1);
					if ($return == false) {
						echo ERROR_WHILE_SAVING_MARKS_FOR_.$thGroupShort._FOR_TEST_.$newTestName;
						die;
					}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				}
			}

			$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentThGroupId);
			foreach($oldTestArray as $oldTestRecord) {
				$oldTestId = $oldTestRecord['testId'];
				$return = $studentManager->quarantineMarksInTransaction($oldTestId,$studentId, $classId, $subjectId);
				if ($return == false) {
					echo ERROR_WHILE_QUARANTINING_MARKS_FOR_.$thGroupShort._FOR_TEST_.$newTestName;
					die;
				}
				$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				$return = $studentManager->deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $studentThGroupId);
				if ($return == false) {
					echo ERROR_WHILE_DELETING_MARKS_FOR_.$thGroup._FOR_TEST_.$newTestName;
					die;
				}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
			}
		}
		if ($studentTutGroupId != $tutGroupId) {
			$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$tutGroupId);
			$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
			$newLectureAttended = $REQUEST_DATA['att_'.$tutGroupId.'_'.$subjectId];
			if (!isset($newLectureAttended) or !is_numeric($newLectureAttended)) {
				$newLectureAttended = 0;
			}
            if($newLectureAttended>$newLectureDelivered){
                echo MAX_LECTURE_DELIVERED_ERROR;
                die;
            }

			if ($newLectureDelivered > 0) {
				$attDetailsArray = $studentManager->getGroupAttendanceDetails($classId,$subjectId,$tutGroupId);
				$totalAttended = 0;
				foreach($attDetailsArray as $attRecod) {
					$newEmployeeId = $attRecod['employeeId'];
					$attendanceType = $attRecod['attendanceType'];
					$attendanceCodeId = $attRecod['attendanceCodeId'];
					$periodId = $attRecod['periodId'];
					$attFromDate = $attRecod['fromDate'];
					$attToDate = $attRecod['toDate'];
					$attLectureDelivered = $attRecod['lectureDelivered'];
					$topicsTaughtId = $attRecod['topicsTaughtId'];
					if ($attendanceType == '1' or $attendanceType == 1) {
						if (($totalAttended + $attLectureDelivered) < $newLectureAttended) {
							$totalAttended += $attLectureDelivered;
							$return = $studentManager->addAttendanceInTransaction($classId,$tutGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $attLectureDelivered, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :'. $attLectureDelivered;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$tutGroupShort;
								die;
							}
						}
						else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$tutGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $totalAttended + $attLectureDelivered - $newLectureAttended, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :'. $totalAttended + $attLectureDelivered - $newLectureAttended;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$tutGroupShort;
								die;
							}
						}
					}
					else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
						if (($totalAttended + 1) <= $newLectureAttended) {
							$totalAttended += 1;
							$return = $studentManager->addAttendanceInTransaction($classId,$tutGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $presentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Present'.$presentAttendanceCode;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$tutGroupShort;
								die;
							}
						}
						else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$tutGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $absentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Absent';
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$tutGroupShort;
								die;
							} $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
						}
					}
				}
			}

			$return = $studentManager->quarantineAttendanceInTransaction($classId,$studentTutGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_.$tutGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$return = $studentManager->deleteAttendanceInTransaction($classId,$studentTutGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_DELETING_ATTENDANCE_FOR_.$tutGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$tutGroupId);
			foreach($newTestArray as $newTestRecord) {
				$newTestId = $newTestRecord['testId'];
				$newTestName = $newTestRecord['testName'];
				$newMaxMarks = $newTestRecord['maxMarks'];
				$newMarksScored = $REQUEST_DATA['test_'.$newTestId.'_'.$subjectId];
				if (!isset($newMarksScored) or !is_numeric($newMarksScored)) {
					$newMarksScored = 0;
				}

                if($newMarksScored>$newMaxMarks){
                   echo MAX_MARKS_SCORED_ERROR;
                   die;
                }

				if ($newMaxMarks > 0) {
					$return = $studentManager->addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, 1, 1);
					if ($return == false) {
						echo ERROR_WHILE_SAVING_MARKS_FOR_.$tutGroupShort._FOR_TEST_.$newTestName;
						die;
					}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				}
			}

			$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentTutGroupId);
			foreach($oldTestArray as $oldTestRecord) {
				$oldTestId = $oldTestRecord['testId'];
				$return = $studentManager->quarantineMarksInTransaction($oldTestId,$studentId, $classId, $subjectId);
				if ($return == false) {
					echo ERROR_WHILE_QUARANTINING_MARKS_FOR_.$tutGroupShort._FOR_TEST_.$newTestName;
					die;
				}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				$return = $studentManager->deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $studentTutGroupId);
				if ($return == false) {
					echo ERROR_WHILE_DELETING_MARKS_FOR_.$tutGroupShort._FOR_TEST_.$newTestName;
					die;
				}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
			}
		}
		if ($studentPrGroupId != $prGroupId) {
			$newAttendanceArray = $studentManager->getGroupAttendance($classId,$subjectId,$prGroupId);
			$newLectureDelivered = $newAttendanceArray[0]['lectureDelivered'];
			$newLectureAttended = $REQUEST_DATA['att_'.$prGroupId.'_'.$subjectId];
			if (!isset($newLectureAttended) or !is_numeric($newLectureAttended)) {
				$newLectureAttended = 0;
			}

            if($newLectureAttended>$newLectureDelivered){
                echo MAX_LECTURE_DELIVERED_ERROR;
                die;
            }

			if ($newLectureDelivered > 0) {
				$attDetailsArray = $studentManager->getGroupAttendanceDetails($classId,$subjectId,$prGroupId);
				$totalAttended = 0;
				foreach($attDetailsArray as $attRecod) {
					$newEmployeeId = $attRecod['employeeId'];
					$attendanceType = $attRecod['attendanceType'];
					$attendanceCodeId = $attRecod['attendanceCodeId'];
					$periodId = $attRecod['periodId'];
					$attFromDate = $attRecod['fromDate'];
					$attToDate = $attRecod['toDate'];
					$attLectureDelivered = $attRecod['lectureDelivered'];
					$topicsTaughtId = $attRecod['topicsTaughtId'];
					if ($attendanceType == '1' or $attendanceType == 1) {
						if (($totalAttended + $attLectureDelivered) < $newLectureAttended) {
							$totalAttended += $attLectureDelivered;
							$return = $studentManager->addAttendanceInTransaction($classId,$prGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $attLectureDelivered, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :'. $attLectureDelivered;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$prGroupShort;
								die;
							}
						}
						else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$prGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, "NULL", "NULL", $attLectureDelivered, $totalAttended + $attLectureDelivered - $newLectureAttended, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :'. $totalAttended + $attLectureDelivered - $newLectureAttended;
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$prGroupShort;
								die;
							}
						}
					}
					else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
						if (($totalAttended + 1) <= $newLectureAttended) {
							$totalAttended += 1;
							$return = $studentManager->addAttendanceInTransaction($classId,$prGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $presentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Present';
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$prGroupShort;
								die;
							}
						}
						else {  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
							$return = $studentManager->addAttendanceInTransaction($classId,$prGroupId, $studentId, $subjectId, $newEmployeeId, $attFromDate, $attToDate, $attendanceType, $absentAttendanceCode, $periodId, $attLectureDelivered, 0, $topicsTaughtId);
							//echo "\n ----Subject Code: ".$subjectCode.'Delivered: '.$attLectureDelivered.' Attended :Absent';
							if ($return == false) {
								echo ERROR_WHILE_SAVING_ATTENDANCE_FOR_.$prGroupShort;
								die;
							} $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
						}
					}
				}
			}



			$return = $studentManager->quarantineAttendanceInTransaction($classId,$studentPrGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_.$prGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$return = $studentManager->deleteAttendanceInTransaction($classId,$studentPrGroupId, $studentId, $subjectId);
			if ($return == false) {
				echo ERROR_WHILE_DELETING_ATTENDANCE_FOR_.$prGroupShort;
				die;
			}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 

			$newTestArray = $studentManager->getGroupTests($classId,$subjectId,$prGroupId);
			foreach($newTestArray as $newTestRecord) {
				$newTestId = $newTestRecord['testId'];
				$newTestName = $newTestRecord['testName'];
				$newMaxMarks = $newTestRecord['maxMarks'];
				$newMarksScored = $REQUEST_DATA['test_'.$newTestId.'_'.$subjectId];
				if (!isset($newMarksScored) or !is_numeric($newMarksScored)) {
					$newMarksScored = 0;
				}

                if($newMarksScored>$newMaxMarks){
                   echo MAX_MARKS_SCORED_ERROR;
                   die;
                }

				if ($newMaxMarks > 0) {
					$return = $studentManager->addMarksInTransaction($newTestId, $studentId, $subjectId, $newMaxMarks, $newMarksScored, 1, 1);
					if ($return == false) {
						echo ERROR_WHILE_SAVING_MARKS_FOR_.$prGroupShort._FOR_TEST_.$newTestName;
						die;
					}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				}
			}

			$oldTestArray = $studentManager->getStudentOldTests($studentId, $classId, $subjectId, $studentPrGroupId);
			foreach($oldTestArray as $oldTestRecord) {
				$oldTestId = $oldTestRecord['testId'];
				$return = $studentManager->quarantineMarksInTransaction($oldTestId,$studentId, $classId, $subjectId);
				if ($return == false) {
					echo ERROR_WHILE_QUARANTINING_MARKS_FOR_.$prGroupShort._FOR_TEST_.$newTestName;
					die;
				}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
				$return = $studentManager->deleteMarksInTransaction($oldTestId,$studentId, $classId, $subjectId, $studentPrGroupId);
				if ($return == false) {
					echo ERROR_WHILE_DELETING_MARKS_FOR_.$prGroupShort._FOR_TEST_.$newTestName;
					die;
				}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
			}
		}
	}


	if ($thGroupId != 0) {
		$return = $studentManager->updateStudentGroupInTransaction($classId,$studentId, $studentThGroupId, $thGroupId);
		if ($return == false) {
			echo ERROR_WHILE_UPDATING_STUDENT_GROUP_.$prGroupShort;
			die;
		}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
	}
	if ($tutGroupId != 0) {
		$return = $studentManager->updateStudentGroupInTransaction($classId,$studentId, $studentTutGroupId, $tutGroupId);
		if ($return == false) {
			echo ERROR_WHILE_UPDATING_STUDENT_GROUP_.$prGroupShort;
			die;
		}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
	}
	if ($prGroupId != 0) {
		$return = $studentManager->updateStudentGroupInTransaction($classId,$studentId, $studentPrGroupId, $prGroupId);
		if ($return == false) {
			echo ERROR_WHILE_UPDATING_STUDENT_GROUP_.$prGroupShort;
			die;
		}$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
	}
	
	//die("\n reached here");
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	$oldGroups = $studentThGroupShort.','.$studentTutGroupShort.','.$studentPrGroupShort;
	$newGroups = $thGroupShort.','.$tutGroupShort.','.$prGroupShort;
	$student = $studentName.'('.$rollNo.')';
	$auditTrialDescription = "Following groups have been changed for student $student : Old groups : $oldGroups New groups : $newGroups" ;
	$type =GROUPS_CHANGED; //Groups changed 
	$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
	if($returnStatus == false) {
		echo  ERROR_WHILE_SAVING_DATA_IN_AUDIT_TRAIL;
		die;
	}
	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
		echo SUCCESS;
	}
	else {
		echo FAILURE;
	}
}
else {
	echo FAILURE;
}


//$History: ajaxInitUpdateStudentGroup.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/21/09    Time: 11:26a
//Updated in $/LeapCC/Library/Student

//file changed to correct attendance problem.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 9/15/09    Time: 3:41p
//Updated in $/LeapCC/Library/Student
//done changes to allow user to save data without selecting tutorial
//group/practical group
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/08/09    Time: 5:10p
//Updated in $/LeapCC/Library/Student
//changed queries from test type to test type categories.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:30a
//Updated in $/LeapCC/Library/Student
//fixed bug no.1204
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:40p
//Created in $/LeapCC/Library/Student
//file added for group change
//






?>
