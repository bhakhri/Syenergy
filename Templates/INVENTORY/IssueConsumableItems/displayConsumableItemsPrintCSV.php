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
    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		$filter = ' having (issuedFrom LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR issuedTo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ic.categoryName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR icii.itemQuantity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'issuedFrom';
    
    $orderBy = " $sortField $sortOrderBy";

	$itemConsumableRecordArray = $itemsManager->getCosumableList($filter,'',$orderBy);
    
	$recordCount = count($itemConsumableRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Issued From,Item Category,Item Name,Quantity,Issued To";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $itemConsumableRecordArray[$i]['issuedFrom'].",";
		  $csvData .= $itemConsumableRecordArray[$i]['categoryName'].",";
		  $csvData .= $itemConsumableRecordArray[$i]['itemName'].",";
		  $csvData .= $itemConsumableRecordArray[$i]['itemQuantity'].",";
		  $csvData .= $itemConsumableRecordArray[$i]['issuedTo'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'ConsumableReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>