<?php
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 02-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['class1'];
	$classSubjectsArray = $studentManager->getClassSubjects($classId);
	$orderArray = Array();
	foreach($classSubjectsArray as $classSubjectRecord) {
		$subjectId = $classSubjectRecord['subjectId'];
		$subjectCode = $classSubjectRecord['subjectCode'];
		$externalTotalMarks = $classSubjectRecord['externalTotalMarks'];

		if (isset($REQUEST_DATA['text_'.$subjectId]) and is_numeric($REQUEST_DATA['text_'.$subjectId])) {
			if (isset($orderArray[$REQUEST_DATA['text_'.$subjectId]]) and $orderArray[$REQUEST_DATA['text_'.$subjectId]] != '') {
				echo DUPLICATE_ORDER_FOUND;
				die;
			}
			$orderArray[$REQUEST_DATA['text_'.$subjectId]] = array('subjectId'=>$subjectId, 'subjectCode'=>$subjectCode, 'externalTotalMarks'=>$externalTotalMarks);
		}
		else {
			echo INVALID_ORDER_FOUND_FOR_SUBJECT_.$subjectCode;
			die;
		}
	}
	ksort($orderArray);
	$csvData = ',,';
	$csvData2 = ',,';
	$mainCSVData = '';

	foreach($orderArray as $recordArray) {
		$subjectId = $recordArray['subjectId'];
		$subjectCode = $recordArray['subjectCode'];
		$externalTotalMarks = $recordArray['externalTotalMarks'];
		$csvData .= ",$subjectCode";
		$csvData2 .= ",$externalTotalMarks";
	}
	$csvData .= "\n";
	$csvData2 .= "\n";
	$mainCSVData .= $csvData . $csvData2;
	$mainCSVData .= "Sr.No,Univ. Roll No., Name \n";
	$studentArray = $studentManager->getClassStudents($classId);
	$i = 1;
	foreach($studentArray as $studentRecord) {
		$universityRollNo = $studentRecord['universityRollNo'];
		$studentName = $studentRecord['studentName'];
		$mainCSVData .= "$i,$universityRollNo,$studentName\n";
		$i++;
	}

	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::makeCSV($mainCSVData, 'Marks_Sample_File.csv');
	die;
	

//$History: listDownloadExternalMarksSampleFile.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 7:07p
//Created in $/LeapCC/Templates/Student
//file uploaded for 'marks upload'
//


?>