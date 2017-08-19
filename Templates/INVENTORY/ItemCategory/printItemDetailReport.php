 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ItemCategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/ItemCategoryManager.inc.php");
    $itemCategoryManager = ItemCategoryManager::getInstance();
    
	$itemCategory = $REQUEST_DATA['searchbox'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = " $sortField $sortOrderBy";
    
	if ($itemCategory != ''){
		$itemArray = $itemCategoryManager->getItemList($itemCategory);
	}

	$recordCount = count($itemArray);
	
	$hostelPrintArray[] =  Array();
	if($recordCount >0 && is_array($itemArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$itemArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Item Report ');

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['itemName']			=    array('Item Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['availableQty']		=    array('Quantity',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>