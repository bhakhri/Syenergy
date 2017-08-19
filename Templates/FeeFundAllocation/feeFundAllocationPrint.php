 <?php 
//This file is used as printing version for fee fund allocation Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
    $feeFundAllocationManager = FeeFundAllocationManager::getInstance();

    define('MODULE','FundAllocationMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
                         
    
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (allocationEntity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR entityType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'allocationEntity';
    
    $orderBy = " $sortField $sortOrderBy";   
    
    //$totalArray = $sectionManager->getTotalSection(); 
    //$totalArray = $feeFundAllocationManager->getTotalFeeFundAllocation($filter);
    //$feeFundAllocationRecordArray = $feeFundAllocationManager->getFeeFundAllocationList();
    $feeFundAllocationRecordArray = $feeFundAllocationManager->getFeeFundAllocationList($filter,'',$orderBy);    
  
    $recordCount = count($feeFundAllocationRecordArray); 
     if($recordCount >0 && is_array($feeFundAllocationRecordArray) ) {
        for($i=0; $i<$recordCount; $i++ ){
            $feeFundAllocationRecordArray[$i]['srNo']=$i+1;
        } 
    }   
    //echo "hi" ;          
    
        
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fund Allocation Report');
    //$reportManager->setReportInformation("$className2 ");
    $reportManager->setReportInformation("SearchBy:&nbsp;".$REQUEST_DATA['searchbox']);

    $reportTableHead                        =    array();
                    //associated key                  col.label,                col. width,                         data align        
    $reportTableHead['srNo']                =    array('#',                     'width="2%" align="left"',        'align="left"');
    $reportTableHead['allocationEntity']    =    array('Allocation Entity ',    ' width=36% align="left" ',       'align="left"');
    $reportTableHead['entityType']          =    array('Abbr.',                  ' width="20%" align="left" ',     'align="left"');
  
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $feeFundAllocationRecordArray);
    $reportManager->showReport(); 

//$History : $
?>
