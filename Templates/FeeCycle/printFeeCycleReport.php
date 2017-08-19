 <?php 
//This file is used as printing version for group.
//
// Author :Jaineesh
// Created on : 13.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
	$feeCycleManager = FeeCycleManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        cycleAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';          
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
     $orderBy = " $sortField $sortOrderBy";

	$feeCycleRecordArray = $feeCycleManager->getFeeCycleList($filter,'',$orderBy);
    
	$recordCount = count($feeCycleRecordArray);
                            
	//$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($feeCycleRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';
			$feeCycleRecordArray[$i]['fromDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['fromDate']);
			$feeCycleRecordArray[$i]['toDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['toDate']);
		   
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$feeCycleRecordArray[$i]);
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Cycle Report');
    $reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['cycleName']			=    array('Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['cycleAbbr']			=    array('Abbr.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['fromDate']			=    array('From',        ' width="10%" align="center" ','align="center"');
	$reportTableHead['toDate']				=    array('To',        ' width="10%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
