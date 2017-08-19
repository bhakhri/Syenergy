 <?php 
//This file is used as printing version for Generate PO.
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

   require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
    $poManager = POManager::getInstance();

    /////////////////////////
    $search=$REQUEST_DATA['searchbox']; 
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='pending'){
			$type = 0;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='approved'){
			$type = 1;
		}
		else if(strtolower(trim($REQUEST_DATA['searchbox']))=='cancelled'){
			$type = 2;
		}
		else{
			$type= -1;
		}

		$filter = ' HAVING (ipm.poNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ipm.poDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%"
		OR status LIKE "%'.add_slashes(trim($type)).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'poNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    //$totalArray = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	$poRecordArray  = $poManager->getGeneratedPo($filter,'',$orderBy);

    $cnt = count($poRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$poRecordArray[$i]['poDate'] = UtilityManager::formatDate($poRecordArray[$i]['poDate']);  
		$poRecordArray[$i]['status'] = $poStatusArray[$poRecordArray[$i]['status']];
		
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$poRecordArray[$i]);
		}
	
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Generate PO Report');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['poNo']			=    array('PO No.',' width=20% align="left" ','align="left" ');
    $reportTableHead['poDate']			=    array('Date',' width="20%" align="center" ','align="center"');
	$reportTableHead['status']			=	 array('Status','width="20%"  align="left"','align="left"');
	$reportTableHead['userName']		=    array('User Name',' width="15%" align="left" ','align="left"');
	
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>