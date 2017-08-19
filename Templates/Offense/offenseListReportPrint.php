<?php
//This file is used as printing version for test marks updation report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
	
	$noOfOffenseValue = $REQUEST_DATA['noOfOffense'];
	$instances = $REQUEST_DATA['instances'];
	$offenseCategory = $REQUEST_DATA['offenseCategory'];

	if ($offenseCategory != "") {
		$condition = "AND sd.offenseId = $offenseCategory";
		$offenseTotalRecordArray = $offenseManager->getTotalOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$orderBy);
	    $offenseRecordArray = $offenseManager->getOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$limit,$orderBy);
		$cnt = count($offenseRecordArray);
	}
	else {
		$offenseTotalRecordArray = $offenseManager->getTotalOffenseReportDetail($noOfOffenseValue,$instances,$condition,$filter,$orderBy);
		$offenseRecordArray = $offenseManager->getOffenseReportDetail($noOfOffenseValue,$instances,$filter,$condition,$limit,$orderBy);
		$cnt = count($offenseRecordArray);
	
	}

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$offenseArray[$i]['offenseDate']  = UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
        $valueArray[] = array(	'srNo' => $i+1 ,
								'rollNo' => $offenseRecordArray[$i]['rollNo'],
								'studentName' => $offenseRecordArray[$i]['studentName'],
								'studentMobileNo' => $offenseRecordArray[$i]['studentMobileNo'],
								'studentEmail' => $offenseRecordArray[$i]['studentEmail'],
								'totalOffenses' => $offenseRecordArray[$i]['totalOffenses']
							);
	}

	//$rollNo = $offenseArray[0]['rollNo'];
	//$studentName = $offenseArray[0]['studentName'];
	//$className = $offenseArray[0]['className'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Offense List Report');
	//$reportManager->setReportInformation("Roll No : $rollNo, Student Name : $studentName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',				'width="4%"', "align='right' ");
	$reportTableHead['rollNo']				=	array('Roll No.',		'width=12% align="left"', 'align="left"');
	$reportTableHead['studentName']			=	array('Student Name',	'width=12% align="left"', 'align="left"');
	$reportTableHead['studentMobileNo']		=	array('Mobile No.',		'width="12%" align="right" ', 'align="right"');
	$reportTableHead['studentEmail']		=	array('Email',			'width="20%" align="left"', 'align="left"');
	$reportTableHead['totalOffenses']		=	array('No. of Offenses',		'width="10%" align="right"', 'align="right"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>