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
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','IssueConsumableItems');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/IssueItemsManager.inc.php");
    $itemsManager = IssueItemsManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		$filter = ' having (issuedFrom LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR issuedTo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ic.categoryName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR im.itemName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR icii.itemQuantity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'issuedFrom';
    
     $orderBy = " $sortField $sortOrderBy";
     
	$itemConsumableRecordArray = $itemsManager->getCosumableList($filter,'',$orderBy);

		$recordCount = count($itemConsumableRecordArray);
		
		$hostelPrintArray[] =  Array();
		if($recordCount >0 && is_array($itemConsumableRecordArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$itemConsumableRecordArray[$i]);
			}
		}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Issue Consumable Items Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['issuedFrom']			=    array('Issued From',' width=15% align="left" ','align="left" ');
    $reportTableHead['categoryName']			=    array('Item Category',' width="15%" align="left" ','align="left"');
	$reportTableHead['itemName']			=    array('Item Name',' width="15%" align="left" ','align="left"');
    $reportTableHead['itemQuantity']          =    array('Quantity',' width="15%" align="right" ','align="right"');
    $reportTableHead['issuedTo']           =    array('Issued To',' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
//*****************  Version 1  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Template/Hostel
//added new fields (floorTotal,hostelType,totalCapacity) 
?>
