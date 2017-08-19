<?php
//This file is used as printing version for fine collection report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	
    require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/FineManager.inc.php");
	$fineManager = FineManager::getInstance();

define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();    

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineCategoryName';
    
     $orderBy = "$sortField $sortOrderBy"; 
	
	//$studentId = $REQUEST_DATA['studentId'];
	//$classId = $REQUEST_DATA['classId'];
	$fineCategoryId = $REQUEST_DATA['fineCategoryId'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];


		$conditions = "	AND fs.fineCategoryId = $fineCategoryId AND (fr.receiptDate BETWEEN '$startDate' AND '$toDate')";
		//$fineTotalCollectionArray = $fineManager->getFineCollectionReportDetail($conditions,'',$orderBy);
		$fineCollectionArray = $fineManager->getFineCollectionListPrint($conditions,$orderBy);
		$cnt = count($fineCollectionArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$fineCollectionArray[$i]['fineDate']  = UtilityManager::formatDate($fineCollectionArray[$i]['fineDate']);
		$fineCollectionArray[$i]['receiptDate']  = UtilityManager::formatDate($fineCollectionArray[$i]['receiptDate']);
        $valueArray[] = array_merge(array('srNo' => ($i+1) ),$fineCollectionArray[$i]);
	}

	$fineCategoryName = $fineCollectionArray[0]['fineCategoryName'];
	//$amount = $fineTotalCollectionArray[0]['amount'];
	//$className = $offenseArray[0]['className'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Category Wise Fine Collection Report');
	$reportManager->setReportInformation("Fine Category Name : $fineCategoryName");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					'width="4%" align="left"', 'align="left"');
	$reportTableHead['rollNo']				=	array('Roll No.',		'width=12% align="left"', 'align="left"');
	$reportTableHead['studentName']			=	array('Student Name',		'width=12% align="left"', 'align="left"');
	$reportTableHead['fineDate']			=	array('Fine Date',				'width="12%" align="center" ', 'align="center"');
	$reportTableHead['receiptDate']			=	array('Collect Fine Date',				'width="12%" align="center" ', 'align="center"');
	$reportTableHead['employeeName']		=	array('Fined By',		'width="20%" align="left"', 'align="left"');
	$reportTableHead['reason']				=	array('Reason',			'width="20%" align="left"', 'align="left"');
	$reportTableHead['amount']				=	array('Amount',			'width="20%" align="right"', 'align="right"');
		
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();


//$History : $
//
//
?>