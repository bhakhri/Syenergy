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

   require_once(INVENTORY_MODEL_PATH . "/GRNManager.inc.php");
    $grnManager = GRNManager::getInstance();

    /////////////////////////
    $search=$REQUEST_DATA['searchbox'];
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (ip.partyCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR igm.billDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'partyCode';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    //$totalArray = $requisitionManager->getApprovedRequisitionList($filter,'',$orderBy);
	//$count = count($totalArray);
	$grnRecordArray  = $grnManager->getGRNList($filter,'',$orderBy);

    $cnt = count($grnRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
		$grnRecordArray[$i]['billDate'] = UtilityManager::formatDate($grnRecordArray[$i]['billDate']);
		
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$grnRecordArray[$i]);
		}
	
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Recieve Good');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['partyCode']		=    array('Party Code',' width=20% align="left" ','align="left" ');
    $reportTableHead['billNo']			=    array('Bill No.',' width="20%" align="center" ','align="center"');
	$reportTableHead['billDate']		=    array('Date',' width="15%" align="center" ','align="center"');
	
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
