<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UpdateTotalMarks');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
   
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

   
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    global $sessionHandler;
        
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
 
    $previewArray = array();
    $studentId = $REQUEST_DATA['studentId'];
    $classId = $REQUEST_DATA['classId'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $preCompre = $REQUEST_DATA['preCompre']; //for Marks,A,UMC
    $preCompreMarks = $REQUEST_DATA['preCompreMarksNew'];
    $preCompreMarksTotal = $REQUEST_DATA['preCompreMarksTotal'];
    $compre = $REQUEST_DATA['compre'];//for Marks,A,UMC
    $compreMarks = $REQUEST_DATA['compreMarksNewCal'];
    $compreMarksTotal = $REQUEST_DATA['compreMarksTotalCal'];
    $gradeId = $REQUEST_DATA['grade'];
    $reason = add_slashes($REQUEST_DATA['reason']);

    
    


    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        
        $studentMarksArray = $studentManager->getStudentOldCGPA($studentId,$classId);
        $oldCGPAExists = false;
        $studentsArray = array();

        foreach($studentMarksArray as $record) {
            $totalGradeIntoPoints = $record['gradeIntoCredits'];
            $totalCredits = $record['credits'];
            $oldCGPAExists = true;
        }
        $studentManager->addCgpaLog($studentId,$classId,$totalGradeIntoPoints, $totalCredits,$reason);

        $oldMarksArray = $studentManager->getStudentOldMarks($studentId,$classId,$subjectId);
        foreach($oldMarksArray as $oldRecord) {
            $oldMarks = $oldRecord['marksScored'];
            $conductingAuthority = $oldRecord['conductingAuthority'];
            $oldGradeId = add_slashes($oldRecord['gradeId']);
            $returnStatus = $studentManager->addMarksUpdationLog($studentId,$classId,$subjectId, $oldMarks, $conductingAuthority,$oldGradeId,$reason, $instituteId);
            if ($returnStatus == false) {
                echo FAILURE;
                exit;
            }
        }



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

        //attendance
        foreach($studentAttendanceArray as $studentAttendanceRecord) {
            $attendanceId = $studentAttendanceRecord['attendanceId'];
            $lectureDelivered = $studentAttendanceRecord['lectureDelivered'];
            $employeeId = $studentAttendanceRecord['employeeId'];
            $employeeName = $studentAttendanceRecord['employeeName'];
            $attendanceType = $studentAttendanceRecord['attendanceType'];
            $periodId = $studentAttendanceRecord['periodId'];
            $attendanceDate = $studentAttendanceRecord['attendanceDate'];
            $attendanceCode = $studentAttendanceRecord['attendanceCode'];
            $attendanceCodeId = $studentAttendanceRecord['attendanceCodeId'];

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

            if ($attendanceType == 'Daily') {
                $returnStatus = $studentManager->updateStudentDailyAttendance($attendanceId,$REQUEST_DATA['att_'.$attendanceId]);
            }
            else {
                $returnStatus = $studentManager->updateStudentAttendance($attendanceId,$lectureAttended);
            }


            if ($returnStatus == false) {
                echo ERROR_WHILE_UPDATING_ATTENDANCE;
                die;
            }

            $totalLecturesDelivered += $lectureDelivered;
            $totalLecturesAttended += $lectureAttended;
        }


        $percent = 0;
        $attendanceMarks = 0;
        if ($totalLecturesDelivered > 0) {
            $percent = ceil(($totalLecturesAttended * 100)/$totalLecturesDelivered);
            $attendanceMarksArray = $studentManager->getAttendanceMarks($percent, $classId);
            $attendanceMarks = $attendanceMarksArray[0]['marksScored'];
        }


        //tests pc

        $studentTestMarksArray = $studentManager->getStudentSubjectTests($studentId,$subjectId,$classId);
        $studentTestTypeCategoryArray = array();
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
                    echo MARKS_SCORED_MORE_THAN_TOTAL_MARKS_FOR_.$testTypeCategoryName;
                    die;
                }
                $return = $studentManager->updateMarksInTransaction($studentId,$subjectId,$classId,$testId,$marksScored);
                if ($return == false) {
                    echo ERROR_IN_MARKS_CALCULATION_FOR_.$testTypeCategoryName;
                    die;
                }
                $totalMaxMarks += $marksScored;
                $totalMarksScored += $marksScored;
            }

            //testtypewise marks

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


            //saving to test transferred marks
            foreach($allStudentsArray as $studentId => $subjectArray) {
                foreach($subjectArray as $subjectId => $testRecordArray) {
                    foreach($testRecordArray as $testTypeId => $testDetailsArray) {
                        $classId = $testDetailsArray['classId'];
                        $actualMaxMarks = $testDetailsArray['actualMaxMarks'];
                        $actualMarksScored = $testDetailsArray['actualMarksScored'];

                        $tables = TEST_TRANSFERRED_MARKS_TABLE;
                        $setCondition = "maxMarks = $actualMaxMarks, marksScored = $actualMarksScored";
                        $whereCondition = "studentId = $studentId AND testTypeId = $testTypeId AND classId = $classId AND subjectId = $subjectId";
                        $returnStatus = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
                        if ($returnStatus == false) {
                            echo FAILURE__221;
                            die;
                        }
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
            else {
                /*
                $tables = TEST_TRANSFERRED_MARKS_TABLE;
                $setCondition = "marksScored = $marksScored";
                $whereCondition = "studentId = $studentId AND testTypeId = $testTypeId AND classId = $classId AND subjectId = $subjectId";
                $returnStatus = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
                if ($returnStatus == false) {
                    echo ERROR_OCCURED_WHILE_UPDATING_TEST_MARKS;
                    die;
                }

                $tables = TEST_MARKS_TABLE;
                $setCondition = "marksScored = $marksScored";
                $whereCondition = "studentId = $studentId AND subjectId = $subjectId and testId  = 
                    (SELECT testId FROM ".TEST_TABLE." WHERE subjectId=$subjectId AND testTypeCategoryId = 
                    (SELECT testTypeCategoryId FROM test_type WHERE testTypeId=$testTypeId AND instituteId = $instituteId))";
               */     
                $tables = TOTAL_TRANSFERRED_MARKS_TABLE;
                $setCondition = "marksScored = $marksScored";
                $whereCondition = "studentId = $studentId AND subjectId = $subjectId AND classId = $classId AND conductingAuthority IN (2) ";
                $returnStatus = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
                if ($returnStatus == false) {
                    echo ERROR_OCCURED_WHILE_SAVING_TEST_MARKS;
                    die;
                }
            }
            $compreMaxMarksTotal += $maxMarks;
            $compreMarksScoredTotal += $marksScored;
        }



        //saving to total transferred marks
        if (empty($gradeId)) {
            $gradeId = "NULL";
        }

        $gradeSetGradingLabelArray = $studentManager->getClassGradeSetGradingLabel($classId,$subjectId);
        $gradeSetId = $gradeSetGradingLabelArray[0]['gradeSetId'];
        $gradingLabelId = $gradeSetGradingLabelArray[0]['gradingLabelId'];

        if (empty($gradingLabelId) or $gradingLabelId == '') {
            echo INVALID_GRADING_LABEL_FOUND;
            die;
        }

        /*
        if (!is_numeric($preCompreMarks) or !is_numeric($compreMarksScoredTotal) or !is_numeric($attendanceMarks)) {
            echo INVALID_MARKS;
            die;
        }
        */
        if(is_numeric($preCompreMarks)) {
            $returnStatus = $studentManager->updateStudentMarks($studentId,$classId,$subjectId,1,$preCompreMarks,$gradeId,$preCompre,$gradeSetId, $gradingLabelId);
            if ($returnStatus == false) {
                echo ERROR_OCCURED_WHILE_UPDATING_MARKS;
                exit;
            }
        }

        if(is_numeric($compreMarksScoredTotal)) {
            $returnStatus = $studentManager->updateStudentMarks($studentId,$classId,$subjectId,2,$compreMarksScoredTotal,$gradeId,$compre,$gradeSetId, $gradingLabelId);
            if ($returnStatus == false) {
                echo ERROR_OCCURED_WHILE_UPDATING_MARKS;
                exit;
            }
        }

        if(is_numeric($attendanceMarks)) {    
            $returnStatus = $studentManager->updateStudentMarks($studentId,$classId,$subjectId,3,$attendanceMarks,$gradeId,'Marks',$gradeSetId, $gradingLabelId);
            if ($returnStatus == false) {
                echo ERROR_OCCURED_WHILE_UPDATING_MARKS;
                exit;
            }
        }

        $returnStatus = $studentManager->updateStudentAttendanceGrade($studentId,$classId,$subjectId,3,$gradeId,'Marks',$gradeSetId, $gradingLabelId);
        if ($returnStatus == false) {
            echo ERROR_OCCURED_WHILE_UPDATING_MARKS;
            exit;
        }

        
        $newMarksArray = $studentInformationManager->getStudentCGPA($studentId,$classId);
        $totalGradePoints = 0;
        $totalCredits = 0;
        foreach($newMarksArray as $newRecord) {
            $totalGradePoints += $newRecord['gradePoints'] * $newRecord['credits'];
            $totalCredits += $newRecord['credits'];
        }


        if ($oldCGPAExists) {
            $returnStatus = $studentManager->updateStudentCGPA($studentId,$classId,$totalGradePoints,$totalCredits);
        }
        else {
            $returnStatus = $studentManager->insertStudentCGPA($studentId,$classId,$totalGradePoints,$totalCredits);
        }


        if ($returnStatus == false) {
            echo ERROR_OCCURED_WHILE_UPDATING_CGPA;
            exit;
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


?>