<?php
//This file saves student grades
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGrades');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
$labelId = $REQUEST_DATA['labelId'];
$subjectId = $REQUEST_DATA['subjectId'];
$degreeId = $REQUEST_DATA['degreeId'];
//$gadeLabelId = $REQUEST_DATA['gadeLabelId'];
$degreeList = $degreeId;
$gradingFormula = $REQUEST_DATA['gradingFormula'];
$gradingLabelName = trim($REQUEST_DATA['gradingLabelName']);
$manualChoice=$REQUEST_DATA['manualChoice']; 
     if($manualChoice=='1'){
    $gradingLabelName = trim($REQUEST_DATA['gradingLabelName1']);
   }
global $sessionHandler;


// Findout Internal Total Marks
$subjectArray = $studentManager->getSingleField('subject_to_class', 'DISTINCT IFNULL(internalTotalMarks,0)+IFNULL(externalTotalMarks,0) AS internalTotalMarks', "WHERE subjectId = '$subjectId' AND classId = '$degreeId'");
if(is_array($subjectArray) && count($subjectArray)>0 ) {
  $internalTotalMarks = $subjectArray[0]['internalTotalMarks'];
}


$instituteId = $sessionHandler->getSessionVariable('InstituteId');
if (empty($gradingLabelName)) {
	echo ENTER_GRADING_LABEL_NAME;
	die;
}

$cntArray = $studentManager->checkGradingLabelName($gradingLabelName);
$cnt = $cntArray[0]['cnt'];
if ($cnt > 0) {
	echo GRADING_LABEL_NAME_ALREADY_EXISTS;
	die;
}

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {

	$return = $studentManager->addGradingLabelInTransaction($gradingLabelName);
	if ($return === false) {
		echo ERROR_WHILE_CREATING_GRADING_LABEL;
		die;
	}

	$gadeLabelId = SystemDatabaseManager::getInstance()->lastInsertId();
	//$gradeRangesArray = $studentManager->getGrades();
	$gradeRangesArray = $studentManager->getActiveSetGrades();
  if($manualChoice==''){
	foreach($gradeRangesArray as $gradeRange) {
		$gradeId = $gradeRange['gradeId'];
		$gradeLabel = $gradeRange['gradeLabel'];
		$gradePoints = $gradeRange['gradePoints'];

		$gradingRangeFromNew = $REQUEST_DATA['textFrom'.$gradeId];
		$gradingRangeToNew = $REQUEST_DATA['textTo'.$gradeId];
		if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
			echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($gradingRangeToNew < $gradingRangeFromNew) {
			echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($i > 1) {
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
		$i++;
	}

 	$insertStr = '';
	$gradeSetId = '';
    $insertStrLast = '';
   
     
	foreach($gradeRangesArray as $gradeRange) {
		$gradeId = $gradeRange['gradeId'];
		$gradingRangeFromNew = $REQUEST_DATA['textFrom'.$gradeId];
		$gradingRangeToNew = $REQUEST_DATA['textTo'.$gradeId];
		$gradeSetId = $gradeRange['gradeSetId'];
		if (!empty($insertStr)) {
		   $insertStr .= ',';
           $insertStrLast .= ',';
		}
		$insertStr .= "($gadeLabelId, $gradingRangeFromNew, $gradingRangeToNew,$gradeId, $instituteId)";
       		$insertStrLast .= "($gradingRangeFromNew, $gradingRangeToNew, $instituteId,$internalTotalMarks)";
	}

  }   

   else { 

foreach($gradeRangesArray as $gradeRange) {
		$gradeId = $gradeRange['gradeId'];
		$gradeLabel = $gradeRange['gradeLabel'];
		$gradePoints = $gradeRange['gradePoints'];

		$gradingRangeFromNew = $REQUEST_DATA['txtfrom1'.$gradeId];
		$gradingRangeToNew = $REQUEST_DATA['txtto1'.$gradeId];
		if (!is_numeric($gradingRangeFromNew) or !is_numeric($gradingRangeToNew)) {
			echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($gradingRangeToNew < $gradingRangeFromNew) {
			echo INCORRECT_VALUES_FOR_GRADE_.$gradeLabel;
			die;
		}
		if ($i > 1) {
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
		$i++;
	}

 	$insertStr = '';
	$gradeSetId = '';
    $insertStrLast = '';
   
     
	foreach($gradeRangesArray as $gradeRange) {
		$gradeId = $gradeRange['gradeId'];
		$gradingRangeFromNew = $REQUEST_DATA['txtfrom1'.$gradeId];
		$gradingRangeToNew = $REQUEST_DATA['txtto1'.$gradeId];
		$gradeSetId = $gradeRange['gradeSetId'];
		if (!empty($insertStr)) {
		   $insertStr .= ',';
           $insertStrLast .= ',';
		}
		$insertStr .= "($gadeLabelId, $gradingRangeFromNew, $gradingRangeToNew,$gradeId, $instituteId)";
       		$insertStrLast .= "($gradingRangeFromNew, $gradingRangeToNew, $instituteId,$internalTotalMarks)";
	}



}


    if($insertStr=='') {
       echo "Grading not defined"; 
       die;  
    }
    else {
       //   if(doubleval($internalTotalMarks) <= 100) {
             // START Grading Scale Save
             $return = $studentManager->deleteLastGradingScalesInTransaction($internalTotalMarks);
             if($return === false) {
               echo ERROR_WHILE_CREATING_GRADING_SCALES;
               die;
             }
              
             $return = $studentManager->addLastGradingScalesInTransaction($insertStrLast);
             if($return === false) {
               echo ERROR_WHILE_CREATING_GRADING_SCALES;
               die;
             }
             // LAST Grading Scale
        //  }
        
	      $return = $studentManager->addGradingScalesInTransaction($insertStr);
	      if($return === false) {
		    echo ERROR_WHILE_CREATING_GRADING_SCALES;
		    die;
	      }
    }
	
	$gradeRangesArray = $studentManager->getGradeLabels($gadeLabelId);
	$whereCondition = '';
	foreach($gradeRangesArray as $gradeRange) {
		$gradingRangeFrom = $gradeRange['gradingRangeFrom'];
		$gradingRangeTo = $gradeRange['gradingRangeTo'];
		$gradeId = $gradeRange['gradeId'];
		$studentsArray = $studentManager->getGradeRangeStudents($subjectId, $gradingRangeFrom, $gradingRangeTo, $degreeList,$gradingFormula);
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
			$whereCondition = " CONCAT(studentId,'#',classId,'#',subjectId) in ($str)";
			$return = $studentManager->updateRecordInTransaction(TOTAL_TRANSFERRED_MARKS_TABLE, "gradingLabelId = $gadeLabelId, gradeId = $gradeId, gradeSetId = $gradeSetId", $whereCondition);
			if ($return === false) { 
				echo FAILURE;
				die;
			}
		}
	}
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		$classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId = $classId");
		$className = $classNameArray[0]['className'];
		$auditTrialDescription = "Grades have been applied for Class: $className ,Subject: " ;
		$type = GRADES_ARE_APPLIED; //GRADES APPLIED
		$subjectsArray = $studentManager->getSingleField('subject', 'subjectCode', " where subjectId = $subjectId");
		$subjectCode = $subjectsArray[0]['subjectCode'];
		$auditTrialDescription .= $subjectCode;
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		}
		########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################

	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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


//$History: scSaveGrades2.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Library/ScStudent
//done changes to implement multi-institute in SC
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/26/09   Time: 1:38p
//Updated in $/Leap/Source/Library/ScStudent
//done changes for not showing Absent students in grading.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/24/09   Time: 10:49a
//Updated in $/Leap/Source/Library/ScStudent
//done the changes to make grading work based on grade-set selected.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/09    Time: 2:31p
//Created in $/Leap/Source/Library/ScStudent
//file added for grading - advanced
//

?>
