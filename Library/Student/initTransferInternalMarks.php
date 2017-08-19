<?php
//-------------------------------------------------------
//  This File contains php code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 18-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
	ini_set('memory_limit','200M');
//	$time_start = microtime(true);
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TransferInternalMarks');
	define('ACCESS','add');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	$classIds = $REQUEST_DATA['class1'];
	$labelId = $REQUEST_DATA['labelId'];

	$classIdArray = explode(',', $classIds);
	$rounding = $REQUEST_DATA['rounding'];

	$errorTypeDescArray = array('testNotEntered' => 'Errors related to Tests No Entered', 'studentsMissing' => 'Error related to Students Who have not given test', 'attendanceNotEntered' => 'Error related to Either attendance is not entered or slab is not made', 'subjectTotalNotMatching' => 'Error related to Subject Internal Marks and Test type sum not matching');

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();
		$inconsistenciesArray = array();
		$inconsistencyCounter = 0;
		foreach($classIdArray as $key => $classId) {
			//get subjects of class
			$returnStatus = $studentManager->deleteTotalTransferredMarks($classId);
			if ($returnStatus == false) {
				echo FAILURE;
				die;
			}
			$returnStatus = $studentManager->deleteTestTransferredMarks($classId);
			if ($returnStatus == false) {
				echo FAILURE;
				die;
			}

			$classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId = $classId");
			$className = $classNameArray[0]['className'];

			$subjectsArray = $studentManager->getClassSubjects($classId);
			$studentsArray = array();
			foreach($subjectsArray as $row) {
				$hasMarks = $row['hasMarks'];
				$hasAttendance = $row['hasAttendance'];
				$subjectId = $row['subjectId'];
				$optional = $row['optional'];
				$hasParentCategory = $row['hasParentCategory'];
				$offered = $row['offered'];
				if ($offered == 0) {
					continue;
				}
				if ($hasMarks == 0 and $hasAttendance == 0) {
					continue;
				}
				$subjectCodeArray = $studentManager->getSubjectCode($subjectId);
				$subjectCode = $subjectCodeArray[0]['subjectCode'];
				$subjectTypeId = $subjectCodeArray[0]['subjectTypeId'];
				if ($hasMarks) {
					if ($optional == 0) {
						$evCriteriaArray = $studentManager->getMarksDistributionOneSubject($classId, $subjectId);
						foreach($evCriteriaArray as $evRow) {
							$testTypeId = $evRow['testTypeId'];
							$testTypeName = $evRow['testTypeName'];
							$evaluationCriteriaId = $evRow['evaluationCriteriaId'];
							$cnt = $evRow['cnt'];
							$weightagePercentage = $evRow['weightagePercentage'];
							$weightageAmount = $evRow['weightageAmount'];
							$testTypeCategoryId = $evRow['testTypeCategoryId'];
							$testTypeCategoryName = $evRow['testTypeCategoryName'];
							$subjectTestTypeIndiSum[$subjectId . '#' . $subjectCode][] = array($testTypeName,$weightageAmount);
							if ($evaluationCriteriaId != 5 and $evaluationCriteriaId != 6) {
								if ($optional == 0) {
									$testsTakenArray = $studentManager->countClassTests($classId, $subjectId, $testTypeCategoryId);
									$testTakenCount = $testsTakenArray[0]['cnt'];
									if ($testTakenCount == 0) {
										$inconsistenciesArray[$classId]['testNotEntered'][] = NO_TESTS_TAKEN_FOR_."<b>$testTypeCategoryName</b> in <b>$subjectCode</b>";
										continue;
									}
									$missingStudentArray = $studentManager->countMissingStudents($classId, $subjectId, $testTypeCategoryId);
									$missingStudent = $missingStudentArray[0]['cnt'];
									if ($missingStudent > 0) {
										$str = FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_."<b>$testTypeCategoryName</b> in <b>$subjectCode</b>";
										$getMissingStudentArray = $studentManager->getMissingStudents($classId, $subjectId, $testTypeCategoryId);
										$str .= getTable($getMissingStudentArray);
										$inconsistenciesArray[$classId]['studentsMissing'][] = $str;
										continue;
									}
								}
							}
							$marksArray = $studentManager->getSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
							foreach($marksArray as $marksRow) {
								$studentId = $marksRow['studentId'];
								$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
														'marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 
														'maxMarks' => $weightageAmount
										);
							}
						}
					}
					elseif ($optional == 1 and $hasParentCategory == 0) {
						$subjectStudentsArray = $studentManager->countOptionalSubjectStudents($classId, $subjectId);
						$subjectStudents = $subjectStudentsArray[0]['cnt'];
						if ($subjectStudents > 0) {
							$evCriteriaArray = $studentManager->getMarksDistributionOneSubject($classId, $subjectId);
							foreach($evCriteriaArray as $evRow) {
								$testTypeId = $evRow['testTypeId'];
								$testTypeName = $evRow['testTypeName'];
								$evaluationCriteriaId = $evRow['evaluationCriteriaId'];
								$cnt = $evRow['cnt'];
								$weightagePercentage = $evRow['weightagePercentage'];
								$weightageAmount = $evRow['weightageAmount'];
								$testTypeCategoryId = $evRow['testTypeCategoryId'];
								$testTypeCategoryName = $evRow['testTypeCategoryName'];
								$subjectTestTypeIndiSum[$subjectId . '#' . $subjectCode][] = array($testTypeName,$weightageAmount);

								if ($evaluationCriteriaId != 5 and $evaluationCriteriaId != 6) {
									$subjectStudentsArray = $studentManager->countOptionalSubjectStudents($classId, $subjectId);
									$subjectStudents = $subjectStudentsArray[0]['cnt'];
									if ($subjectStudents > 0) {
										$testsTakenArray = $studentManager->countClassTests($classId, $subjectId, $testTypeCategoryId);
										$testTakenCount = $testsTakenArray[0]['cnt'];
										if ($testTakenCount == 0) {
											$inconsistenciesArray[$classId]['testNotEntered'][] =  NO_TESTS_TAKEN_FOR_."'$testTypeCategoryName' in '$subjectCode'";
											continue;
										}
										$missingStudentArray = $studentManager->countMissingOptionalSubjectStudents($classId, $subjectId, $testTypeCategoryId);
										$missingStudent = $missingStudentArray[0]['cnt'];
										if ($missingStudent > 0) {
											$str = FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_."'$testTypeCategoryName' in '$subjectCode'";
											$getMissingStudentArray = $studentManager->getMissingStudents($classId, $subjectId, $testTypeCategoryId);
											$str .= getTable($getMissingStudentArray);
											$inconsistenciesArray[$classId]['studentsMissing'][] =  $str;
											continue;
										}
									}
									$marksArray = $studentManager->getOptionalSubjectTestTypeTestMarks($classId, $subjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
									foreach($marksArray as $marksRow) {
										$studentId = $marksRow['studentId'];
										$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
															'marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 
															 'maxMarks' => $weightageAmount
												);
									}
								}
							}
						}
					}
					elseif ($optional == 1 and $hasParentCategory == 1) {
						$subjectStudentsArray = $studentManager->countOptionalChildSubjectStudents($classId, $subjectId);
						$subjectStudents = $subjectStudentsArray[0]['cnt'];
						if ($subjectStudents > 0) {
							$optionalSubjectsArray = $studentManager->getMMSubjects($classId, $subjectId);
							foreach($optionalSubjectsArray as $optionalSubjectRecord) {
								$mmSubjectId = $optionalSubjectRecord['subjectId'];
								$mmSubjectCode = $optionalSubjectRecord['subjectCode'];
								$evCriteriaArray = $studentManager->getMarksDistributionOneSubject($classId, $mmSubjectId);
								foreach($evCriteriaArray as $evRow) {
									$testTypeId = $evRow['testTypeId'];
									$testTypeName = $evRow['testTypeName'];
									$evaluationCriteriaId = $evRow['evaluationCriteriaId'];
									$cnt = $evRow['cnt'];
									$weightagePercentage = $evRow['weightagePercentage'];
									$weightageAmount = $evRow['weightageAmount'];
									$testTypeCategoryId = $evRow['testTypeCategoryId'];
									$testTypeCategoryName = $evRow['testTypeCategoryName'];
									$subjectTestTypeIndiSum[$subjectId . '#' . $mmSubjectCode][] = array($testTypeName,$weightageAmount);

									if ($evaluationCriteriaId != 5 and $evaluationCriteriaId != 6) {
										$testsTakenArray = $studentManager->countClassTests($classId, $mmSubjectId, $testTypeCategoryId);
										$testTakenCount = $testsTakenArray[0]['cnt'];
										if ($testTakenCount == 0) {
											$inconsistenciesArray[$classId]['testNotEntered'][] =  NO_TESTS_TAKEN_FOR_."'$testTypeCategoryName' in $mmSubjectId '$mmSubjectCode'";
											continue;
											//die;
										}
										$missingStudentArray = $studentManager->countMissingOptionalSubjectStudents($classId, $mmSubjectId, $testTypeCategoryId);
										$missingStudent = $missingStudentArray[0]['cnt'];
										if ($missingStudent > 0) {
											$str = FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_."'$testTypeCategoryName' in '$mmSubjectCode'";
											$getMissingStudentArray = $studentManager->getMissingStudents($classId, $mmSubjectId, $testTypeCategoryId);
											$str .=  getTable($getMissingStudentArray);
											$inconsistenciesArray[$classId]['studentsMissing'][] =  $str;
											continue;
										}
									}
									$marksArray = $studentManager->getOptionalSubjectTestTypeTestMarks($classId, $mmSubjectId, $testTypeId, ' AND c.isMemberOfClass = 1');
									foreach($marksArray as $marksRow) {
										$studentId = $marksRow['studentId'];
										$studentsArray[$classId][$mmSubjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
														'marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 
														'maxMarks' => $weightageAmount
												);
									}
								}
							}
						}
					}
				}
				if ($hasAttendance) {
					$attendanceTypeArray = $studentManager->getAttendanceTestType($classId, $subjectId);
					if (isset($attendanceTypeArray[0]['testTypeId']) and $attendanceTypeArray[0]['testTypeId'] != '') {
						$testTypeIdAtt = $attendanceTypeArray[0]['testTypeId'];
						$testTypeNameAtt = $attendanceTypeArray[0]['testTypeName'];
						$evaluationCriteriaIdAtt = $attendanceTypeArray[0]['evaluationCriteriaId'];
						$cntAtt = $attendanceTypeArray[0]['cnt'];
						$weightagePercentageAtt = $attendanceTypeArray[0]['weightagePercentage'];
						$weightageAmountAtt = $attendanceTypeArray[0]['weightageAmount'];
						

						
						if ($evaluationCriteriaIdAtt == 5) {	//PERCENTAGE
							if (($optional == 0) or ($optional == 1 and $hasParentCategory == 0)) {
								$subjectTestTypeIndiSum[$subjectId . '#' . $subjectCode][] = array($testTypeNameAtt,$weightageAmountAtt);
								$attendanceMarksArray = $studentManager->getStudentAttendancePercentageMarks($labelId, $classId, $subjectId, $subjectTypeId);
									if (!count($attendanceMarksArray)) {
										$inconsistenciesArray[$classId]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_."'$subjectCode'";
										continue;
										//die;
									}
								foreach($attendanceMarksArray as $attendanceMarksRow) {
									$studentId = $attendanceMarksRow['studentId'];
									$studentsArray[$classId][$subjectId][$testTypeIdAtt.'#'.$evaluationCriteriaIdAtt.'#'.$cntAtt.'#'.$weightageAmountAtt][$studentId][] = Array(
													'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmountAtt) * $weightageAmountAtt,2), 
													 'maxMarks' => $weightageAmountAtt
											);
								}
							}
							elseif ($optional == 1 and $hasParentCategory == 1) {
								$optionalSubjectsArray = $studentManager->getMMSubjects($classId, $subjectId);
								foreach($optionalSubjectsArray as $optionalSubjectRecord) {
									$mmSubjectId = $optionalSubjectRecord['subjectId'];
									$mmSubjectCode = $optionalSubjectRecord['subjectCode'];
									$subjectTestTypeIndiSum[$subjectId . '#' . $mmSubjectCode][] = array($testTypeNameAtt,$weightageAmountAtt);
									
									$attendanceMarksArray = $studentManager->getStudentAttendancePercentageMarks($labelId, $classId, $mmSubjectId, $subjectTypeId);
									if (!count($attendanceMarksArray)) {
										$inconsistenciesArray[$classId]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_."'$mmSubjectCode'";
										continue;
										//die;
									}
								
									foreach($attendanceMarksArray as $attendanceMarksRow) {
										$studentId = $attendanceMarksRow['studentId'];
										$studentsArray[$classId][$mmSubjectId][$testTypeIdAtt.'#'.$evaluationCriteriaIdAtt.'#'.$cntAtt.'#'.$weightageAmountAtt][$studentId][] = Array(
													'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmountAtt) * $weightageAmountAtt,2), 
													 'maxMarks' => $weightageAmountAtt
												);
									}
								}
							}
						}
						elseif ($evaluationCriteriaIdAtt == 6) {	//SLABS
							if (($optional == 0) or ($optional == 1 and $hasParentCategory == 0)) {
								$subjectTestTypeIndiSum[$subjectId . '#' . $subjectCode][] = array($testTypeNameAtt,$weightageAmountAtt);
								$attendanceMarksArray = $studentManager->getStudentAttendanceSlabsMarks($labelId, $classId, $subjectId, $subjectTypeId);
								if (!count($attendanceMarksArray)) {
									$inconsistenciesArray[$classId]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_."'$subjectCode'";
									continue;
									//die;
								}
								foreach($attendanceMarksArray as $attendanceMarksRow) {
									$studentId = $attendanceMarksRow['studentId'];
									$studentsArray[$classId][$subjectId][$testTypeIdAtt.'#'.$evaluationCriteriaIdAtt.'#'.$cntAtt.'#'.$weightageAmountAtt][$studentId][] = Array(
													'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmountAtt) * $weightageAmountAtt,2), 
													 'maxMarks' => $weightageAmountAtt
											);
								}
							}
							elseif ($optional == 1 and $hasParentCategory == 1) {
								$optionalSubjectsArray = $studentManager->getMMSubjects($classId, $subjectId);
								foreach($optionalSubjectsArray as $optionalSubjectRecord) {
									$mmSubjectId = $optionalSubjectRecord['subjectId'];
									$subjectTestTypeIndiSum[$subjectId . '#' . $mmSubjectCode][] = array($testTypeNameAtt,$weightageAmountAtt);
									if (!count($attendanceMarksArray)) {
										$inconsistenciesArray[$classId]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_."'$mmSubjectCode'";
										continue;
										//die;
									}
									$attendanceMarksArray = $studentManager->getStudentAttendanceSlabsMarks($labelId, $classId, $mmSubjectId, $subjectTypeId);
									foreach($attendanceMarksArray as $attendanceMarksRow) {
										$studentId = $attendanceMarksRow['studentId'];
										$studentsArray[$classId][$mmSubjectId][$testTypeIdAtt.'#'.$evaluationCriteriaIdAtt.'#'.$cntAtt.'#'.$weightageAmountAtt][$studentId][] = Array(
													'marksScored' => round(($attendanceMarksRow['marksScored'] / $weightageAmountAtt) * $weightageAmountAtt,2), 
													 'maxMarks' => $weightageAmountAtt
												);
									}
								}
							}
						}
					}
				}
			}
			if (count($subjectTestTypeIndiSum) > 0) {
				foreach($subjectTestTypeIndiSum as $subjectIdCode => $weightageArray) {
					list($subjectId, $subjectCode) = explode('#', $subjectIdCode);
					$internalTestMarksArray = $studentManager->getInternalTotalMarks($classId, $subjectId);
					$internalTestMarks = $internalTestMarksArray[0]['internalTotalMarks'];
					if (!$internalTestMarks) {
						$internalTestMarks = 0;
					}
					$totalWeightageSum = 0;
					$errorStr = '[';
					foreach($weightageArray as $weightageRecord) {
						$testType = $weightageRecord[0];
						$weightage = $weightageRecord[1];
						$totalWeightageSum += $weightage;
						if ($errorStr != '[') {
							$errorStr .= ', ';
						}
						$errorStr .= $testType.':'.$weightage;
					}
					$errorStr .= '] '; 
					$errorStr = '<b>'.$errorStr.'</b>';
					if ($internalTestMarks != $totalWeightageSum) {
						$inconsistenciesArray[$classId]['subjectTotalNotMatching'][] = SUBJECT_TOTAL_NOT_MATCH_FOR_."'$subjectCode' ".TEST_TYPE_SUM . $totalWeightageSum.' '.$errorStr.INTERNAL_MARKS_SUM.$internalTestMarks;
					}
				}
			}
			if (count($inconsistenciesArray) > 0) {
				continue;
			}
			$allStudentsArray = array();
			foreach($studentsArray as $classId => $subjectArray) {
				foreach($subjectArray as $subjectId => $testArray) {
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

								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId, 'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 2 or $evaluationCriteriaId == 4) {
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
								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
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
									//$per = $marksArray['per'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
									break; //we have to take best only
								}
								$actualMaxMarks = $totalMaxMarks;
								$actualMarksScored = $totalMarksScored;

								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
								$actualMaxMarks2 = $weightageAmount;


								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						/* DIRECT MARKS ENTRY, COVERED WITH [AVERAGE]
						elseif ($evaluationCriteriaId == 4) {
							//direct marks entry, not to be done here
						}
						*/
						elseif ($evaluationCriteriaId == 5 or $evaluationCriteriaId == 6) {
							//Percentage, Slabs, for attendance
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
								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 7) {
							//Sum
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									//$per = $marksArray['per'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
								}
								$actualMaxMarks = $totalMaxMarks;
								$actualMarksScored = $totalMarksScored;

								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
								$actualMaxMarks2 = $weightageAmount;


								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 8) {
							//sum. of best
							foreach($studentIdArray as $studentId => $marksRecord) {
								$startCnt = 0;
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
								foreach($marksRecord as $marksArray) {
									if ($startCnt == $cnt) {
										break;
									}
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									//$per = $marksArray['per'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
									$startCnt++;
								}
								$actualMaxMarks = $totalMaxMarks;
								$actualMarksScored = $totalMarksScored;
								$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
								$actualMaxMarks2 = $weightageAmount;
								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
					}
				}
			}
			$tableCtr = 0;
			$queryPart = '';
			foreach($allStudentsArray as $studentId => $subjectArray) {
				foreach($subjectArray as $subjectId => $testRecordArray) {
					foreach($testRecordArray as $testTypeId => $testDetailsArray) {
						$classId = $testDetailsArray['classId'];
						$actualMaxMarks = $testDetailsArray['actualMaxMarks'];
						$actualMarksScored = $testDetailsArray['actualMarksScored'];
						if ($rounding == 'ceilTestType') {
							$actualMarksScored = ceil($actualMarksScored);
						}
						elseif ($rounding == 'roundTestType') {
							$actualMarksScored = round($actualMarksScored);
						}
						if (!empty($queryPart)) {
							$queryPart .= ',';
						}
						$queryPart .= "($studentId,$testTypeId,$classId,$subjectId,$actualMaxMarks,$actualMarksScored)";
						$tableCtr++;
						if ($tableCtr % 2000 == 0) {
							$returnStatus = $studentManager->addTotalMarksInTransaction($queryPart);
							if ($returnStatus == false) {
								echo FAILURE;
								die;
							}
							$queryPart = '';
						}
					}
				}
			}
			if (!empty($queryPart)) {
				$returnStatus = $studentManager->addTotalMarksInTransaction($queryPart);
				if ($returnStatus == false) {
					echo FAILURE;
					die;
				}
			}
			$queryPart = '';
			$tableCtr = 0;
			$studentMarksArray = $studentManager->getStudentMarksSum($classId);
			foreach($studentMarksArray as $studentRecord) {
				$studentId = $studentRecord['studentId'];
				$classId = $studentRecord['classId'];
				$subjectId = $studentRecord['subjectId'];
				$maxMarks = $studentRecord['maxMarks'];
				$marksScored = $studentRecord['marksScored'];

				if ($rounding == 'ceilTotal') {
					$marksScored = ceil($marksScored);
				}
				elseif ($rounding == 'roundTotal') {
					$marksScored = round($marksScored);
				}

				
				$marksScoredStatus = 'Marks';
				if ($marksScored == -1) {
					$marksScored = 0;
					$marksScoredStatus = 'A';
				}
				elseif ($marksScored == -2) {
					$marksScored = 0;
					$marksScoredStatus = 'UMC';
				}
				elseif ($marksScored == -3) {
					$marksScored = 0;
					$marksScoredStatus = 'I';
				}
				elseif ($marksScored == -4) {
					$marksScored = 0;
					$marksScoredStatus = 'MU';
				}

				$conductingAuthority = $studentRecord['conductingAuthority'];
				if (!empty($queryPart)) {
					$queryPart .= ',';
				}
				$queryPart .= "($studentId,$classId,$subjectId,$maxMarks,$marksScored,0,$conductingAuthority, '$marksScoredStatus')";
				$tableCtr++;
				if ($tableCtr % 2000 == 0) {
					$returnStatus = $studentManager->addGradingRecordInTransaction($queryPart);
					if ($returnStatus == false) {
						echo FAILURE;
						die;
					}
					$queryPart = '';
				}
			}
			if (!empty($queryPart)) {
				$returnStatus = $studentManager->addGradingRecordInTransaction($queryPart);
				if ($returnStatus == false) {
					echo FAILURE;
					die;
				}
			}
			//update marksTransferred for that class
			$returnStatus = $studentManager->updateRecordInTransaction('class', "marksTransferred = 1", "classId = $classId");
			if ($returnStatus == false) {
				echo FAILURE;
				die;
			}
		}
		$returnStr = '';
		if (count($inconsistenciesArray) > 0) {
			//$classNameArray = $studentManager->getSingleField('class', 'className', " where classId = $classId");
			//$className = $classNameArray[0]['className'];
			$returnStr .= "
						
						<table width='100%' border='0' cellspacing='5' cellpadding='0' class='contenttab_border2'>
							<tr>
								<td valign='top' colspan='1' class='contenttab_row1'>
									<table border='0' cellspacing='10' cellpadding='0' width='100%'>
										<tr><td><u><b>Marks have not been transferred because of following issues:</b></u></td></tr>
							";
							foreach($inconsistenciesArray as $className => $errorArray) {
								$returnStr .= 
									"
										<tr>
											<td valign='top' colspan='1' class='lightGrey'><u><b>Following Issues were found during transferring marks for <b>$className</b> : </b></u>";
								
								
								foreach($errorArray as $errorType => $errorTypeArray) {
									$returnStr .= "<br><br><b><u>".$errorTypeDescArray[$errorType].":</u></b>";
									$i = 0;
									foreach($errorTypeArray as $inconsistency) {
										$i++;
										$returnStr .= "<br> &bull;&nbsp;&nbsp;".$inconsistency;
									}
								}
								
								$returnStr .= "<br></td></tr>";
			}
				$returnStr .= "	</table></td></tr></table>";

			$fp = fopen(TEMPLATES_PATH . '/Xml/marksTransferIssues.doc','wb');
			if ($fp) {
				fwrite($fp, $returnStr);
			}
			fclose($fp);

			
			echo $returnStr;
			die;
		}

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
		}
		else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
	}


function getTable($getMissingStudentArray) {
	$str = '<table border="1" style="border-collapse:collapse" width="96%" align="center">';
	$str .= '<tr><th>Sr. No</th><th>Roll No</th><th>Student Name</th></tr>';
	$i = 0;

	foreach($getMissingStudentArray as $record) {
		$str .= '<td>'.++$i.'<td>'.$record['rollNo'].'</td><td>'.$record['studentName'].'</td></tr>';
	}
	$str .= '</table>';
	return $str;
}

//$History: initTransferInternalMarks.php $
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 12/09/09   Time: 12:47p
//Updated in $/LeapCC/Library/Student
//changed direct marks entry code to merge with average
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 12/06/09   Time: 2:36p
//Updated in $/LeapCC/Library/Student
//done changes in files for marks transfer
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/02/09   Time: 12:08p
//Updated in $/LeapCC/Library/Student
//added patch missed earlier by mistake
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Library/Student
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/20/09   Time: 5:32p
//Updated in $/LeapCC/Library/Student
//removed code written for debugging process.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/17/09   Time: 6:53p
//Updated in $/LeapCC/Library/Student
//done changes for marks transfer
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:23a
//Updated in $/LeapCC/Library/Student
//fixed bug no.1203
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:20p
//Updated in $/LeapCC/Library/Student
//code updation for bug fixing found during self testing.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 4/22/09    Time: 6:26p
//Updated in $/LeapCC/Library/Student
//done changes as per flow.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:48p
//Created in $/LeapCC/Library/Student
//file added for transfer of internal marks
//



?>