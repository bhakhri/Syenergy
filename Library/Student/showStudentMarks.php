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

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$rollNo = trim($REQUEST_DATA['rollNo']);
	if (empty($rollNo)) {
		echo INVALID_ROLL_NO;
		die;
	}
	$rollNo = add_slashes($rollNo);
	$studentIdArray = $studentManager->getStudentIdDetails($rollNo);
	$studentId = $studentIdArray[0]['studentId'];
	if ($studentId == '') {
		echo INVALID_ROLL_NO;
		exit;
	}

	$classId = $studentIdArray[0]['classId'];
	$studentName = $studentIdArray[0]['studentName'];
	$fatherName = $studentIdArray[0]['fatherName'];


	$resultArray = $studentManager->getStudentMarksGrades($studentId);
	$cnt = count($resultArray);
	$valueArray = array();
	for($i=0;$i<$cnt;$i++) {
  		$totalMarks = $resultArray[$i]['totalMarks'];
		$resultArray[$i]['studentName'] = $studentName;
		$resultArray[$i]['fatherName'] = $fatherName;
        $resultArray[$i]['totalMarks']=$totalMarks;      
        if($resultArray[$i]['examType']==2) {
          $grades = $resultArray[$i]['grades'];   
          $subjectCode = $resultArray[$i]['subjectCode']; 
          $resultArray[$i]['subjectCode'] = "<span style='font-family: Arial, Helvetica, sans-serif;font-size: 12px;color:red'>".$subjectCode."*</span>";  
          $resultArray[$i]['grades'] = "<span style='font-family: Arial, Helvetica, sans-serif;font-size: 12px;color:red'>".$grades."</span>";  
        $resultArray[$i]['totalMarks']="<span style='font-family: Arial, Helvetica, sans-serif;font-size: 12px;color:red'>".$totalMarks."</span>";   
        }
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$resultArray[$i]);
	}
	echo json_encode($valueArray);

// $History: scShowStudentMarks.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/03/10    Time: 1:39p
//Updated in $/Leap/Source/Library/ScStudent
//fixed issue. 0002313
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/01/09    Time: 5:19p
//Updated in $/Leap/Source/Library/ScStudent
//added student name,
//corrected attendance bug
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 1/14/09    Time: 1:11p
//Updated in $/Leap/Source/Library/ScStudent
//applied access rights
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/04/08   Time: 4:36p
//Created in $/Leap/Source/Library/ScStudent
//file made for marks updation for single student
//


?>
