 <?php 
//This file is used as printing version for Consumable Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
	$requisitionManager = RequisitionManager::getInstance();

	
    $conditions = ''; 

   
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
	   elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelledbyhod') {
           $type=5;
       }
	   else {
		   $type=-1;
	   }

      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionStatus LIKE "'.$type.'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

     
	
	
	
	//if($recordCount >0 && is_array($requisitionRecordArray) ) { 

    ////////////
	    
    $totalArray = $requisitionManager->getRequisitionList($filter,'',$orderBy);
	$count = count($totalArray);
	$requisitionRecordArray  = $requisitionManager->getRequisitionList($filter,$orderBy);

    $cnt = count($requisitionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$requisitionRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($requisitionRecordArray[$i]['requisitionDate']);  
		$requisitionRecordArray[$i]['requisitionStatus'] = $requisitionStatusArray[$requisitionRecordArray[$i]['requisitionStatus']];
		$requisitionRecordArray[$i]['totalCount'] = "<a href='#' title='Show Detail' onclick='printRequisitionReport(".$requisitionRecordArray[$i]['requisitionId'].")'>".$requisitionRecordArray[$i]['totalCount']."</a>";

        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$requisitionRecordArray[$i]);
	}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Requisition Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['requisitionNo']		=    array('Requisition No.',' width=20% align="left" ','align="left" ');
    $reportTableHead['requisitionDate']		=    array('Requisition Date',' width="20%" align="center" ','align="center"');
	$reportTableHead['requisitionStatus']	=    array('Status',' width="15%" align="left" ','align="left"');
	$reportTableHead['totalCount']			=    array('Items Count',' width="10%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>