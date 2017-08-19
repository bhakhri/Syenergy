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

	$csvData ='';
    $csvData="Sr No.,Roll No.,Student Name,Mobile No.,Enail, No. of Offenses";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
		//$offenseArray[$i]['offenseDate']  = UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
        $csvData .= ($i+1).",";
		  $csvData .= $offenseRecordArray[$i]['rollNo'].",";
		  $csvData .= $offenseRecordArray[$i]['studentName'].",";
		  $csvData .= $offenseRecordArray[$i]['studentMobileNo'].",";
		  $csvData .= $offenseRecordArray[$i]['studentEmail'].",";
		  $csvData .= $offenseRecordArray[$i]['totalOffenses'].",";
		  $csvData .= "\n";
		
	}

	//$rollNo = $offenseArray[0]['rollNo'];
	//$studentName = $offenseArray[0]['studentName'];
	//$className = $offenseArray[0]['className'];

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="'.'OffeseListReport.csv'.'"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;  
	die;

?>