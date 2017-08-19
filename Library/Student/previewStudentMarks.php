<?php
//This file creates Html Form output "listAssignRollNo" Module
//
// Author :Ajinder Singh
// Created on : 02-Mar-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
    define('MODULE','UpdateTotalMarks');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    
    $previewArray = array();

    $studentId = $REQUEST_DATA['studentId'];
    $classId = $REQUEST_DATA['classId'];
    $subjectId = $REQUEST_DATA['subjectId'];
   
    $studentAttendanceArray = $studentManager->getStudentSubjectAttendance($studentId,$subjectId,$classId);
    $totalLecturesDelivered = 0;
    $totalLecturesAttended = 0;

  
    $attendanceCodeArray = $commonQueryManager->getAttendanceCode();

    $attendanceCodePercentageArray = Array();
    foreach($attendanceCodeArray as $attendanceCodeRecord) {
        $attendanceCodeId = $attendanceCodeRecord['attendanceCodeId'];
        $attendanceCodePercentage = $attendanceCodeRecord['attendanceCodePercentage'];
        $attendanceCodePercentageArray[$attendanceCodeId] = $attendanceCodePercentage/100;
    }

    foreach($studentAttendanceArray as $studentAttendanceRecord) {
        $attendanceId = $studentAttendanceRecord['attendanceId'];
        $lectureDelivered = $studentAttendanceRecord['lectureDelivered'];
        $employeeId = $studentAttendanceRecord['employeeId'];
        $employeeName = $studentAttendanceRecord['employeeName'];
        $attendanceType = $studentAttendanceRecord['attendanceType'];
        $periodId = $studentAttendanceRecord['periodId'];
        $attendanceDate = $studentAttendanceRecord['attendanceDate'];
        $attendanceCode = $studentAttendanceRecord['attendanceCode'];
        
        if ($attendanceType == 'Daily') {
            $lectureAttended = $attendanceCodePercentageArray[$REQUEST_DATA['att_'.$attendanceId]];
        }
        else {
            $lectureAttended = $REQUEST_DATA['att_'.$attendanceId];
        }
        if (!is_numeric($lectureAttended)) {
            echo ENTER_VALID_LECTURES_ATTENDED_FOR_.$attendanceDate;
            die;
        }
        if ($lectureAttended > $lectureDelivered) {
            echo TOTAL_LECTURES_ATTENDED_MORE_THAN_TOTAL_LECTURES_DELIVERED;
            die;
        }
        $totalLecturesDelivered += $lectureDelivered;
        $totalLecturesAttended += $lectureAttended;
    }

    $attendanceMarks = 0;
    if ($totalLecturesDelivered > 0 and $totalLecturesAttended > 0) {
        if ($totalLecturesAttended > $totalLecturesDelivered) {
            echo TOTAL_LECTURES_ATTENDED_MORE_THAN_TOTAL_LECTURES_DELIVERED;
            die;
        }
        $percent = ceil(($totalLecturesAttended * 100)/$totalLecturesDelivered);
        $attendanceMarksArray = $studentManager->getAttendanceMarks($percent, $classId);
        $attendanceMarks = $attendanceMarksArray[0]['marksScored'];
    }

    $previewArray['attendanceMarks'] = $attendanceMarks;
    $attendanceTotalMarksArray = $studentManager->getAttendanceMaxMarks($classId,$subjectId);
    $previewArray['attendanceMarksTotal'] = $attendanceTotalMarksArray[0]['maxMarksScored'];

    $totalMarksScored = 0;
    $totalMaxMarks = 0;
    $studentTestMarksArray = $studentManager->getStudentSubjectTests($studentId,$subjectId,$classId);
    $studentTestTypeCategoryArray = array();
    //start transaction but not commit;
    $studentsArray = array();
  
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if (is_array($studentTestMarksArray[0])) {
            foreach($studentTestMarksArray as $studentTestMarksRecord) {
                $testId = $studentTestMarksRecord['testId'];
                $maxMarks = $studentTestMarksRecord['maxMarks'];
                $marksScored = $REQUEST_DATA['test_'.$testId];
                $testTypeCategoryId = $studentTestMarksRecord['testTypeCategoryId'];
                $testTypeCategoryName = $studentTestMarksRecord['testTypeCategoryName'];
                if (!in_array($testTypeCategoryId, $studentTestTypeCategoryArray)) {
                    $studentTestTypeCategoryArray[] = $testTypeCategoryId;
                }
                if (!is_numeric($marksScored)) {
                    echo ENTER_VALID_MARKS_SCORED_FOR_.$testTypeCategoryName;
                    die;
                }
                if ($marksScored > $maxMarks) {
                    echo MARKS_SCORED_MORE_THAN_TOTAL_MARKS;
                    die;
                }
                $return = $studentManager->updateMarksInTransaction($studentId,$subjectId,$classId,$testId,$marksScored);
                if ($return == false) {
                    echo ERROR_IN_CALCULATION_FOR_TEST_.$testTypeCategoryName;
                    die;
                }
                $totalMaxMarks += $maxMarks;
                $totalMarksScored += $marksScored;
            }
            if ($totalMarksScored > 0 and $totalMaxMarks > 0) {
                if ($totalMarksScored > $totalMaxMarks) {
                    echo TOTAL_MARKS_SCORED_MORE_THAN_TOTAL_MAX_MARKS;
                    die;
                }
            }
            if (!count($studentTestTypeCategoryArray) or !isset($studentTestTypeCategoryArray[0]) or empty($studentTestTypeCategoryArray[0])) {
                echo NO_CATEGORY_FOUND_FOR_TESTS;
                die;
            }
            $studentTestTypeCategoryList = implode(',', $studentTestTypeCategoryArray);
            $evCriteriaArray = $studentManager->getMarksDistributionOneSubjectCategory($classId, $subjectId, $studentTestTypeCategoryList);

            foreach($evCriteriaArray as $evRow) {
                $testTypeId = $evRow['testTypeId'];
                $testTypeName = $evRow['testTypeName'];
                $evaluationCriteriaId = $evRow['evaluationCriteriaId'];
                $cnt = $evRow['cnt'];
                $weightagePercentage = $evRow['weightagePercentage'];
                $weightageAmount = $evRow['weightageAmount'];
                $subjectTestTypeSum += $weightageAmount;
                $marksArray = $studentManager->getSubjectTestTypeCategoryTestMarks($classId, $subjectId, $testTypeId, " AND a.studentId = $studentId");
                foreach($marksArray as $marksRow) {
                    $studentId = $marksRow['studentId'];
                    $studentsArray[$classId][$subjectId][$testTypeId.'#'.$evaluationCriteriaId.'#'.$cnt.'#'.$weightageAmount][$studentId][] = Array(
                                            'maxMarks' => $marksRow['maxMarks'], 
                                            'marksScored' => $marksRow['marksScored']
                            );
                }
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
                                $actualMaxMarks = ($totalMaxMarks/$cnt);
                                $actualMarksScored = ($totalMarksScored/$cnt);
                                $actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
                                $actualMaxMarks2 = $weightageAmount;

                                $allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId, 'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
                            }
                        }
                        elseif ($evaluationCriteriaId == 2) {
                            //avg.
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

                                $actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
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

                                $actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
                                $actualMaxMarks2 = $weightageAmount;


                                $allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$actualMaxMarks2, 'actualMarksScored' => $actualMarksScored2);
                            }
                        }
                        elseif ($evaluationCriteriaId == 4) {
                            //direct marks entry, not to be done here
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
                                    //$per = $marksArray['per'];
                                    $totalMaxMarks += $maxMarks;
                                    $totalMarksScored += $marksScored;
                                }

                                $actualMaxMarks = ($totalMaxMarks / $totalTests);
                                $actualMarksScored = ($totalMarksScored  / $totalTests);

                                $actualMarksScored2 = round(($actualMarksScored / $actualMaxMarks) * $weightageAmount,3);
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
                        elseif ($evaluationCriteriaId == 9) {
                            //storeWithoutProcessing
                            foreach($studentIdArray as $studentId => $marksRecord) {
                                foreach($marksRecord as $marksArray) {
                                    $maxMarks = $marksArray['maxMarks'];
                                    $marksScored = $marksArray['marksScored'];
                                    $allStudentsArray[$studentId][$subjectId][$testTypeId] = Array('classId'=>$classId,  'actualMaxMarks'=>$maxMarks, 'actualMarksScored' => $marksScored);
                                }
                            }
                        }
                    }
                }
            }
        
            $totalMaxMarks = 0;
            $totalMarksScored = 0;
            foreach($allStudentsArray as $studentId => $subjectArray) {
                foreach($subjectArray as $subjectId => $testRecordArray) {
                    foreach($testRecordArray as $testTypeId => $testDetailsArray) {
                        $classId = $testDetailsArray['classId'];
                        $actualMaxMarks = $testDetailsArray['actualMaxMarks'];
                        $actualMarksScored = $testDetailsArray['actualMarksScored'];
                        $totalMaxMarks += $actualMaxMarks;
                        $totalMarksScored += $actualMarksScored;
                    }
                }
            }
        }

        $studentCompreTestMarksArray = $studentManager->getStudentTestCompreMarks($studentId,$subjectId,$classId);
        $compreMaxMarksTotal = 0;
        $compreMarksScoredTotal = 0;
        foreach($studentCompreTestMarksArray as $studentCompreTestMarksRecord) {
            $maxMarks = $studentCompreTestMarksRecord['maxMarks'];
            $testTypeId = $studentCompreTestMarksRecord['testTypeId'];
            $testTypeName = $studentCompreTestMarksRecord['testTypeName'];
            if (!isset($REQUEST_DATA['compreMarks_'.$testTypeId])) {
                echo COMPRE_TEST_.$testTypeName._NOT_FOUND;
                die;
            }
            $marksScored = $REQUEST_DATA['compreMarks_'.$testTypeId];
            if (!is_numeric($marksScored) or $marksScored < 0 or $marksScored > $maxMarks) {
                echo INVALID_MARKS_FOR_COMPRE_TEST_.$testTypeName;
                die;
            }
            $compreMaxMarksTotal += $maxMarks;
            $compreMarksScoredTotal += $marksScored;
        }

        if ($REQUEST_DATA['compreMarks'] > $REQUEST_DATA['compreMarksTotal']) {
            echo COMPRE_MARKS_SCORED_MORE_THAN_MAX_MARKS;
            die;
        }
        $previewArray['preCompreMarks'] = "$totalMarksScored";
        $previewArray['preCompreMarksTotal'] = "$totalMaxMarks";
        $previewArray['compreMarks'] = "$compreMarksScoredTotal";
        $previewArray['compreMarksTotal'] = "$compreMaxMarksTotal";
        $previewArray['grade'] = $REQUEST_DATA['grade'];

        echo json_encode($previewArray);

    }

?>