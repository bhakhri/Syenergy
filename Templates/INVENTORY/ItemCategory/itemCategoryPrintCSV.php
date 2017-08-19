 <?php 
//This file is used as printing version for display Inventory Deptt/Store.
//
// Author :Jaineesh
// Created on : 26 July 10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
    $itemCategoryManager = ItemCategoryManager::getInstance();
    
	function parseCSVComments($comments) {
		 $comments = str_replace('"', '""', $comments);
		 $comments = str_ireplace('<br/>', "\n", $comments);
		 if(eregi(",", $comments) or eregi("\n", $comments)) {
		   return '"'.$comments.'"'; 
		 } 
		 else {
			return $comments; 
		 }
	}

    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
	   if(strtolower(trim($REQUEST_DATA['searchbox']))=='consumable') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='non-consumable') {
           $type=2;
       }
	   else {
		   $type=-1;
	   }
       $filter = ' WHERE (categoryName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR categoryCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR categoryType LIKE "'.$type.'%" )';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
    $orderBy = " $sortField $sortOrderBy";

	$itemCategoryRecordArray = $itemCategoryManager->getItemCategoryList( $filter, $orderBy, '');
    
	$recordCount = count($itemCategoryRecordArray);

    $valueArray = array();

    $csvData ='';
	$csvData.="Search by : ".$REQUEST_DATA['searchbox']."\n";
    $csvData.="#,Category Name,Category Code,Category Type";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $itemCategoryRecordArray[$i]['categoryName'].",";
		  $csvData .= $itemCategoryRecordArray[$i]['categoryCode'].",";
  		  $csvData .= $categoryTypeStatus[$itemCategoryRecordArray[$i]['categoryType']].",";
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
header('Content-Disposition: attachment;  filename="'.'ItemTypeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
