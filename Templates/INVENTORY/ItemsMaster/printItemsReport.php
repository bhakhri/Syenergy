 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
    $itemsManager = ItemsManager::getInstance();

	
    $conditions = ''; 
	$search=$REQUEST_DATA['searchbox'];
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
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryCode';
    
    $orderBy = " $sortField $sortOrderBy";
     
	$itemRecordArray = $itemsManager->getItemList($filter,'',$orderBy);

	$recordCount = count($itemRecordArray);
	
	if($recordCount >0 && is_array($itemRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$itemRecordArray[$i]['units']=$UnitOfMeasurementArray[$itemRecordArray[$i]['units']];
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$itemRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Items Description');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['categoryCode']	=    array('Category Code',' width=15% align="left" ','align="left" ');
    $reportTableHead['categoryName']	=    array('Category Name',' width="15%" align="left" ','align="left"');
	$reportTableHead['itemName']		=    array('Item Name',' width="15%" align="left" ','align="left"');
    $reportTableHead['itemCode']		=    array('Item Code',' width="15%" align="left" ','align="left"');
    $reportTableHead['reOrderLevel']	=    array('Re-order Level',' width="15%" align="right" ','align="right"');
	$reportTableHead['units']			=    array('Unit',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
