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
    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();
     
	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='kilogram') {
           $type=0;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='litre') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='number') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }

    	$filter = ' AND (ic.categoryCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR ic.categoryName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.reOrderLevel LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR im.units LIKE "%'.$type.'%")';         
    	$search = $REQUEST_DATA['searchbox'];
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'itemCode';
    
    $orderBy = " $sortField $sortOrderBy";

	$itemRecordArray = $itemsManager->getItemList($filter,'',$orderBy);

	$recordCount = count($itemRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData.="Search by : ".$REQUEST_DATA['searchbox']."\n";
    $csvData.="Sr No.,Category Code,Category Name,Item Name,Item Code,Re-order Level,Unit";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		$itemRecordArray[$i]['units']=$UnitOfMeasurementArray[$itemRecordArray[$i]['units']];
		$itemRecordArray[$i]['packaging']=$packagingArray[$itemRecordArray[$i]['packaging']];
		$itemRecordArray[$i]['itemType']=$itemTypeArr[$itemRecordArray[$i]['itemType']];
		$csvData .= ($i+1).",";
		$csvData .= $itemRecordArray[$i]['categoryCode'].",";
		$csvData .= $itemRecordArray[$i]['categoryName'].",";
		$csvData .= $itemRecordArray[$i]['itemName'].",";
		$csvData .= $itemRecordArray[$i]['itemCode'].",";
		$csvData .= $itemRecordArray[$i]['reOrderLevel'].",";
		$csvData .= $itemRecordArray[$i]['units'].",";
		$csvData .= "\n";
  }
   if($recordCount == 0){
		$csvData .=" No Data Found ";
	}
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'ItemsReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>