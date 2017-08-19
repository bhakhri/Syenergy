<?php
//-------------------------------------------------------
//  This File contains code for transferring marks
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0);
	ini_set('memory_limit','200M');
    //$time_start = microtime(true);
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    define('MODULE','TransferInternalMarksAdvanced');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	global $sessionHandler;
	$queryDescription =''; 
	
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	
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
	$consolidated=1;
	
	$higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
   

	$testMarksSubjectArray = array();

	$studentSubjectMarksArray = array();

	$userSelectedRoundingArray = array();

	$transferMarksManager->validateTimeTableClass($labelId, $classId);
	$transferMarksManager->checkTimeTableClass($labelId, $classId);

	$classFrozenArray = $commonQueryManager->checkFrozenClass($classId);
	$isFrozen = $classFrozenArray[0]['isFrozen'];

	$classNameArray = $transferMarksManager->getSingleField('class', 'className', "WHERE classId = $classId");
	$className = $classNameArray[0]['className'];

	if ($isFrozen == 1) {
		$transferMarksManager->setTransferProcessRunning(false);
		echo '<br><b><u>'.FATAL_ERROR_OCCURED.'</u><br><br>1. '.CLASS_FROZEN_RESTRICTION.'</b>';
		die;
	}

	$transferMarksRoundingArray = Array("ceilTotal","ceilTestType", "roundTotal", "roundTestType", "noRound");

	$errorTypeDescArray = array('fatalErrors' => 'Fatal Errors', 'testNotEntered' => 'Errors related to Tests Not Entered', 'studentsMissing' => 'Error related to Students Who have not given test', 'attendanceNotEntered' => 'Error related to attendance is not entered', 'subjectTotalNotMatching' => 'Error related to Subject Internal Marks and Test type sum not matching', 'attendanceSlabNotMade' => 'Error related to Attendance Slab not made', 'dutyLeave' => 'Duty Leave unresolved', 'dutyLeaveConflicted' => 'Duty Leave Conflicted','medicalLeave' => 'Medical Leave unresolved', 'medicalLeaveConflicted' => 'Medical Leave Conflicted', 'dutyMedicalLeaveConflicted' => 'Duty and Medical Leave Conflicted');

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

		$transferSubjectList = UtilityManager::makeCSList($transferSubjectDetailsArray, 'subjectId');

		foreach($transferSubjectDetailsArray as $subjectRecord) {

			$returnStr = '';
			$inconsistenciesArray = array();
			$inconsistencyCounter = 0;
			$studentsArray = array();
			$subjectId = $subjectRecord['subjectId'];
			$subjectCode = $subjectRecord['subjectCode'];
			$transferSubjectId = $subjectRecord['subjectId']; #SAVE A SPARE COPY OF SUBJECT ID
            
            $tempAttendanceTestTypeSum = $subjectRecord['attendanceTestTypeSum'];
            

			$returnStatus = $transferMarksManager->deleteTotalTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE." for subject: $subjectCode";
				die;
			}
			//$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$returnStatus = $transferMarksManager->deleteTestTransferredMarks($classId, $subjectId);
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE." for subject: $subjectCode";
				die;
			}
			//$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			
            
            // Note:  Atendance weightage is not defined then duty leave and medical leave not be checked
            if($tempAttendanceTestTypeSum>0) {
			    //check unresolved duty leave
			    $dutyLeaveCountArray = $transferMarksManager->countUnresolvedDutyLeave($classId, $subjectId);
			    $cnt = $dutyLeaveCountArray[0]['cnt'];
			    if ($cnt > 0) {
				    $dutyLeaveUnresolvedArray = $transferMarksManager->getUnresolvedDutyLeave($classId, $subjectId);
				    $str = FOLLOWING_DUTY_LEAVE_UNRESOLVED_FOR_SUBJECT_.$subjectCode;
				    $str .= $transferMarksManager->getDutyLeaveTable($dutyLeaveUnresolvedArray);
				    $inconsistenciesArray[$className]['dutyLeave'][] = $str;
			    }
			    
			    //check unresolved medical leave
			    $medicalLeaveCountArray = $transferMarksManager->countUnresolvedMedicalLeave($classId, $subjectId);
			    $cnt = $medicalLeaveCountArray[0]['cnt'];
			    if ($cnt > 0) {
				    $medicalLeaveUnresolvedArray = $transferMarksManager->getUnresolvedMedicalLeave($classId, $subjectId);
				    $str = FOLLOWING_MEDICAL_LEAVE_UNRESOLVED_FOR_SUBJECT_.$subjectCode;
				    $str .= $transferMarksManager->getMedicalLeaveTable($medicalLeaveUnresolvedArray);
				    $inconsistenciesArray[$className]['medicalLeave'][] = $str;
			    }

			    //check conflicting duty leave with attendance
			    $conflictedDutyLeaveCountArray = $transferMarksManager->countConflictedDutyLeave($classId, $subjectId);
			    $cnt = $conflictedDutyLeaveCountArray[0]['cnt'];
			    if ($cnt > 0) {
				    $dutyLeaveConflictedArray = $transferMarksManager->getConflictedDutyLeave($classId, $subjectId);
				    $str = FOLLOWING_DUTY_LEAVE_CONFLICTED_FOR_SUBJECT_.$subjectCode;
				    $str .= $transferMarksManager->getDutyLeaveTable($dutyLeaveConflictedArray);
				    $inconsistenciesArray[$className]['dutyLeaveConflicted'][] = $str;
			    }

			    //check conflicting medical leave with attendance
			    $conflictedMedicalLeaveCountArray = $transferMarksManager->countConflictedMedicalLeave($classId, $subjectId);
			    $cnt = $conflictedMedicalLeaveCountArray[0]['cnt'];
			    if ($cnt > 0) {
				    $medicalLeaveConflictedArray = $transferMarksManager->getConflictedMedicalLeave($classId, $subjectId);
				    $str = FOLLOWING_MEDICAL_LEAVE_CONFLICTED_FOR_SUBJECT_.$subjectCode;
				    $str .= $transferMarksManager->getMedicalLeaveTable($medicalLeaveConflictedArray);
				    $inconsistenciesArray[$className]['medicalLeaveConflicted'][] = $str;
			    }

			    //check for conflicts between duty  and medical leaves
			    $conflictedDutyMedicalLeaveCountArray = $transferMarksManager->countConflictedDutyMedicalLeave($classId, $subjectId);
			    $cnt = $conflictedDutyMedicalLeaveCountArray[0]['cnt'];
			    if ($cnt > 0) {
				    $dutyMedicalLeaveConflictedArray = $transferMarksManager->getConflictedDutyMedicalLeave($classId, $subjectId);
				    $str = FOLLOWING_DUTY_AND_MEDICAL_LEAVE_CONFLICTED_FOR_SUBJECT_.$subjectCode;
				    $str .= $transferMarksManager->getMedicalLeaveTable($dutyMedicalLeaveConflictedArray);
				    $inconsistenciesArray[$className]['dutyMedicalLeaveConflicted'][] = $str;
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
                  $msg =INTERNAL_MARKS_SUM_DOES_NOT_MATCH_WITH_TEST_TYPE_SUM_FOR_SUBJECT_.$subjectCode;
                  $inconsistenciesArray[$className]['fatalErrors'][] = $msg;
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
						if ($evaluationCriteriaId == PERCENTAGES) {

                            $per = array();       
                            $studentAttendanceArray = array();   
                            
							# GET ATTENDANCE PERCENT DETAILS                    
                            $attendancePercentArray = $transferMarksManager->getAttendanceSetPercentDetails($attendanceSetId);
					        $attendancePercentMarksArray = array();
                            foreach($attendancePercentArray as $record) {
								$percentFrom = $record['percentFrom'];
								$percentTo = $record['percentTo'];
								$marksScored = $record['marksScored'];
								while ($percentFrom <= $percentTo) {
									$attendancePercentMarksArray[$percentFrom] = $marksScored;
									$percentFrom++;
								}
							}
                        
                            # GET STUDENT ATTENDANCE
						    $attCondition = " AND att.classId ='$classId' AND att.subjectId ='$subjectId' "; 
                            $attendanceDetailsArray = $commonQueryManager->getFinalAttendance($attCondition,'','1','','',$classId,$subjectId);
                            if(!count($attendanceDetailsArray)) {
                              $inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
                              continue;
                            }
                         
                            foreach($attendanceDetailsArray as $record) {
                                $medStudentId = $record['studentId'];
                                $studentPercent = $record['percentage']; 
                                
                                $studentAttendanceMarksScored = $attendancePercentMarksArray[$studentPercent];
                                
    $studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$medStudentId][]
								=Array(
										'marksScored' => round(($studentAttendanceMarksScored / $weightageAmount) * $weightageAmount,2),
										'maxMarks' => $weightageAmount
							     );
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
									# GET ATTENDANCE SLAB DETAILS
									$attendanceSlabArray = $transferMarksManager->getAttendanceSetSlabDetails($attendanceSetId);
									$attendanceSlabMarksArray = array();

									foreach($attendanceSlabArray as $record) {
										$attendanceSlabMarksArray[$record['lectureDelivered']][$record['lectureAttended']] = $record['marksScored'];
									}

									# GET STUDENT ATTENDANCE
                                    $studentAttendanceArray = array(); 
                                    $attCondition = " AND att.classId ='$classId' AND att.subjectId ='$subjectId' "; 
                                    $attendanceDetailsArray = $commonQueryManager->getFinalAttendance($attCondition,'','1','','',$classId,$subjectId);
                                    if(!count($attendanceDetailsArray)) {
                                      $inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
                                      continue;
                                    }
									foreach($attendanceDetailsArray as $record) {
                                        $attendedCnt = $record['lectureAttended']+$record['dutyLeave']+$record['medicalLeave'];
										$studentAttendanceArray[$record['studentId']]['lectureDelivered'] = $record['lectureDelivered'];
										$studentAttendanceArray[$record['studentId']]['lectureAttended'] = $attendedCnt;
									
                                        
                                    //$attendanceMarksArray = $transferMarksManager->getStudentAttendanceSlabsMarks($classId, $subjectId, $attendanceSetId);                  
                                    //foreach($attendanceDetailsArray as $attendanceMarksRow) {
										$studentId = $record['studentId'];
										$lectureDelivered = $studentAttendanceArray[$studentId]['lectureDelivered'];
										$lectureAttended = intval($studentAttendanceArray[$studentId]['lectureAttended']);
                                    
                                        if($lectureDelivered=='') {
                                          $lectureDelivered='0';
                                        }
                                        
                                        if($lectureAttended=='') {
                                          $lectureAttended='0';
                                        }
                                    
										$studentAttendanceMarksScored = $attendanceSlabMarksArray[$lectureDelivered][$lectureAttended];
										$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
														'marksScored' => round(($studentAttendanceMarksScored / $weightageAmount) * $weightageAmount,2),
														 'maxMarks' => $weightageAmount
												);
									//}
                                   }
								}
							}
							else {
								$inconsistenciesArray[$className]['attendanceNotEntered'][] =  ATTENDANCE_NOT_ENTERED_IN_SUBJECT_.$subjectCode;
							}
						}
					}
				}
				else {
					# FOR TESTS
					if ($evaluationCriteriaId != 1 and $evaluationCriteriaId != 2 and $evaluationCriteriaId != 3 and $evaluationCriteriaId != 7 and $evaluationCriteriaId != 8 ) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo INVALID_DATA_FOR_TEST_TYPE_.$testTypeName._FOR_.$subjectCode;
						die;
					}
					if ($hasMarks == 1) {
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
                                    
                                   // $mks = ($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount;
         							$studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] =                                   
                                    Array('marksScored' => $marksRow['marksScored'], 
                                          'maxMarks' => $marksRow['maxMarks']);
								
                                //Array('marksScored' => round(($marksRow['marksScored'] / $marksRow['maxMarks']) * $weightageAmount,2), 
                                //          'maxMarks' => $weightageAmount);
                                }
							}
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
				foreach($subjectArray as $subjectId => $testArray) {
					foreach($testArray as $testTypeIdVal => $studentIdArray) {
						list($testTypeId,$evaluationCriteriaId,$cnt,$weightageAmount) = explode('#',$testTypeIdVal);
						if ($evaluationCriteriaId == 1) {
							//Weighted Average of Best n
							foreach($studentIdArray as $studentId => $marksRecord) {
								$totalTests = count($marksRecord);
								$startCnt = 0;
								$totalMaxMarks = 0;
								$totalMarksScored = 0;
                                $ttMksScored = 0;
                                $ttMaxMarks = 0; 
                                
                             	foreach($marksRecord as $marksArray) {
									if ($startCnt == $cnt) {
										break;
									}
									$maxMarks = $marksArray['maxMarks'];
									$marksScored = $marksArray['marksScored'];
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
                                    
                                    $ttMksScored += ($marksScored/$maxMarks) * $weightageAmount;
                                    $ttMaxMarks =  $weightageAmount; 
									$startCnt++;
								}
                                /*
                                $actualMaxMarks = ($ttMaxMarks/$startCnt);
                                $actualMarksScored = ($ttMksScored/$startCnt);
                                */
                                $actualMaxMarks = $ttMaxMarks;
                                $actualMarksScored = ($ttMksScored/$startCnt);
                                $actualMarksScored2 = $actualMarksScored;
                                $actualMaxMarks2 = $actualMaxMarks;

								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId, 'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
						elseif ($evaluationCriteriaId == 2) {
                           
                            //Weighted Average
                            foreach($studentIdArray as $studentId => $marksRecord) {
                                $totalTests = count($marksRecord);
                                $totalMaxMarks = 0;
                                $totalMarksScored = 0;
                                $ttMksScored = 0;
                                $ttMaxMarks = 0;     
                                foreach($marksRecord as $marksArray) {
                                   $maxMarks = $marksArray['maxMarks'];
                                   $marksScored = $marksArray['marksScored'];
                                   $ttMksScored += ($marksScored/$maxMarks) * $weightageAmount;
                                   $ttMaxMarks =  $weightageAmount; 
                                }  
                                if($totalTests>0) {
                                  $actualMarksScored2 = $ttMksScored/$totalTests;
                                  $actualMaxMarks2 = $ttMaxMarks;
                                }
                                else {
                                  $actualMarksScored2=0;
                                  $actualMaxMarks2=0;  
                                }
                                
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
									$totalMaxMarks += $maxMarks;
									$totalMarksScored += $marksScored;
									break; //we have to take best only
								}
								$actualMaxMarks = $totalMaxMarks;
								$actualMarksScored = $totalMarksScored;

								//$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
                                $actualMarksScored2 = ($actualMarksScored / $actualMaxMarks) * $weightageAmount;
								$actualMaxMarks2 = $weightageAmount;


								$allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
							}
						}
                        elseif ($evaluationCriteriaId == 7) {
                            //Simple Average
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

                                //$actualMaxMarks = ($totalMaxMarks / $totalTests);
                                //$actualMarksScored = ($totalMarksScored  / $totalTests);
                                $actualMaxMarks = $totalMaxMarks;
                                $actualMarksScored = $totalMarksScored;

                                //$actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,1);
                                $actualMarksScored2 = ($actualMarksScored / $actualMaxMarks) * $weightageAmount;
                                $actualMaxMarks2 = $weightageAmount;
                                //$actualMaxMarks = $totalMaxMarks * ($weightagePercentage / 100);
                                //$actualMarksScored = round($totalMarksScored * ($weightagePercentage / 100),2);
                                $allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
                            }
                        }
                        else if ($evaluationCriteriaId == 8) {
                            //Simple Average of Best n
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
                                $actualMarksScored2 = ($actualMarksScored / $actualMaxMarks) * $weightageAmount;
                                
                                $actualMaxMarks2 = $weightageAmount;

                                $allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId, 'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
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

                                $mks = ($actualMarksScored / $actualMaxMarks) * $weightageAmount;   
                              
								$actualMarksScored2 = $mks;
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
						$queryPart .= "($studentId,$testTypeId,$classId,$subjectId,$actualMaxMarks,$actualMarksScored)";
						$tableCtr++;
						if ($tableCtr % 200 == 0) {
							if (!in_array($subjectId, $testMarksSubjectArray)) {
								$testMarksSubjectArray[] = $subjectId;
							}
							$returnStatus = $transferMarksManager->addTotalMarksInTransaction($queryPart);
							if ($returnStatus == false) {
								$transferMarksManager->setTransferProcessRunning(false);
								echo FAILURE." for subject: $subjectCode";
								die;
							}
                            //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
							$queryPart = '';
						}
					}
				}
			}
			if (!empty($queryPart)) {
				if (!in_array($subjectId, $testMarksSubjectArray)) {
					$testMarksSubjectArray[] = $subjectId;
				}
				$returnStatus = $transferMarksManager->addTotalMarksInTransaction($queryPart);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE." for subject: $subjectCode";
					die;
				}
                //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			}
			$queryPart = '';
			$tableCtr = 0;


			$studentMarksArray = $transferMarksManager->getStudentMarksSum($classId, $subjectId);
			foreach($studentMarksArray as $studentRecord) {
				$studentId = $studentRecord['studentId'];
				$classId = $studentRecord['classId'];
				$subjectId = $studentRecord['subjectId'];
				$maxMarks = $studentRecord['maxMarks'];
				$marksScored = $studentRecord['marksScored'];
				$conductingAuthority = $studentRecord['conductingAuthority'];

				if ($studentSubjectMarksArray[$classId][$subjectId][$conductingAuthority][$studentId] != '') {
					continue;
				}
				$studentSubjectMarksArray[$classId][$subjectId][$conductingAuthority][$studentId] = $marksScored;
				$rounding = $REQUEST_DATA['rounding_'.$subjectId];
				$userSelectedRoundingArray[$subjectId] = $roundingArray[$rounding];
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
				$queryPart .= "($studentId,$classId,$subjectId,$maxMarks,$marksScored,0,$conductingAuthority, '$marksScoredStatus')";
				$tableCtr++;
				if ($tableCtr % 200 == 0) {
					$returnStatus = $transferMarksManager->addGradingRecordInTransaction($queryPart);
					if ($returnStatus == false) {
						$transferMarksManager->setTransferProcessRunning(false);
						echo FAILURE." for subject: $subjectCode";
						die;
					}
                    //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
					$queryPart = '';
				}
			}
			if (!empty($queryPart)) {
				$returnStatus = $transferMarksManager->addGradingRecordInTransaction($queryPart);
				if ($returnStatus == false) {
					$transferMarksManager->setTransferProcessRunning(false);
					echo FAILURE." for subject: $subjectCode";
					die;
				}
                //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
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
				echo FAILURE." for subject: $subjectCode";
				die;
			}
            //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		}
		foreach($userSelectedRoundingArray as $subjectId => $rounding) {
			#UPDATE RECORD IN SUBJECT_TO_CLASS AND OPTIONAL_SUBJECT_TO_CLASS, BECAUSE AT A TIME, ONLY ONE PLACE WILL BE UPDATED
			$returnStatus = $transferMarksManager->updateRecordInTransaction('subject_to_class', "rounding = $rounding", "classId = $classId AND subjectId = $subjectId");
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE." while updating rounding subject: $subjectCode";
				die;
			}
            //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
			$returnStatus = $transferMarksManager->updateRecordInTransaction('optional_subject_to_class', "rounding = $rounding", "classId = $classId AND subjectId = $subjectId");
			if ($returnStatus == false) {
				$transferMarksManager->setTransferProcessRunning(false);
				echo FAILURE." while updating rounding subject: $subjectCode";
				die;
			}
            //$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
		}
		

		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$auditTrialDescription = "Marks have been transferred for class: $className subject(s): ";
		$type =MARKS_ARE_TRANSFERRED; //MARKS TRANSFERRED
		$subjectsArray = $transferMarksManager->getSingleField('subject', 'subjectCode', " where subjectId IN ($transferSubjects )");
		$subjectList = UtilityManager::makeCSList($subjectsArray, 'subjectCode');
		$auditTrialDescription .= $subjectList;
        $queryDescription='';
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
			$transferMarksManager->storeTransferMarksManager($transferMarksManager);
			$subjectIdCodeArray = array();
			$subjectTransferredArray = $transferMarksManager->getTransferredSubjects($classId, $transferSubjects);
			foreach($subjectTransferredArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				$subjectCode = $subjectRecord['subjectCode'];
				$subjectIdCodeArray[] = $subjectId.'#'.$subjectCode;
			}
			echo json_encode(array(SUCCESS,$subjectIdCodeArray));

		}
		else {
			$transferMarksManager->setTransferProcessRunning(false);
			echo FAILURE." for subject: $subjectCode";
		}
	}
	else {
		$transferMarksManager->setTransferProcessRunning(false);
		echo FAILURE." for subject: $subjectCode";
	}
?>
