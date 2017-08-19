<?php 
//This file is used as printing version for Cleaning room.
//
// Author :Jaineesh
// Created on : 16.06.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
    $cleaningRoomManager = CleaningRoomManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' AND (hs.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR et.tempEmployeeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';        
    }
    
    // to limit records per page
    

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tempEmployeeName';

     
    $orderBy = "$sortField $sortOrderBy";

	$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningRoomDetailList($conditions,'',$orderBy);
    $cnt = count($cleaningRoomRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $cleaningRoomRecordArray[$i]['Dated'] = UtilityManager::FormatDate($cleaningRoomRecordArray[$i]['Dated']);

        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$cleaningRoomRecordArray[$i]);
   }

    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Cleaning Report');
	if ($search != '') {
		$reportManager->setReportInformation("SearchBy: $search");
	}
     
    $reportTableHead                        =    array();
    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']						=   array('#','width="3%" align="left"', 'align="left" ');
    $reportTableHead['hostelName']					=   array('Hostel Name','width=12% align="left"', 'align="left"');
    $reportTableHead['Dated']						=   array('Date','width="8%" align="left" ', 'align="left"');
    $reportTableHead['tempEmployeeName']			=   array('Employee Name','width="10%" align="left" ', 'align="left"');
    $reportTableHead['toiletsCleaned']				=   array('Toilet(s) Cleaned','width="8%" align="right" ', 'align="right"');
    $reportTableHead['noOfRoomsCleaned']            =   array('Room(s) Cleaned','width="10%" align="right" ', 'align="right"');
    $reportTableHead['attachedRoomToiletsCleaned']  =   array('Attached Room Toilet(s) Cleaned','width="8%" align="right" ', 'align="right"');
	$reportTableHead['dryMoppingInSqMeter']         =   array('Dry Mopping','width="8%" align="right" ', 'align="right"');
	$reportTableHead['wetMoppingInSqMeter']         =   array('Wet Mopping','width="8%" align="right" ', 'align="right"');
	$reportTableHead['roadCleanedInSqMeter']        =   array('Road Cleaned','width="8%" align="right" ', 'align="right"');
	$reportTableHead['noOfGarbageBinsDisposal']     =   array('Garbage Disposal','width="8%" align="right" ', 'align="right"');
	$reportTableHead['noOfHoursWorked']				=   array('Working hrs.','width="8%" align="right" ', 'align="right"');

    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();


?>