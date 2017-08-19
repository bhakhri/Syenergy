 <?php 
//This file is used as printing version for tyre retreading
//
// Author :Jaineesh
// Created on : 05-Jan-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();
	
	$search = $REQUEST_DATA['searchbox'];
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (tm.tyreNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.retreadingDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.totalRun LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
    $orderBy = " $sortField $sortOrderBy";

	$tyreRetreadingRecordArray = $tyreRetreadingManager->getTyreRetreadingList($filter,$limit,$orderBy);
    $cnt = count($tyreRetreadingRecordArray);

				
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$tyreRetreadingRecordArray[$i]['retreadingDate'] = UtilityManager::formatDate($tyreRetreadingRecordArray[$i]['retreadingDate']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$tyreRetreadingRecordArray[$i]);
     }
     
     //print_r($recordArray);
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Tyre Retreading');
    $reportManager->setReportInformation("Search By: $search");

    $reportTableHead						=    array();
    $reportTableHead['srNo']				=    array('#', 'width="4%" align="left"', "align='left'");
    $reportTableHead['tyreNumber']			=    array('Tyre No.',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['busNo']				=    array('Registration No.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['retreadingDate']		=    array('Retreading Date',        ' width="10%" align="center" ','align="center"');
	$reportTableHead['totalRun']			=    array('Reading',        ' width="10%" align="right" ','align="right"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
