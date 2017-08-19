<?php
//This file is used as printing version for Country Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
    $feeCycleFineManager =FeeCycleFineManager::getInstance();

    /////////////////////////
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);  
        
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter /////  
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (fc.cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineAmount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';        
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
    $orderBy = "$sortField $sortOrderBy";         

    
	$feeCycleFineRecordArray = $feeCycleFineManager->getFeeCycleFineListPrint($filter,'',$orderBy);
    $cnt = count($feeCycleFineRecordArray);
    
	 for($i=0;$i<$cnt;$i++) {
       $feeCycleFineRecordArray[$i]['fromDate']=UtilityManager::formatDate($feeCycleFineRecordArray[$i]['fromDate']);
	   $feeCycleFineRecordArray[$i]['toDate']=UtilityManager::formatDate($feeCycleFineRecordArray[$i]['toDate']);
		// add feeCycleFineId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array( 'srNo' => ($records+$i+1) ),$feeCycleFineRecordArray[$i]);
    }

    $search = $REQUEST_DATA['searchbox'];    
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Cycle Fine List ');
    $reportManager->setReportInformation("SearchBy: $search");  
    
    $reportTableHead                        =    array();
                    //associated key                  col.label,          col. width,      data align        
    $reportTableHead['srNo']                =    array('#',             ' width="3%" align="left"', "align='left' ");
    $reportTableHead['cycleName']           =    array('Fee Cycle',     ' width=20% align="left" ','align="left" ');
   // $reportTableHead['headName']          =    array('Fee Head  ',    ' width="20%" align="left" ','align="left"');
    $reportTableHead['fromDate']            =    array('From',          ' width="10%" align="center" ','align="center"'); 
    $reportTableHead['toDate']              =    array('To',            ' width="10%" align="center" ','align="center"'); 
    $reportTableHead['fineAmount']          =    array('Fine Amount',   ' width="20%" align="right" ','align="right"'); 
    $reportTableHead['fineType']            =    array('Fine Type',     ' width="15%" align="center" ','align="center"'); 

    
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead,$valueArray);
    $reportManager->showReport(); 

//$History : $
?>
