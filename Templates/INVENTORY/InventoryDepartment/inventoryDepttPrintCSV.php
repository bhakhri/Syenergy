 <?php 
//This file is used as printing version for display Inventory Deptt/Store.
//
// Author :Jaineesh
// Created on : 01.06.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
     require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
    $inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='issue/transfer') {
           $type=1;
		}
		elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issuing authority') {
           $type=2;
		}
		elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='end user') {
           $type=3;
		}
		else {
		   $type=-1;
		}

		$filter = ' AND (invd.invDepttName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.invDepttAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR invd.depttType LIKE "'.$type.'" OR emp.employeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'invDepttName';
    
    $orderBy = " $sortField $sortOrderBy";

	$inventoryDepartmentArray = $inventoryDeptartmentManager->getInventoryDepartmentList($filter,$orderBy,'');
	//print_r($inventoryDepartmentArray);
	//die('line'.__LINE__);
    
	$recordCount = count($inventoryDepartmentArray);

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Deptt./Store Name,Abbreviation,Type,Incharge";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $inventoryDepartmentArray[$i]['invDepttName'].",";
		  $csvData .= $inventoryDepartmentArray[$i]['invDepttAbbr'].",";
		  $csvData .= $inventoryDepartmentArray[$i]['departmentType'].",";
		  $csvData .= $inventoryDepartmentArray[$i]['employeeName'].",";
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'InventoryDepttReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>