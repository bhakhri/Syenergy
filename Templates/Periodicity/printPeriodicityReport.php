 <?php 
//This file is used as printing version for group.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
	$periodicityManager = PeriodicityManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'WHERE  (periodicityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityFrequency LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodicityName';
    
    $orderBy = " $sortField $sortOrderBy";

	$periodicityRecordArray = $periodicityManager->getPeriodicityList($filter,'',$orderBy);
    
	$recordCount = count($periodicityRecordArray);
                            
                            //$designationPrintArray[] =  Array();
                            if($recordCount >0 && is_array($periodicityRecordArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$periodicityRecordArray[$i]);
								
                                }
                            }
     
    $search = $REQUEST_DATA['searchbox'];                            
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Periodicity Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead							=    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']					=    array('#',                'width="2%" align="left"', "align='left'");
    $reportTableHead['periodicityName']			=    array('Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['periodicityCode']			=    array('Abbr.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['periodicityFrequency']	=    array('Annual Frequency',        ' width="15%" align="right" ','align="right"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
