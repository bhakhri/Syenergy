<?php
//This file saves student grades
// Author :Parveen Sharma
// Created on : 21-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGrade');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); 

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();  
    
global $sessionHandler;
$queryDescription =''; 

    $labelId = $REQUEST_DATA['labelId'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    $gadeLabelId = $REQUEST_DATA['gadeLabelId'];
    $degreeList = $degreeId;
    $gradingFormula = $REQUEST_DATA['gradingFormula'];
    $ttGradeFrom = $REQUEST_DATA['ttGradeFrom'];   
    $ttGradeTo = $REQUEST_DATA['ttGradeTo'];   
    $hiddenGradeId = $REQUEST_DATA['hiddenGradeId'];   
    
    $pendingStudents='0';
    $gradeRangesArray = array();

    
    $j=0;
    $rangArray = $studentManager->getGradeLabels($gadeLabelId);    
    for($i=0;$i<count($rangArray);$i++) {
      for($k=0;$k<count($ttGradeFrom);$k++) {  
          if($rangArray[$i]['gradeId']==$hiddenGradeId[$k]) {
            $gradeRangesArray[$j]['gradingRangeFrom'] = $ttGradeFrom[$k];  
            $gradeRangesArray[$j]['gradingRangeTo'] = $ttGradeTo[$k];  
            $gradeRangesArray[$j]['gradeId'] = $hiddenGradeId[$k];  
            $gradeRangesArray[$j]['gradePoints'] = $rangArray[$i]['gradePoints']; 
            $gradeRangesArray[$j]['gradeLabel'] = $rangArray[$i]['gradeLabel'];
            $gradeRangesArray[$j]['studentCount'] = 0;   
            $gradeRangesArray[$j]['gradeSetId'] = $rangArray[$i]['gradeSetId'];  
            $j++;
          }
      }
    }
    

    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $studentCountArray = array();
        $i = 0;
        foreach($gradeRangesArray as $gradeRange) {
            $gradingRangeFrom = $gradeRange['gradingRangeFrom'];
            $gradingRangeTo = $gradeRange['gradingRangeTo'];
            $gradeId = $gradeRange['gradeId'];
            $gradeSetId = $gradeRange['gradeSetId'];  
            $studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFrom, $gradingRangeTo, $degreeList,$gradingFormula);
            
            $scaleCondition = " gradingLabelId = '$gadeLabelId' AND gradeId = '$gradeId' ";
            $return = $studentManager->updateGradeScaleInTransaction($gradingRangeFrom,$gradingRangeTo,$scaleCondition );
            if ($return === false) {
                echo FAILURE;
                die;
            }
            $str = ''; 
            foreach($studentsArray as $studentRecord) {
                $studentId = $studentRecord['studentId'];
                $classId = $studentRecord['classId'];
                if (!empty($str)) {
                    $str .= ',';
                }
                $str .= "'$studentId#$classId#$subjectId'";
            }
            if ($str != '') {
                $whereCondition = " CONCAT(studentId,'#',classId,'#',subjectId) IN ($str)";
                $return = $studentManager->updateRecordInTransaction(TOTAL_TRANSFERRED_MARKS_TABLE, "gradingLabelId = '$gadeLabelId', gradeId = '$gradeId', gradeSetId = '$gradeSetId' ", $whereCondition);
                if ($return === false) {
                    echo FAILURE;
                    die;
                }
                $whereCondition = " CONCAT(studentId,'#',classId,'#',subjectId) IN ($str)";
                $return = $studentManager->updateRecordInTransaction(TOTAL_TRANSFERRED_MARKS_TABLE, "gradingLabelId = '$gadeLabelId', gradeId = '$gradeId', gradeSetId = '$gradeSetId' ", $whereCondition);
                if ($return === false) {
                    echo FAILURE;
                    die;
                }
            }
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
            $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
            $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId = '$classId' ");
            $className = $classNameArray[0]['className'];
            $auditTrialDescription = "Grades have been applied for Class: $className, Subject: " ;
            $type = GRADES_ARE_APPLIED; //GRADES APPLIED
            $subjectsArray = $studentManager->getSingleField('subject', 'subjectCode', " where subjectId = '$subjectId' ");
            $subjectCode = $subjectsArray[0]['subjectCode'];
            $auditTrialDescription .= $subjectCode;
            $returnStatus = $commonQueryManager->addAuditTrialRecord($type,$auditTrialDescription,$queryDescription);
            if($returnStatus === false) {
              echo "Error while saving data for audit trail";
              die;
            }
            ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
            echo SUCCESS;
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

?>