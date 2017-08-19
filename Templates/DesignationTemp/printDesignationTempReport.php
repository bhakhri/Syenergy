 <?php 
//This file is used as printing version for designations.
//
// Author :Gurkeerat Sidhu
// Created on : 29-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TemporaryDesignationMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DesignationTempManager.inc.php");
    $designationTempManager = DesignationTempManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter ///// 
    $search=$REQUEST_DATA['searchbox'];
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (designationName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR designationCode LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'designationName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $designationTempManager->getTotalDesignation($filter);
    $designationRecordArray = $designationTempManager->getDesignationList($filter,'',$orderBy);
    $cnt = count($designationRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$designationRecordArray[$i]);
    }
                           
    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Temporary Designation Report');
    $reportManager->setReportInformation("SearchBy: $search");
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']                =    array('#',                'width="4%" align="left"', 'align="left"');
    $reportTableHead['designationName']     =    array('Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['designationCode']     =    array('Designation Code',        ' width="15%" align="left" ','align="left"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 


?>
