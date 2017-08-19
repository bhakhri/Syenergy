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
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/OffenseManager.inc.php");
	$offenseManager = OffenseManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
     $orderBy = "$sortField $sortOrderBy"; 
	
	$studentId = $REQUEST_DATA['studentId'];
	$classId = $REQUEST_DATA['classId'];
	$offenseId = $REQUEST_DATA['offenseId'];


		$conditions = "	AND sd.studentId = $studentId";
		$offenseArray = $offenseManager->getOffenseListPrint($conditions,$orderBy);
		$cnt = count($offenseArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$offenseArray[$i]['offenseDate']  = UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
        $valueArray[] = array(	'srNo' => $i+1 ,
								'className' => $offenseArray[$i]['className'],
								'offenseName' => $offenseArray[$i]['offenseName'],
								'offenseDate' => $offenseArray[$i]['offenseDate'],
								'reportedBy' => $offenseArray[$i]['reportedBy'],
								'remarks' => $offenseArray[$i]['remarks']
							);
	}

	$rollNo = $offenseArray[0]['rollNo'];
	$studentName = $offenseArray[0]['studentName'];
	//$className = $offenseArray[0]['className'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Offense Report');
	$reportManager->setReportInformation("Roll No : $rollNo, Student Name : $studentName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					'width="4%"', "align='right' ");
	$reportTableHead['className']			=	array('Class Name',		'width=20% align="left"', 'align="left"');
	$reportTableHead['offenseName']			=	array('Offense Name',		'width=12% align="left"', 'align="left"');
	$reportTableHead['offenseDate']			=	array('Date',				'width="12%" align="left" ', 'align="left"');
	$reportTableHead['reportedBy']			=	array('Reported By',		'width="10%" align="left"', 'align="left"');
	$reportTableHead['remarks']				=	array('Remarks',			'width="15%" align="left"', 'align="left"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>