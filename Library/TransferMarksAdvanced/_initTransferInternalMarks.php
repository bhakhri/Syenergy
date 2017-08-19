<?php
//-------------------------------------------------------
//  This File contains code for transferring marks
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	ini_set('memory_limit','200M');
//	$time_start = microtime(true);
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TransferInternalMarksAdvanced');
	define('ACCESS','add');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/TransferMarksManager.inc.php");
	$transferMarksManager = TransferMarksManager::getInstance();

	if (false == $transferMarksManager->fetchTransferMarksManager()) {
		echo SOME_ERROR_HAS_OCCURED;
		die;
	}
	$transferMarksManager = $transferMarksManager->fetchTransferMarksManager();
	$transferMarksManager->setCurrentProcess('transferMarks');

	$labelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['class1'];

	$testMarksSubjectArray = array();

	$studentSubjectMarksArray = array();

	$transferMarksManager->validateTimeTableClass($labelId, $classId);
	$transferMarksManager->checkTimeTableClass($labelId, $classId);

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$classFrozenArray = CommonQueryManager::getInstance()->checkFrozenClass($classId);
	$isFrozen = $classFrozenArray[0]['isFrozen'];

	$classNameArray = $transferMarksManager->getSingleField('class', 'className', "WHERE classId = $classId");
	$className = $classNameArray[0]['className'];

	if ($isFrozen == 1) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.CLASS_FROZEN_RESTRICTION.'</b>';
		die;
	}

	$transferMarksRoundingArray = Array("ceilTotal","ceilTestType", "roundTotal", "roundTestType", "noRound");

	$errorTypeDescArray = array('fatalErrors' => 'Fatal Errors', 'testNotEntered' => 'Errors related to Tests Not Entered', 'studentsMissing' => 'Error related to Students Who have not given test', 'attendanceNotEntered' => 'Error related to attendance is not entered', 'subjectTotalNotMatching' => 'Error related to Subject Internal Marks and Test type sum not matching', 'attendanceSlabNotMade' => 'Error related to Attendance Slab not made');

	$transferSubjectsArray = $transferMarksManager->getTransferSubjects();
	$transferSubjects = implode(',', $transferSubjectsArray);

	if (!is_array($REQUEST_DATA['transferSubjects']) or !count($REQUEST_DATA['transferSubjects'])) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.NO_SUBJECT_SELECTED.'</b>';
		die;
	}

	//if ($transferMarksManager->getCurrentProcess() == 'showClassSubjects') {
		$classSubjectsArray = $transferMarksManager->getClassSubjects($classId);
		$dbSubjectsArray = explode(',',UtilityManager::makeCSList($classSubjectsArray,'subjectId'));

		foreach($REQUEST_DATA['transferSubjects'] as $key => $subjectId) {
			if (!in_array($subjectId, $dbSubjectsArray)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.INVALID_SUBJECTS.'</b>';
				die;
			}
			if (!isset($REQUEST_DATA['rounding_'.$subjectId]) or !in_array($REQUEST_DATA['rounding_'.$subjectId], $transferMarksRoundingArray)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.INVALID_ROUNDING.'</b>';
				die;
			}
			if (!in_array($subjectId, $transferSubjectsArray)) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.TRANSFER_PROCESS_ALREADY_RUNNING_IN_SAME_SESSION.'</b>';
				die;
			}
		}
	//}
	$transferMarksManager->setTransferFinalSubjects($REQUEST_DATA['transferSubjects']);
	$transferSubjectsArray = $transferMarksManager->getTransferFinalSubjects();

	$transferSubjects = implode(',', $transferSubjectsArray);
	$transferSubjectDetailsArray = $transferMarksManager->getClassSubjectsTestTypes($classId, " AND b.subjectId IN ($transferSubjects)");

	$subjectIdCodeArray = array();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$allReturnStr = '';
		$errorFound = false;

		$mmSubjectCountArray = $transferMarksManager->countMMSubjects($classId);
		$mmSubjectCount = $mmSubjectCountArray[0]['cnt'];
		if ($mmSubjectCount > 0) {
			$transferSelectedCourseCount = count($transferSubjectDetailsArray);
			$classSubjectsArray = $transferMarksManager->getClassSubjectCount($classId);
			$classSubjectCount = $classSubjectsArray[0]['cnt'];
			if ($classSubjectCount == $transferSelectedCourseCount) {
				$returnStatus = $transferMarksManager->deleteTotalTransferredMarksClass($classId);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE;
					die;
				}
				$returnStatus = $transferMarksManager->deleteTestTransferredMarksClass($classId);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE;
					die;
				}
			}
			else {
				//$inconsistenciesArray[$className]['fatalErrors'][] = CLASS_CONTAINS_MAJOR_MINOR_SUBJECTS_SELECT_ALL_SUBJECTS;
				echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.CLASS_CONTAINS_MAJOR_MINOR_SUBJECTS_SELECT_ALL_SUBJECTS.'</b>';
				die;
			}
		}
		$transferSubjectList = UtilityManager::makeCSList($transferSubjectDetailsArray, 'subjectId');

		foreach($transferSubjectDetailsArray as $subjectRecord) {
			$returnStr = '';
			$inconsistenciesArray = array();
			$inconsistencyCounter = 0;
			$studentsArray = array();
			$subjectId = $subjectRecord['subjectId'];
			$transferSubjectId = $subjectRecord['subjectId']; #SAVE A SPARE COPY OF SUBJECT ID

			$returnStatus = $transferMarksManager->deleteTotalTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}
			$returnStatus = $transferMarksManager->deleteTestTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}


			$optionalSubjectsArray = $transferMarksManager->getMMSubjects($classId, $subjectId);
			if (is_array($optionalSubjectsArray) and $optionalSubjectsArray[0]['subjectId'] != '') {
				foreach($optionalSubjectsArray as $optionalSubjectRecord) {
					$mmSubjectId = $optionalSubjectRecord['subjectId'];
					/*
					$returnStatus = $transferMarksManager->deleteTotalTransferredMarks($classId,$mmSubjectId);
					if ($returnStatus == false) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo FAILURE;
						die;
					}
					$returnStatus = $transferMarksManager->deleteTestTransferredMarks($classId,$mmSubjectId);
					if ($returnStatus == false) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo FAILURE;
						die;
					}
					*/
				}
			}

			$subjectCode = $subjectRecord['subjectCode'];
			$subjectIdCodeArray[] = $subjectId . '#' . $subjectCode;

			$internalTotalMarks = $subjectRecord['internalTotalMarks'];
			$externalTotalMarks = $subjectRecord['externalTotalMarks'];
			$hasAttendance = $subjectRecord['hasAttendance'];
			$hasMarks = $subjectRecord['hasMarks'];
			$optional = $subjectRecord['optional'];
			$hasParentCategory = $subjectRecord['hasParentCategory'];
			if ($optional == 0 and $hasParentCategory == 1) {
				$inconsistenciesArray[$className]['fatalErrors'][] = INVALID_DATA_FOUND_FOR_SUBJECT_.$subjectCode;
				continue;
			}
			$offered = $subjectRecord['offered'];
			if ($offered == 0) {
				$inconsistenciesArray[$className]['fatalErrors'][] = SUBJECT_.$subjectCode._IS_NOT_OFFERED;
			}
			if ($hasAttendance == 0 and $hasMarks == 0) {
				$inconsistenciesArray[$className]['fatalErrors'][] = SUBJECT_.$subjectCode._HAS_NO_ATTENDANCE_TEST_MARKS;
			}
			$totalInternalMarks = $internalTotalMarks + $externalTotalMarks;
			if ($totalInternalMarks <= 0) {
				$inconsistenciesArray[$className]['fatalErrors'][] = INVALID_INTERNAL_MARKS_FOR_SUBJECT_.$subjectCode;
			}

			$internalTestTypeSum = $subjectRecord['internalTestTypeSum'];
			$externalTestTypeSum = $subjectRecord['externalTestTypeSum'];
			$attendanceTestTypeSum = $subjectRecord['attendanceTestTypeSum'];

			$totalTestTypeMarks = $internalTestTypeSum + $externalTestTypeSum + $attendanceTestTypeSum;
			if ($totalTestTypeMarks <= 0) {
				$inconsistenciesArray[$className]['fatalErrors'][] = INVALID_TEST_TYPE_MARKS_FOR_SUBJECT_.$subjectCode;

			}
			else if ($internalTotalMarks != ($internalTestTypeSum + $attendanceTestTypeSum)) {
				$inconsistenciesArray[$className]['fatalErrors'][] = INTERNAL_MARKS_SUM_DOES_NOT_MATCH_WITH_TEST_TYPE_SUM_FOR_SUBJECT_.$subjectCode;

			}

			$transferMarksManager->getInconsistencyTable($inconsistenciesArray,$className, $errorTypeDescArray, $subjectCode);

			if ($errorFound == true) {
				continue;
			}

			$testTypeDetailsArray = $transferMarksManager->getTestTypeDetails($classId, $subjectId, " AND ttc.examType != 'C' HAVING weightageAmount != 0");
			foreach($testTypeDetailsArray as $testTypeRecord) {
				$testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
				$testTypeId = $testTypeRecord['testTypeId'];
				$testTypeCategoryName = $testTypeRecord['testTypeName'];
				$isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
				$evaluationCriteriaId = $testTypeRecord['evaluationCriteriaId'];
				$cnt = $testTypeRecord['cnt'];
				$weightageAmount = $testTypeRecord['weightageAmount'];
				# FOR ATTENDANCE
				if ($isAttendanceCategory == 1) {
					if ($evaluationCriteriaId != PERCENTAGES and $evaluationCriteriaId != SLABS) {
						$inconsistenciesArray[$className]['fatalErrors'][] = INVALID_DATA_FOR_TEST_TYPE_.$testTypeName._FOR_SUBJECT_.$subjectCode;
						continue;
					}
					if ($hasAttendance == 1) {
						$attendanceSetArray = $transferMarksManager->getAttendanceSetId($classId, $subjectId, $evaluationCriteriaId);
						$attendanceSetId = $attendanceSetArray[0]['attendanceSetId'];
						if (empty($attendanceSetId)) {
							$inconsistenciesArray[$className]['fatalErrors'][] = ATTENDANCE_SET_NOT_FOUND_FOR_SUBJECT_.$subjectCode;
							continue;
						}
						if ($optional == 0) {
							if ($evaluationCriteriaId == PERCENTAGES) {
								$attendanceMarksArray = $transferMarksManager->getStudentAttendancePercentageMarks($classId, $subjectId, $attendanceSetId);
								if (!count($attendanceMarksArray)) {
									$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
								}
								else {
									foreach($attendanceMarksArray as $attendanceMarksRow) {
										$studentId = $attendanceMarksRow['studentId'];
										$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
														'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
														 'maxMarks' => $weightageAmount
												);
									}
								}
							}
							elseif ($evaluationCriteriaId == SLABS) {
								$slabsArray = $transferMarksManager->getDistinctSlabsRequired($classId, $subjectId);
								if (is_array($slabsArray) and $slabsArray[0]['lectureDelivered'] != '') {
									$requiredLectureDeliveredArray = $transferMarksManager->checkSlabsMade($attendanceSetId);
									$slabsMadeArray = explode(',', UtilityManager::makeCSList($requiredLectureDeliveredArray, 'lectureDelivered'));
									$allSlabsMade = true;
									foreach($slabsArray as $slabRecord) {
										$lectureDelivered = $slabRecord['lectureDelivered'];
										if ($lectureDelivered == 0) {
											continue;
										}
										if (!in_array($lectureDelivered, $slabsMadeArray)) {
											$inconsistenciesArray[$className]['attendanceSlabNotMade'][] =  ATTENDANCE_SLAB_NOT_MADE_FOR_LECTURES_DELIVERED_.$lectureDelivered._FOR_SUBJECT_.$subjectCode;
											$allSlabsMade = false;
										}
									}
									if ($allSlabsMade == true) {
										$attendanceMarksArray = $transferMarksManager->getStudentAttendanceSlabsMarks($classId, $subjectId, $attendanceSetId);
										foreach($attendanceMarksArray as $attendanceMarksRow) {
											$studentId = $attendanceMarksRow['studentId'];
											$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
															'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
															 'maxMarks' => $weightageAmount
													);
										}
									}
								}
								else {
									$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
								}
							}
						}
						else if ($optional == 1 and $hasParentCategory = 0) {
							$subjectStudentsArray = $transferMarksManager->countOptionalSubjectStudents($classId, $subjectId);
							$subjectStudents = $subjectStudentsArray[0]['cnt'];
							if ($subjectStudents > 0) {
								if ($evaluationCriteriaId == PERCENTAGES) {
									$attendanceMarksArray = $transferMarksManager->getOptSubStudentAttendancePercentageMarks($classId, $subjectId, $attendanceSetId);
									if (!count($attendanceMarksArray)) {
										$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
									}
									else {
										foreach($attendanceMarksArray as $attendanceMarksRow) {
											$studentId = $attendanceMarksRow['studentId'];
											$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
															'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
															 'maxMarks' => $weightageAmount
													);
										}
									}
								}
								elseif ($evaluationCriteriaId == SLABS) {
									$slabsArray = $transferMarksManager->getDistinctSlabsRequired($classId, $subjectId);
									if (is_array($slabsArray) and $slabsArray[0]['lectureDelivered'] != '') {
										$requiredLectureDeliveredArray = $transferMarksManager->checkSlabsMade($attendanceSetId);
										$slabsMadeArray = explode(',', UtilityManager::makeCSList($requiredLectureDeliveredArray, 'lectureDelivered'));
										$allSlabsMade = true;
										foreach($slabsArray as $slabRecord) {
											$lectureDelivered = $slabRecord['lectureDelivered'];
											if ($lectureDelivered == 0) {
												continue;
											}
											if (!in_array($lectureDelivered, $slabsMadeArray)) {
												$inconsistenciesArray[$className]['attendanceSlabNotMade'][] =  ATTENDANCE_SLAB_NOT_MADE_FOR_LECTURES_DELIVERED_.$lectureDelivered._FOR_SUBJECT_.$subjectCode;
												$allSlabsMade = false;
											}
										}
										if ($allSlabsMade == true) {
											$attendanceMarksArray = $transferMarksManager->getOptSubStudentAttendanceSlabsMarks($classId, $subjectId, $attendanceSetId);
											foreach($attendanceMarksArray as $attendanceMarksRow) {
												$studentId = $attendanceMarksRow['studentId'];
												$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
																'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
																 'maxMarks' => $weightageAmount
														);
											}
										}
									}
									else {
										$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
									}
								}
							}
						}
						else if ($optional == 1 and $hasParentCategory = 1) {
							$subjectStudentsArray = $transferMarksManager->countPrentOptionalSubjectStudents($classId, $subjectId);
							$subjectStudents = $subjectStudentsArray[0]['cnt'];
							if ($subjectStudents > 0) {
								$optionalSubjectsArray = $transferMarksManager->getMMSubjects($classId, $subjectId);
								if ($evaluationCriteriaId == PERCENTAGES) {
									foreach($optionalSubjectsArray as $optionalSubjectRecord) {
										$mmSubjectId = $optionalSubjectRecord['subjectId'];
										$mmSubjectCode = $optionalSubjectRecord['subjectCode'];
										$attendanceMarksArray = $transferMarksManager->getOptSubStudentAttendancePercentageMarks($classId, $mmSubjectId, $attendanceSetId);
										if (!count($attendanceMarksArray)) {
											$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$mmSubjectCode;
										}
										else {
											foreach($attendanceMarksArray as $attendanceMarksRow) {
												$studentId = $attendanceMarksRow['studentId'];
												$studentsArray[$classId][$mmSubjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
																'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
																 'maxMarks' => $weightageAmount
														);
											}
										}
									}
								}
								elseif ($evaluationCriteriaId == SLABS) {
									foreach($optionalSubjectsArray as $optionalSubjectRecord) {
										$mmSubjectId = $optionalSubjectRecord['subjectId'];
										$mmSubjectCode = $optionalSubjectRecord['subjectCode'];
										$slabsArray = $transferMarksManager->getDistinctSlabsRequired($classId, $mmSubjectId);
										if (is_array($slabsArray) and $slabsArray[0]['lectureDelivered'] != '') {
											$requiredLectureDeliveredArray = $transferMarksManager->checkSlabsMade($attendanceSetId);
											$slabsMadeArray = explode(',', UtilityManager::makeCSList($requiredLectureDeliveredArray, 'lectureDelivered'));
											$allSlabsMade = true;
											foreach($slabsArray as $slabRecord) {
												$lectureDelivered = $slabRecord['lectureDelivered'];
												if ($lectureDelivered == 0) {
													continue;
												}
												if (!in_array($lectureDelivered, $slabsMadeArray)) {
													$inconsistenciesArray[$className]['attendanceSlabNotMade'][] =  ATTENDANCE_SLAB_NOT_MADE_FOR_LECTURES_DELIVERED_.$lectureDelivered._FOR_SUBJECT_.$mmSubjectCode;
													$allSlabsMade = false;
												}
											}
											if ($allSlabsMade == true) {
												$attendanceMarksArray = $transferMarksManager->getOptSubStudentAttendanceSlabsMarks($classId, $mmSubjectId, $attendanceSetId);
												foreach($attendanceMarksArray as $attendanceMarksRow) {
													$studentId = $attendanceMarksRow['studentId'];
													$studentsArray[$classId][$mmSubjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] =
														Array(
																	'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmount) * $weightageAmount,2),
																	 'maxMarks' => $weightageAmount
														);
												}
											}
										}
										else {
											$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$mmSubjectCode;
										}
									}
								}
							}
						}
						else {
							$transferMarksManager->setTransferProcessRunning(false);
							echo INVALID_OPTION;
							die;
						}
					}
				}
				else {
					# FOR TESTS
					if ($evaluationCriteriaId != 1 and $evaluationCriteriaId != 2 and $evaluationCriteriaId != 3) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo INVALID_DATA_FOR_TEST_TYPE_.$testTypeName._FOR_.$subjectCode;
						die;
					}
					if ($hasMarks == 1) {
						if ($optional == 0) {
							$testsTakenArray = $transferMarksManager->countClassTests($classId, $subjectId, $testTypeCategoryId);
							$testTakenCount = $testsTakenArray[0]['cnt'];
							if ($testTakenCount == 0) {
								$inconsistenciesArray[$className]['testNotEntered'][] = NO_TESTS_TAKEN_FOR_.$testTypeCategoryName._FOR_SUBJECT_.$subjectCode;
							}
							else {
								$missingStudentArray = $transferMarksManager->countMissingStudents($classId, $subjectId, $testTypeCategoryId);
								$missingStudent = $missingStudentArray[0]['cnt'];
								if ($missingStudent > 0) {
									$str = FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_.$testTypeCategoryName._FOR_SUBJECT_.$subjectCode;
									$getMissingStudentArray = $transferMarksManager->getMissingStudents($classId, $subjectId, $testTypeCategoryId);
									$str .= $transferMarksManager->getTable($getMissingStudentArray);
									$inconsistenciesArray[$className]['studentsMissing'][] = $str;
								}
								else {
									$marksArray = $transferMarksManager->getSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
									foreach($marksArray as $marksRow) {
										$studentId = $marksRow['studentId'];
										$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] =
										Array('marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 'maxMarks' => $weightageAmount);
									}
								}
							}
						}
						else if ($optional == 1 and $hasParentCategory = 0) {
							$subjectStudentsArray = $transferMarksManager->countOptionalSubjectStudents($classId, $subjectId);
							$subjectStudents = $subjectStudentsArray[0]['cnt'];
							if ($subjectStudents > 0) {
								$testsTakenArray = $transferMarksManager->countClassTests($classId, $subjectId, $testTypeCategoryId);
								$testTakenCount = $testsTakenArray[0]['cnt'];
								if ($testTakenCount == 0) {
									$inconsistenciesArray[$className]['testNotEntered'][] = NO_TESTS_TAKEN_FOR_.$testTypeCategoryName._FOR_SUBJECT_.$subjectCode;
								}
								else {
									$missingStudentArray = $transferMarksManager->countMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId);
									$missingStudent = $missingStudentArray[0]['cnt'];
									if ($missingStudent > 0) {
										$str = NO_TESTS_TAKEN_FOR_.$testTypeCategoryName._FOR_SUBJECT_.$subjectCode;
										$getMissingStudentArray = $transferMarksManager->getMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId);
										$str .= $transferMarksManager->getTable($getMissingStudentArray);
										$inconsistenciesArray[$className]['studentsMissing'][] = $str;
									}
									else {
										$marksArray = $transferMarksManager->getOptionalSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
										foreach($marksArray as $marksRow) {
											$studentId = $marksRow['studentId'];
											$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] =
											Array('marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 'maxMarks' => $weightageAmount);
										}
									}
								}
							}
						}
						else if ($optional == 1 and $hasParentCategory = 1) {
							$subjectStudentsArray = $transferMarksManager->countOptionalChildSubjectStudents($classId, $subjectId);
							$subjectStudents = $subjectStudentsArray[0]['cnt'];
							if ($subjectStudents > 0) {
								$optionalSubjectsArray = $transferMarksManager->getMMSubjects($classId, $subjectId);
								foreach($optionalSubjectsArray as $optionalSubjectRecord) {
									$mmSubjectId = $optionalSubjectRecord['subjectId'];
									$mmSubjectCode = $optionalSubjectRecord['subjectCode'];
									$testsTakenArray = $transferMarksManager->countClassTests($classId, $mmSubjectId, $testTypeCategoryId);
									$testTakenCount = $testsTakenArray[0]['cnt'];
									if ($testTakenCount == 0) {
										$inconsistenciesArray[$className]['testNotEntered'][] =  NO_TESTS_TAKEN_FOR_.$testTypeCategoryName._FOR_SUBJECT_.$mmSubjectCode;
									}
									else {
										$missingStudentArray = $transferMarksManager->countMissingOptionalSubjectStudents($classId, $mmSubjectId, $testTypeCategoryId);
										$missingStudent = $missingStudentArray[0]['cnt'];
										if ($missingStudent > 0) {
											$str = FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_."'$testTypeCategoryName' in '$mmSubjectCode'";
											$getMissingStudentArray = $transferMarksManager->getMissingOptionalSubjectStudents($classId, $mmSubjectId, $testTypeCategoryId);
											$str .=  getTable($getMissingStudentArray);
											$inconsistenciesArray[$className]['studentsMissing'][] =  $str;
										}
										else {
											$marksArray = $transferMarksManager->getOptionalSubjectTestTypeTestMarks($classId, $mmSubjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
											foreach($marksArray as $marksRow) {
												$studentId = $marksRow['studentId'];
												$studentsArray[$classId][$mmSubjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] =
													Array(
																'marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2),
																'maxMarks' => $weightageAmount
														);
											}
										}
									}
								}
							}
						}
						else {
							$transferMarksManager->setTransferProcessRunning(false);
							echo INVALID_OPTION;
							die;
						}
					}
				}
			}


			$transferMarksManager->getInconsistencyTable($inconsistenciesArray,$className, $errorTypeDescArray, $subjectCode);
			if ($errorFound == true) {
				continue;
			}

			$allStudentsArray = array();
			foreach($studentsArray as $classId => $subjectArray) {
				foreach($subjectArray as $subjectIdAA => $testArray) {
					foreach($testArray as $testTypeIdVal => $studentIdArray) {
						list($testTypeId,$evaluationCriteriaId,$cnt,$weightageAmount) = explode('#',$testTypeIdVal);
						if ($evaluationCriteriaId == 1) {
							//avg. of best
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalTests = count($marksRecord);
								$startCnt = 0;
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									if ($startCnt == $cnt) {
										break;
									}
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
									$startCnt++;
								}
								$actualMaxMarks = ($totalMaxMarks/$startCnt);
								$actualMarksScored = ($totalMarksScored/$startCnt);
								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
								$actualMaxMarks2 = $weightageAmount;

								$allStudentsArray[$studentId][$subjectIdAA][$testTypeId] = Array('classId'=>$classId, 'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 2) {
							//avg. or direct marks entry
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalTests = count($marksRecord);
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									//$per = $marksArray['per'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
								}

								$actualMaxMarks = ($totalMaxMarks / $totalTests);
								$actualMarksScored = ($totalMarksScored  / $totalTests);

								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
								$actualMaxMarks2 = $weightageAmount;
								//$actualMaxMarks = $totalMaxMarks * ($weightagePercentage / 100);
								//$actualMarksScored = round($totalMarksScored * ($weightagePercentage / 100),2);
								$allStudentsArray[$studentId][$subjectIdAA][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 3) {
							//best
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
									break; //we have to take best only
								}
								$actualMaxMarks = $totalMaxMarks;
								$actualMarksScored = $totalMarksScored;

								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
								$actualMaxMarks2 = $weightageAmount;


								$allStudentsArray[$studentId][$subjectIdAA][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 5 or $evaluationCriteriaId == 6) {
							//Percentage, Slabs, for attendance
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalTests = count($marksRecord);
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
								}

								$actualMaxMarks = ($totalMaxMarks / $totalTests);
								$actualMarksScored = ($totalMarksScored  / $totalTests);

								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
								$actualMaxMarks2 = $weightageAmount;

								$allStudentsArray[$studentId][$subjectIdAA][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
					}
				}
			}

			$tableCtr = 0;
			$queryPart = '';
			foreach($allStudentsArray as $studentId => $subjectArray) {
				foreach($subjectArray as $subjectIdBB => $testRecordArray) {
					foreach($testRecordArray as $testTypeId => $testDetailsArray) {
						$classId = $testDetailsArray['classId'];
						$actualMaxMarks = $testDetailsArray['actualMaxMarks'];
						$actualMarksScored = $testDetailsArray['actualMarksScored'];
						$rounding = $REQUEST_DATA['rounding_'.$subjectId];
						if ($rounding == 'ceilTestType') {
							$actualMarksScored = ceil($actualMarksScored);
						}
						elseif ($rounding == 'roundTestType') {
							$actualMarksScored = round($actualMarksScored);
						}
						if (!empty($queryPart)) {
							$queryPart .= ',';
						}
						$queryPart .= "($studentId,$testTypeId,$classId,$subjectIdBB,$actualMaxMarks,$actualMarksScored)";
						$tableCtr++;
						if ($tableCtr % 200 == 0) {
							if (!in_array($subjectIdBB, $testMarksSubjectArray)) {
								$testMarksSubjectArray[] = $subjectIdBB;
							}
							$returnStatus = $transferMarksManager->addTotalMarksInTransaction($queryPart);
							if ($returnStatus == false) {
								$transferMarksManager->setTransferProcessRunning(false);
								echo FAILURE;
								die;
							}
							$queryPart = '';
						}
					}
				}
			}
			if (!empty($queryPart)) {
				if (!in_array($subjectIdBB, $testMarksSubjectArray)) {
					$testMarksSubjectArray[] = $subjectIdBB;
				}
				$returnStatus = $transferMarksManager->addTotalMarksInTransaction($queryPart);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE;
					die;
				}
			}
			$queryPart = '';
			$tableCtr = 0;

			$optionalSubjectsArrayAA = $transferMarksManager->getMMSubjects($classId, $transferSubjectId);
			if ($optionalSubjectsArrayAA[0]['subjectId'] != '') {
				$transferSubjectId = UtilityManager::makeCSList($optionalSubjectsArrayAA, 'subjectId');
			}

			$studentMarksArray = $transferMarksManager->getStudentMarksSum($classId, $transferSubjectId);
			foreach($studentMarksArray as $studentRecord) {
				$studentId = $studentRecord['studentId'];
				$classId = $studentRecord['classId'];
				$subjectIdCC = $studentRecord['subjectId'];
				$maxMarks = $studentRecord['maxMarks'];
				$marksScored = $studentRecord['marksScored'];
				$conductingAuthority = $studentRecord['conductingAuthority'];

				if ($studentSubjectMarksArray[$classId][$subjectIdCC][$conductingAuthority][$studentId] != '') {
					continue;
				}
				$studentSubjectMarksArray[$classId][$subjectIdCC][$conductingAuthority][$studentId] = $marksScored;
				$rounding = $REQUEST_DATA['rounding_'.$subjectId];
				if ($rounding == 'ceilTotal') {
					$marksScored = ceil($marksScored);
				}
				elseif ($rounding == 'roundTotal') {
					$marksScored = round($marksScored);
				}


				$marksScoredStatus = 'Marks';


				if (!empty($queryPart)) {
					$queryPart .= ',';
				}
				$queryPart .= "($studentId,$classId,$subjectIdCC,$maxMarks,$marksScored,0,$conductingAuthority, '$marksScoredStatus')";
				$tableCtr++;
				if ($tableCtr % 200 == 0) {
					$returnStatus = $transferMarksManager->addGradingRecordInTransaction($queryPart);
					if ($returnStatus == false) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo FAILURE;
						die;
					}
					$queryPart = '';
				}
			}
			if (!empty($queryPart)) {
				$returnStatus = $transferMarksManager->addGradingRecordInTransaction($queryPart);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE;
					die;
				}
			}

			$transferMarksManager->getInconsistencyTable($inconsistenciesArray,$className, $errorTypeDescArray, $subjectCode);

			if ($errorFound == true) {
				continue;
			}

		}

		if ($errorFound == true) {
			echo $allReturnStr;
			die;
		}
		else {
			//update marksTransferred for that class
			$returnStatus = $transferMarksManager->updateRecordInTransaction('class', "marksTransferred = 1", "classId = $classId");
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE;
				die;
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			$transferMarksManager->storeTransferMarksManager($transferMarksManager);
			$subjectIdCodeArray = array();
			$subjectTransferredArray = $transferMarksManager->getTransferredSubjects($classId);
			foreach($subjectTransferredArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				$subjectCode = $subjectRecord['subjectCode'];
				$subjectIdCodeArray[] = $subjectId.'#'.$subjectCode;
			}
			echo json_encode(array(SUCCESS,$subjectIdCodeArray));
		}
		else {
			$transferMarksManager->setTransferProcessRunning(false);
			echo FAILURE;
		}
	}
	else {
		$transferMarksManager->setTransferProcessRunning(false);
		echo FAILURE;
	}

// for VSS
//$History: initTransferInternalMarks.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/04/10    Time: 11:57a
//Updated in $/LeapCC/Library/TransferMarksAdvanced
//applied missing check of data validation
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/28/09   Time: 6:44p
//Updated in $/LeapCC/Library/TransferMarksAdvanced
//added check in marks transfer, to stop process if class is frozen.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Library/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//











?>