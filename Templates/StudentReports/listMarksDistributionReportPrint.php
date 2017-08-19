<?php 
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
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
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

    $page = $REQUEST_DATA['page'];
    $sortOrderBy = $REQUEST_DATA['sortOrderBy'];
    $sortField = $REQUEST_DATA['sortField'];
    $degree = $REQUEST_DATA['degree'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $subjectId = $REQUEST_DATA['subjectId'];


   $classNameArray = $studentReportManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $degree");
   $className = $classNameArray[0]['className'];
   $className2 = str_replace("-",' ',$className);

   if($subjectId == 'all') {
	  $subCode = 'All';
   }
   else {
	  $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode', "WHERE (hasMarks=1 OR hasAttendance=1) AND subjectId  = $subjectId");
	  $subCode = $subCodeArray[0]['subjectCode'];
   }


	$sortField = $sortField == 'subjectCode' ? " subjectCode $sortOrderBy, conductingAuthority, testTypeName " : " conductingAuthority $sortOrderBy, subjectCode, testTypeName ";

    $condition = " AND (a.hasMarks=1 OR a.hasAttendance=1) ";    
	if($subjectId == 'all') {
	   $subjectsArray = $studentReportManager->getMarksDistributionAllSubjects($degree, $subjectTypeId, $sortField,'',$condition);
	}
	else {
	   $subjectsArray = $studentReportManager->getMarksDistributionOneSubject($degree, $subjectTypeId, $subjectId, $sortField,'',$condition);
	}

	$cnt = count($subjectsArray);
	$oldValue = '';
	$newValue = '';

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$newValue = $subjectsArray[$i]['conductingAuthority2'] . $subjectsArray[$i]['subjectCode'];

		if ($newValue === $oldValue) {
			$subjectsArray[$i]['conductingAuthority2'] = '';
			$subjectsArray[$i]['subjectCode'] = '';
		}
	
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$subjectsArray[$i]);
		$oldValue = $newValue;
   }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Marks Distribution Report');
	$reportManager->setReportInformation("$className2, Subjects : $subCode");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="5%" align=left', "align='left' ");
	$reportTableHead['conductingAuthority2']	=	array('Conducting Authority',	'width=18% align="left"', 'align="left"');
	$reportTableHead['subjectCode']				=	array('Subject',				'width="15%" align="left" ', 'align="left"');
	$reportTableHead['testTypeName']			=	array('Test Type',			'width="18%" align="left"', 'align="left"');
	$reportTableHead['weightageAmount']			=	array('Weightage Amt.',			'width="18%" align="right"','align="right"');
	$reportTableHead['weightagePercentage']		=	array('Weightage %',	'width="18%" align="right"','align="right"');


	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksDistributionReportPrint.php $
//
?>