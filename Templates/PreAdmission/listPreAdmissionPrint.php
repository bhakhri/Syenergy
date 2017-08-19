<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PreAdmissionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

       
    require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
    $preAdmissionManager = PreAdmissionManager::getInstance();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
	$conditionsArray = array();
	$qryString = "";
    

    $listShowArray = array();
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'admissionStatus';
    $orderBy = " $sortField $sortOrderBy";         

    
    $filter='';
    $preAdmissionRecordArray = $preAdmissionManager->getPreAdmissionList($filter,$orderBy);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
      // add stateId in actionId to populate edit/delete icons in User Interface 
  	  $valueArray[] = array_merge(array('srNo' => ($i+1)),$preAdmissionRecordArray[$i]);
    }

    $search = $REQUEST_DATA['searchbox'];
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Student Registration Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						   =  array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				   =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['subjectName']            =  array('Subject Name','width=25% align="left"', 'align="left"');
	$reportTableHead['subjectCode']		       =  array('Subject Code','width=15% align="left"', 'align="left"');
	$reportTableHead['subjectAbbreviation']    =  array('Abbr.','width="15%" align="left" ', 'align="left"');
    $reportTableHead['subjectTypeName']        =  array('Subject Type','width="10%" align="left" ', 'align="left"');
    $reportTableHead['categoryName']           =  array('Subject Category','width=15% align="left"', 'align="left"');
    $reportTableHead['hasAttendance']          =  array('Attendance','width=12% align="center"', 'align="center"'); 
    $reportTableHead['hasMarks']               =  array('Marks','width=12% align="center"', 'align="center"'); 
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
?>