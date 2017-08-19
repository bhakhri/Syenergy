 <?php 
//This file is used as printing version for display periods.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(MODEL_PATH . "/PeriodsManager.inc.php");
    $periodsManager = PeriodsManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (pr.periodNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ps.slotName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
     $orderBy = " $sortField $sortOrderBy";

	$periodsArray = $periodsManager->getPeriodsList($filter,'',$orderBy);
    
	$recordCount = count($periodsArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Slot,Period Number,Start Time,End Time";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $periodsArray[$i]['slotName'].",";
		  $csvData .= $periodsArray[$i]['periodNumber'].",";
		  $csvData .= $periodsArray[$i]['startTime'].",";
		  $csvData .= $periodsArray[$i]['endTime'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'PeriodsReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>