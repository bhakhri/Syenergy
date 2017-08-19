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
define('MODULE','ApplyGrade');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$labelId = $REQUEST_DATA['labelId'];
$subjectId = $REQUEST_DATA['subjectId'];
$degreeId = $REQUEST_DATA['degreeId'];
$degreeList = $degreeId;
$gradingFormula = $REQUEST_DATA['gradingFormula'];



	// Findout Internal Total Marks
	$subjectArray = $studentManager->getSingleField('subject_to_class', 
							'DISTINCT 
							IFNULL(internalTotalMarks,0)+IFNULL(externalTotalMarks,0) AS internalTotalMarks', 
							"WHERE subjectId = '$subjectId' AND  classId = '$degreeId' ");
 
	$gradesArray = $studentManager->getActiveSetGrades();
    
	$internalTotalMarks = $subjectArray[0]['internalTotalMarks'];

	// Last Save 
	$gradesLastArray = $studentManager->getLastGradingScalesNew($internalTotalMarks); 
	$lastGradeCount=count($gradesLastArray);
	
	$totalArray = Array('lastGradeCount' => $lastGradeCount, 'internalTotalMarks' => $internalTotalMarks, 
			    'pendingStudents' => 0, 'gradesArray' => $gradesArray, 
			    'gradesLastArray' => $gradesLastArray);

	echo json_encode($totalArray);



//$History: scShowSliders.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/24/09   Time: 10:49a
//Updated in $/Leap/Source/Library/ScStudent
//done the changes to make grading work based on grade-set selected.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 4/20/09    Time: 5:22p
//Created in $/Leap/Source/Library/ScStudent
//file added for grading-advanced
//

?>
