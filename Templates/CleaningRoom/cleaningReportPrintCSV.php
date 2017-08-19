 <?php 
//This file is used as printing version for display Cleaning Record.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
    $cleaningRoomManager = CleaningRoomManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if (!empty($search)) {
        $conditions =' AND (hs.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR et.tempEmployeeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';        
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tempEmployeeName';
    
    $orderBy = " $sortField $sortOrderBy";

	$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningRoomDetailList($conditions,'',$orderBy);
    
	$recordCount = count($cleaningRoomRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Hostel Name,Date,Employee Name,Toilet(s) Cleaned,Room(s) Cleaned,Attached Room Toilet(s) Cleaned,Dry Mopping,Wet Mopping,Road Cleaned,Garbage Disposal,Working hrs.";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $cleaningRoomRecordArray[$i]['Dated'] = UtilityManager::FormatDate($cleaningRoomRecordArray[$i]['Dated']);
		  $csvData .= ($i+1).",";
		  $csvData .= $cleaningRoomRecordArray[$i]['hostelName'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['Dated'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['tempEmployeeName'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['toiletsCleaned'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['noOfRoomsCleaned'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['attachedRoomToiletsCleaned'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['dryMoppingInSqMeter'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['wetMoppingInSqMeter'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['roadCleanedInSqMeter'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['noOfGarbageBinsDisposal'].",";
		  $csvData .= $cleaningRoomRecordArray[$i]['noOfHoursWorked'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'CleaningRoomReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>