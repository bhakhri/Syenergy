<?php 
//This file is used as csv version for attendance missed report.
//
// Author :Ajinder Singh
// Created on : 02-Sep-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

	$studentReportManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$tillDate = $REQUEST_DATA['tillDate'];
//	$tillDate = date('Y-m-d',strtotime($tillDate));
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];

	$labelId = $REQUEST_DATA['labelId'];

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$recordArray = array();

    // $condition: It checks the value of hasAttendance field for every subject
    $condition = " AND c.hasAttendance =1 ";
       
	if ($classId == 'all') {
			$recordArray = $studentReportManager->getAllClassMissedAttendanceReport($labelId, $tillDate, $sortField, $sortOrderBy,$condition);
			$reportManager->setReportInformation("For All Classes As On $formattedDate ");
	}
	else {

	   $classNameArray = $studentReportManager->getSingleField('class', 'SUBSTRING_INDEX(className,"-",-3) as className', "where classId  = $classId");
	   $className = $classNameArray[0]['className'];
	   $className2 = str_replace("-",' ',$className);

    
		if ($subjectId == 'all') {
			//fetch data for all subjects of selected class
			$recordArray = $studentReportManager->getAllSubjectMissedAttendanceReport($classId, $tillDate, $sortField, $sortOrderBy,$condition);
			$reportManager->setReportInformation("For $className2, Subject: All, As On $formattedDate ");
		}
		else {
			//fetch data for selected subject of selected class
			$subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode', "where hasAttendance =1 AND subjectId  = $subjectId");
			$subCode = $subCodeArray[0]['subjectCode'];
			$recordArray = $studentReportManager->getOneSubjectMissedAttendanceReport($classId, $subjectId, $tillDate, $sortField, $sortOrderBy,$condition);
			$reportManager->setReportInformation("For $className2, Subject: $subCode As On $formattedDate ");
		}
	}

	$cnt = count($recordArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
	
	$csvData = '';
	$csvData .= "Sr, Class, Subject, Group, Faculty, Attendance Till \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].",";
		$csvData .= $record['className'].",";
		$csvData .= $record['subjectCode'].',';
		$csvData .= $record['groupName'].',';
		$csvData .= $record['employeeName'].',';
		$csvData .= $record['toDate'];
		$csvData .= "\n";
	}

	UtilityManager::makeCSV($csvData,'MissedAttendanceReport.csv');
	die;


?>
