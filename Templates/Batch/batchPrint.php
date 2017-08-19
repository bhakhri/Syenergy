 <?php 
//This file is used as printing version for Country Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    
    define('MODULE','BatchMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();

  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/BatchManager.inc.php");
    $batchManager = batchManager::getInstance();

    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (bat.batchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR studentId LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.startDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.endDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.batchYear LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'batchName';
    $orderBy = " $sortField $sortOrderBy";
    
    $batchRecordArray = $batchManager->getBatchList($filter,'',$orderBy);
    $recordCount = count($batchRecordArray); 
     if($recordCount >0 && is_array($batchRecordArray) ) {
        for($i=0; $i<$recordCount; $i++ ){
            $batchRecordArray[$i]['startDate']  = UtilityManager::formatDate($batchRecordArray[$i]['startDate']);
            $batchRecordArray[$i]['endDate']  = UtilityManager::formatDate($batchRecordArray[$i]['endDate']);
            $batchRecordArray[$i]['srNo']=$i+1;
        } 
    }   
                    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Batch Report');
    $reportManager->setReportInformation("SearchBy: ".$REQUEST_DATA['searchbox']);

    $reportTableHead                        =    array();
                    //associated key                   col.label,        col. width,   data align        
    $reportTableHead['srNo']                =    array('#',             'width="4%"    align="left"',   "align='left' ");
    $reportTableHead['batchName']           =    array('Name',          'width=30%     align="left"',   'align="left" ');
    $reportTableHead['studentId']           =    array('Student',       'width=10%     align="right"',   'align="right" '); 
    $reportTableHead['startDate']           =    array('Start Date',    'width="20%"   align="center"', 'align="center"');
    $reportTableHead['endDate']             =    array('End Date',      'width="20%"   align="center"', 'align="center"');
    $reportTableHead['batchYear']           =    array('Batch Year',    'width="30%"   align="center"',   'align="center"');  

    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $batchRecordArray);
    $reportManager->showReport(); 
?> 
<?php
//$History : $
?>
