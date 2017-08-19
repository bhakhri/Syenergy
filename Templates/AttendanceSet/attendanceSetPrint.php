<?php 
//This file is used as printing version for blocks.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AttendanceSetManager.inc.php");
    $attendanceSetManager = AttendanceSetManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    define('MODULE','AttendanceSetMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    
   /// Search filter /////  
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $search = trim($REQUEST_DATA['searchbox']);
       if(strtoupper($search)=='PERCENTAGES'){
           $filter =' WHERE at.evaluationCriteriaId='.PERCENTAGES;
       }
       else if(strtoupper($search)=='SLABS'){
           $filter =' WHERE at.evaluationCriteriaId='.SLABS;
       }
       if($filter!=''){
          $filter .= ' OR ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }
       else{
          $filter = ' WHERE ( at.attendanceSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
       }

    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'attendanceSetName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray      = $attendanceSetManager->getTotalAttendanceSet($filter);
    $setRecordArray  = $attendanceSetManager->getAttendanceList($filter,'',$orderBy);
    $cnt = count($setRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($setRecordArray[$i]['evaluationCriteriaId']==PERCENTAGES){
           $setRecordArray[$i]['evaluationCriteriaId']='Percentages';
       }
       else if($setRecordArray[$i]['evaluationCriteriaId']==SLABS){
           $setRecordArray[$i]['evaluationCriteriaId']='Slabs';
       }
       else{
           $setRecordArray[$i]['evaluationCriteriaId']=NOT_APPLICABLE_STRING;
       } 
       $valueArray[] = array_merge(array('action' => $setRecordArray[$i]['attendanceSetId'] , 'srNo' => ($records+$i+1) ),$setRecordArray[$i]);
       
    }
   
 
	$reportManager->setReportWidth(780);
	$reportManager->setReportHeading('Attendance Set Master Report');
    $reportManager->setReportInformation("SearchBy: $search");
   
	$reportTableHead				   = array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		            =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['attendanceSetName']       =  array('Set Name','width=30% align="left"', 'align="left"');
    $reportTableHead['evaluationCriteriaId']    =  array('Criteria','width=20% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>