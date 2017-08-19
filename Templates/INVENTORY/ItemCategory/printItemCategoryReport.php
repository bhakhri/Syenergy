 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 26 July 10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
   
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
    $itemCategoryManager = ItemCategoryManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
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
	
	if($recordCount >0 && is_array($itemCategoryRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$itemCategoryRecordArray[$i]['categoryType'] = $categoryTypeStatus[$itemCategoryRecordArray[$i]['categoryType']];
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$itemCategoryRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Item Type Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['categoryName']		=    array('Category Name',' width=15% align="left" ','align="left" ');
    $reportTableHead['categoryCode']		=    array('Category Code',' width="15%" align="left" ','align="left"');
	$reportTableHead['categoryType']		=    array('Category Type',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>
