<?php 
//This file is used as printing version for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");

	 
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonAttendanceArr = CommonQueryManager::getInstance();
	 

    $studentId= $REQUEST_DATA['studentId'];
	$classId= $REQUEST_DATA['classId'];

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'postedDate';

	if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
    $orderBy = " $sortField $sortOrderBy";  
     
    $resourceRecordArray = $studentManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,' ');
    $cnt = count($resourceRecordArray);
    
       

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		$resourceRecordArray[$i]['postedDate'] = UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);
		$resourceRecordArray[$i]['resourceUrl']=strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? NOT_APPLICABLE_STRING:$resourceRecordArray[$i]['resourceUrl'];
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$resourceRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Resource Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                        =    array();
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['subject']				=    array('Subject Code',' width=6% align="left" ','align="left" ');
	$reportTableHead['description']			=    array('Description',' width=15% align="left" ','align="left" ');
	$reportTableHead['resourceName']		=    array('Type',' width=10% align="left" ','align="left" ');
	$reportTableHead['postedDate']			=    array('Date',' width=8% align="left" ','align="left" ');
	$reportTableHead['resourceUrl']		    =    array('Link',' width=10% align="left" ','align="left" ');
	//$reportTableHead['attachmentLink']	=    array('Attachment',' width=10% align="left" ','align="left" ');
	$reportTableHead['employeeName']		=    array('Creator',' width="10%" align="left" ','align="left"');
	//$reportTableHead['stateName']			=    array('State Name',        ' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
	
	
?>