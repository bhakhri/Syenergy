 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/PeriodsManager.inc.php");
    $periodsManager = PeriodsManager::getInstance();

	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (pr.periodNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ps.slotName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
    $orderBy = " $sortField $sortOrderBy"; 

	$periodsArray = $periodsManager->getPeriodsList($filter,'',$orderBy); 
                               
                            $recordCount = count($periodsArray);
                            
                            $periodsPrintArray[] =  Array();
                            if($recordCount >0 && is_array($periodsArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $valueArray[] = array_merge(array('srNo' => ($i+1) ),$periodsArray[$i]);
								
                                }
                            }
                           
    
	$reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Periods Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',					'width="2%" align="left"', "align='left'");
	$reportTableHead['slotName']			=    array('Slot',	'width="15%" align="left"', "align='left'");
    $reportTableHead['periodNumber']		=    array('Period Number ',' width=15% align="left" ','align="left" ');
    $reportTableHead['startTime']			=    array('Start Time',        ' width="15%" align="center" ','align="center"');
	$reportTableHead['endTime']				=    array('End Time',			' width="15%" align="center" ','align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
