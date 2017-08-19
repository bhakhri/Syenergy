 <?php 
//This file is used as printing version for session.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/SessionsManager.inc.php");
	$sessionsManager = SessionsManager::getInstance();
	
    define('MODULE','COMMNO');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);

    
    
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
         $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sessionYear LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sessionName';
    
    $orderBy = " $sortField $sortOrderBy";

	$sessionRecordArray = $sessionsManager->getSessionList($filter,'',$orderBy);
    
	$recordCount = count($sessionRecordArray);
                            
                            //$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($sessionRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';
			$sessionRecordArray[$i]['startDate'] =strip_slashes($sessionRecordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($sessionRecordArray[$i]['startDate']);

			$sessionRecordArray[$i]['endDate'] = strip_slashes($sessionRecordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['endDate']);
		   
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$sessionRecordArray[$i]);
		
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Session Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead							=    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']					=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['sessionName']				=    array('Session Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['sessionYear']				=    array('Session Year',        ' width="15%" align="center" ','align="center"');
	$reportTableHead['abbreviation']			=    array('Abbr.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['startDate']				=    array('Start Date',        ' width="15%" align="center" ','align="center"');
	$reportTableHead['endDate']					=    array('End Date',        ' width="15%" align="center" ','align="center"');
	$reportTableHead['active']					=    array('Active',        ' width="15%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
