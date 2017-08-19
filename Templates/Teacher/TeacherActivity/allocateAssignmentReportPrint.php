<?php
//--------------------------------------------------------
// This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    $search=trim($REQUEST_DATA['search']);
    if($search!='') {
       $isVisible=-1;
       if(strtoupper($search)=='YES'){
           $isVisible=1;
       }
       else if(strtoupper($search)=='NO'){
           $isVisible=0;
       }
       else{
           $isVisible=-1;
       }
       $search=add_slashes($search);
       $filter = ' HAVING ( aa.topicTitle LIKE "%'.$search.'%" OR aa.topicDescription LIKE "%'.$search.'%" OR totalAssignment LIKE "%'.$search.'%" OR isVisible2 LIKE "'.$isVisible.'%" )';         
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'assignedOn';

    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray =$teacherManager->getTeacherAssignmentList($filter,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['assignedOn'] = UtilityManager::formatDate($recordArray[$i]['assignedOn']);
        $recordArray[$i]['tobeSubmittedOn'] = UtilityManager::formatDate($recordArray[$i]['tobeSubmittedOn']);
        $recordArray[$i]['addedOn'] = UtilityManager::formatDate($recordArray[$i]['addedOn']);
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Allocate Assignment Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =   array('#','width="2%"', "align='center' ");
    $reportTableHead['topicTitle']       =   array('Topic','width=15% align="left"', 'align="left"');
	$reportTableHead['topicDescription'] =   array('Description','width=30% align="left"', 'align="left"');
	$reportTableHead['assignedOn']		 =   array('Assigned','width="7%" align="center" ', 'align="center"');
    $reportTableHead['tobeSubmittedOn']  =   array('Due Date','width="8%" align="center" ', 'align="center"');
    $reportTableHead['addedOn']          =   array('Added','width="6%" align="center" ', 'align="center"');
    $reportTableHead['totalAssignment']  =   array('Total','width="6%" align="right" ', 'align="right"');
    $reportTableHead['isVisible']        =   array('Visible','width="7%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>