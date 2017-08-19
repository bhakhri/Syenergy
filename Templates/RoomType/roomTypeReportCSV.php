 <?php 
//This file is used as printing version for display Hostel.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(MODEL_PATH . "/RoomTypeManager.inc.php");
    $roomTypeManager = RoomTypeManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if (!empty($search)) {
		$conditions =' WHERE (roomType LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR abbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomType';
    
    $orderBy = " $sortField $sortOrderBy";

	$roomTypeArray = $roomTypeManager->getRoomTypeList($conditions,$orderBy,'');
    
	$recordCount = count($roomTypeArray);

    $valueArray = array();

    $csvData ='';
	$csvData = "Search by:$search";
	$csvData .="\n";
    $csvData .="#,Room Type,Abbreviation";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		$csvData .= ($i+1).",";
		$csvData .= $roomTypeArray[$i]['roomType'].",";
		$csvData .= $roomTypeArray[$i]['abbr'].",";
		$csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'RoomType.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>