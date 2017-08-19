 <?php 
//This file is used as printing version for display Hostel.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
    $hostelRoomManager = HostelRoomManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (hr.roomName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hs.hostelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hr.roomCapacity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR hrt.roomType LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
     $orderBy = "$sortField $sortOrderBy";

    $hostelRoomRecordArray = $hostelRoomManager->getHostelRoomList($filter,'',$orderBy);
    
	$recordCount = count($hostelRoomRecordArray);

    $valueArray = array();
    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Room Name,Hostel Name,Room Capacity,Room Type, Room Floor";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $hostelRoomRecordArray[$i]['roomName'].",";
		  $csvData .= $hostelRoomRecordArray[$i]['hostelName'].",";
		  $csvData .= $hostelRoomRecordArray[$i]['roomCapacity'].",";
		  $csvData .= $hostelRoomRecordArray[$i]['roomType'].",";
          $csvData .= $hostelRoomRecordArray[$i]['roomFloor'].",";  
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'HostelRoomReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
