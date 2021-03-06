 <?php
//This file is used as printing version for display classes.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classesManager = ClassesManager::getInstance();

    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  (degreeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR batchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR branchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR studentCount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR degreeDuration LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR periodName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';

    $orderBy = " $sortField $sortOrderBy";

	$classesRecordArray = $classesManager->getClassesList($filter,'',$orderBy);

	$recordCount = count($classesRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData .="SearchBy:".trim($REQUEST_DATA['searchbox']);
	$csvData .="\n";
    $csvData .="#,Degree,Batch,Branch,Duration (Yrs.),Active Classes, Student";
    $csvData .="\n";
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $classesRecordArray[$i]['degreeCode'].",";
		  $csvData .= $classesRecordArray[$i]['batchName'].",";
		  $csvData .= $classesRecordArray[$i]['branchName'].",";
		  $csvData .= $classesRecordArray[$i]['degreeDuration'].",";
		 // $csvData .= $classesRecordArray[$i]['branchCode'].",";
		  $csvData .= $classesRecordArray[$i]['Active'].",";
          $csvData .= $classesRecordArray[$i]['studentCount'].",";
		  $csvData .= "\n";
  }
  if($recordCount == 0){
	  $csvData .="NO Data Found";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'ClassesReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>