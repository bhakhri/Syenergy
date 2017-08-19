<?php
//This file is used as printing version for test marks updation report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	define('MODULE','MarksStatusReport');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

 	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();


	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	$labelId = $REQUEST_DATA['labelId'];

	$conditions = '';
	if ($classId != 'all') {
		$conditions = " AND ttc.classId = $classId";
		if ($subjectId != 'all') {
			$conditions .= " AND t.subjectId = $subjectId";
		}
	}

	$studentRecordArray = $studentReportsManager->getMarksStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');
	$cnt = count($studentRecordArray);


	$csvData ='';
    $csvData="Sr No.,Class,Subject,Faculty,Group,Test Type,Test Abbr.,Index,M.M.,Students";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
		//$offenseArray[$i]['offenseDate']  = UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
        $csvData .= ($i+1).",";
		  $csvData .= $studentRecordArray[$i]['className'].",";
		  $csvData .= $studentRecordArray[$i]['subjectCode'].",";
		  $csvData .= $studentRecordArray[$i]['employeeName'].",";
		  $csvData .= $studentRecordArray[$i]['groupShort'].",";
		  $csvData .= $studentRecordArray[$i]['testTypeName'].",";
		  $csvData .= $studentRecordArray[$i]['testAbbr'].",";
		  $csvData .= $studentRecordArray[$i]['testIndex'].",";
		  $csvData .= $studentRecordArray[$i]['maxMarks'].",";
		  $csvData .= $studentRecordArray[$i]['studentCount'];
		  $csvData .= "\n";
	}
	
	if($cnt == 0){
		$csvData .= "No Data Found";	
	}

	UtilityManager::makeCSV($csvData, 'Marks Status Report.csv');
	die;

?>
