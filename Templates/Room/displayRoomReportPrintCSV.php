 <?php 
//This file is used as printing version for display room.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	
   require_once(MODEL_PATH . "/RoomManager.inc.php");
    $roomManager = RoomManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=="laboratory") {
				   $type="Laboratory";
		   }
		   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=="theory") {
			   $type="Theory";
		   }
        else{
             $type=-1;
		}
		   
       $filter = ' AND (roomName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR capacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomType LIKE "%'.$type.'%" OR blockName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR buildingName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR examCapacity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
     $orderBy = " $sortField $sortOrderBy";

	$roomRecordArray = $roomManager->getRoomList($filter,'',$orderBy);
    
	$recordCount = count($roomRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Room Name,Abbr.,Room Type,Building,Block Name,Capacity,Examroom Capacity";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $roomRecordArray[$i]['roomName'].",";
		  $csvData .= $roomRecordArray[$i]['roomAbbreviation'].",";
		  $csvData .= $roomRecordArray[$i]['roomType'].",";
		  $csvData .= $roomRecordArray[$i]['buildingName'].",";	
		  $csvData .= $roomRecordArray[$i]['blockName'].",";
		  $csvData .= $roomRecordArray[$i]['capacity'].",";
		  $csvData .= $roomRecordArray[$i]['examCapacity'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'RoomReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>