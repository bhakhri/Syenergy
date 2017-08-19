<?php 
//This file is used as printing version for blocks.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
    $employeeLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    define('MODULE','EmployeeEmployeeLeaveSetMapping');  
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    
    //search filter
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ls.leaveSetName  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $filter .=" AND s.active=1";
    $totalArray = $employeeLeaveSetMappingManager->getTotalEmployeeLeaveSetMapping($filter);
    $empLeaveSetMappingRecordArray = $employeeLeaveSetMappingManager->getEmployeeLeaveSetMappingList($filter,'',$orderBy);
    $cnt = count($empLeaveSetMappingRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeCode'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeName'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $valueArray[] = array_merge(array('action' => $empLeaveSetMappingRecordArray[$i]['employeeLeaveSetMappingId'] , 
                                          'srNo' => ($records+$i+1) ),$empLeaveSetMappingRecordArray[$i]);
    }
   
 
    $search = trim($REQUEST_DATA['searchbox']);  

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Employee Leave Set Mapping Report');
    $reportManager->setReportInformation("SearchBy:&nbsp;$search");
    
   
	$reportTableHead				   = array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		   =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['employeeCode']   =  array('Employee Code','width=20% align="left"', 'align="left"');
    $reportTableHead['employeeName']   =  array('Employee Name','width=20% align="left"', 'align="left"');
    $reportTableHead['leaveSetName']   =  array('Leave Set','width=20% align="left"', 'align="left"');
    $reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

