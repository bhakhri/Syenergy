 <?php 
//---------------------------------------------------------------
//This file is used as printing version for Approved Requisition.
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
    require_once(INVENTORY_MODEL_PATH . "/RequisitionManager.inc.php");
    $requisitionManager = RequisitionManager::getInstance();

    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (irm.requisitionNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR irm.requisitionDate LIKE "%'.date("Y-m-d", strtotime(trim($REQUEST_DATA['searchbox']))).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'requisitionNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    //$totalArray = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	$requisitionApprovedRecordArray  = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
    $cnt = count($requisitionApprovedRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$requisitionApprovedRecordArray[$i]['requisitionDate'] = UtilityManager::formatDate($requisitionApprovedRecordArray[$i]['requisitionDate']);  

        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$requisitionApprovedRecordArray[$i]);
    }
	

    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Approved Requisition Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['requisitionNo']	=    array('Requisition No.',' width=15% align="left" ','align="left" ');
    $reportTableHead['requisitionDate']	=    array('Requisition Date',' width="15%" align="left" ','align="left"');
	$reportTableHead['userName']		=    array('User',' width="15%" align="left" ','align="left"');
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>