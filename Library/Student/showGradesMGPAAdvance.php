<?php
//This file saves student grades
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();


    $labelId = $REQUEST_DATA['labelId'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    $gadeLabelId = $REQUEST_DATA['gadeLabelId'];
    $degreeList = $degreeId;
    $gradingFormula = $REQUEST_DATA['gradingFormula'];
    $gradingFrom = $REQUEST_DATA['ttGradeFrom'];
    $gradingTo = $REQUEST_DATA['ttGradeTo'];
    
    $pendingStudentsArray = array();
    $pendingStudents = 0;
    
    /* 
        $pendingStudentsArray = $studentManager->checkSubjectNotTransferredStudents($subjectId, $degreeList);
        $pendingStudents = $pendingStudentsArray[0]['cnt'];
        if($pendingStudents > 0) {
	       echo MARKS_NOT_TRANSFERRED_FOR_ALL_STUDENTS;
	       die;
        }
    */
    $gradeRangesArray = $studentManager->getActiveSetGrades();

    $studentCountArray = array();
    $i = 0;
    foreach($gradeRangesArray as $gradeRange) {
	    $gradeId = $gradeRange['gradeId'];
	    $gradeLabel = $gradeRange['gradeLabel'];
	    $gradePoints = $gradeRange['gradePoints'];

	    $gradingRangeFromNew = $gradingFrom[$i];
	    $gradingRangeToNew = $gradingTo[$i];
        
       if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
		    echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		    die;
	    }
	    if ($gradingRangeToNew < $gradingRangeFromNew) {
		    echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
		    die;
	    }
        
        if($i!=0) {
		    if ($gradingRangeFromNew >= $gradingRangeFrom and $gradingRangeFromNew <= $gradingRangeTo) {
		      echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
		      die;
		    }
		    if ($gradingRangeToNew >= $gradingRangeFrom and $gradingRangeToNew <= $gradingRangeTo) {
		       echo INCORRECT_RANGE_FOR_GRADE_.$gradeLabel;
		       die;
		    }
        }
        
        $gradingRangeFrom = $gradingRangeFromNew;
        $gradingRangeTo = $gradingRangeToNew;
        
        $studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFromNew, $gradingRangeToNew, $degreeList,$gradingFormula);
        $studentCountArray[$gradeId] = $gradeRange;
        $studentCountArray[$gradeId]['studentCount'] = count($studentsArray);
        $studentCountArray[$gradeId]['gradePoints'] = $gradePoints;
	    $i++;
    }

    $totalArray = Array('gradeArray'=>$gradeRangesArray,'studentCountArray' => $studentCountArray);
    echo json_encode($totalArray);
    
?>
