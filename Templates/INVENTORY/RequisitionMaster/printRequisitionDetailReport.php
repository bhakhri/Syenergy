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

    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();

	
    $conditions = '';
	$requisitionId = $REQUEST_DATA['requisitionId'];

   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='approved') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled') {
           $type=3;
       }
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='issued') {
           $type=4;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' AND (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ic.categoryCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR im.itemCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irt.quantityRequired LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irt.requisitionStatus LIKE "'.$type.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryCode';
    
    $orderBy = " $sortField $sortOrderBy";
    
	$conditions = "AND irt.requisitionId=".$requisitionId;
	$requisitionDetailRecordArray  = $requisitionManager->getRequisitionDetailList($conditions);
	$requisitionNo = $requisitionDetailRecordArray[0]['requisitionNo'];
	$recordCount = count($requisitionDetailRecordArray);
	
	if($recordCount >0 && is_array($requisitionDetailRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$requisitionDetailRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Requisition Report');
	$reportManager->setReportInformation("Search By : $requisitionNo");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['categoryName']		=    array('Category Name',' width=15% align="left" ','align="left" ');
    $reportTableHead['itemName']			=    array('Item Name',' width="15%" align="center" ','align="center"');
    $reportTableHead['quantityRequired']	=    array('Quantity Required',' width="15%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>