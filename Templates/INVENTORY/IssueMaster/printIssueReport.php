 <?php 
//---------------------------------------------------------------
//This file is used as printing version for Issue Items.
//
// Author :Jaineesh
// Created on : 31-May-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------
 
?>

<?php
   
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    $conditions = ''; 
	$search=$REQUEST_DATA['searchbox'];
    require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
    $issueManager = IssueManager::getInstance();

    /////////////////////////
    
    /// Search filter /////  

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" OR irm.approvedOn LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
	$approvedItemsRecordArray  = $issueManager->getApprovedItemsList($filter,$limit,$orderBy);

    $cnt = count($approvedItemsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$approvedItemsRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($approvedItemsRecordArray[$i]['requisitionDate']);
		$approvedItemsRecordArray[$i]['approvedOn'] = UtilityManager::formatDate($approvedItemsRecordArray[$i]['approvedOn']);
		
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$approvedItemsRecordArray[$i]);
    }

    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Issue Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['requisitionNo']	=    array('Requisition No.',' width=15% align="left" ','align="left" ');
    $reportTableHead['requisitionDate']	=    array('Requisition Date',' width="15%" align="left" ','align="left"');
	$reportTableHead['employeeName']	=    array('Approved By',' width="15%" align="left" ','align="left"');
    $reportTableHead['approvedOn']		=    array('Approved On',' width="15%" align="left" ','align="left"');
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>