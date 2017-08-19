 <?php 
//This file is used as printing version for period slot.
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

    require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
    $periodSlotManager = PeriodSlotManager::getInstance();
	
	/// Search filter /////  
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
        }
        elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
        }
	    else {
		   $type=-1;
	    }

        $filter = ' WHERE ( slotName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR slotName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isActive LIKE "'.$type.'")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
    $orderBy = " $sortField $sortOrderBy";

	$periodSlotRecordArray = $periodSlotManager->getPeriodSlotDetail($filter,'',$orderBy);
    
	$recordCount = count($periodSlotRecordArray);
                            
                            //$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($periodSlotRecordArray) ) { 
		
		for($i=0; $i<$recordCount; $i++ ) {
			
			$bg = $bg =='row0' ? 'row1' : 'row0';
		   
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$periodSlotRecordArray[$i]);
		
		}
	}
                           
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Period Slot Report');
	$reportManager->setReportInformation("SearchBy: $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="2%" align="left"', "align='left'");
    $reportTableHead['slotName']			=    array('Slot Name',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['slotAbbr']			=    array('Abbr.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['isActive']			=    array('Active',        ' width="10%" align="center" ','align="center"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
