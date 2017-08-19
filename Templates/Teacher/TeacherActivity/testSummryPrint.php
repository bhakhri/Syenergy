<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';

    $orderBy=" $sortField $sortOrderBy"; 

	
    $testRecordArray = $studentManager->getTeacherTestsSummery($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId'],$orderBy);
    $cnt = count($testRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$testRecordArray[$i]['testDate']=UtilityManager::formatDate($testRecordArray[$i]['testDate']);
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$testRecordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Test Summary Report');
    $reportManager->setReportInformation("TimeTable: $REQUEST_DATA[timeTableName] Class: $REQUEST_DATA[className] Subject: $REQUEST_DATA[subjectName]  Group: $REQUEST_DATA[groupName] <br> Total Tests Conducted : $cnt");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%"', "align='center' ");
	$reportTableHead['className']           =   array('Class','width=12% align="left"', 'align="left"');
    $reportTableHead['subjectCode']         =   array('Subject','width=15% align="left"', 'align="left"');
    $reportTableHead['groupName']           =   array('Group','width=6% align="left"', 'align="left"');
	$reportTableHead['testTopic']			=	array('Topic','width="10%" align="left" ', 'align="left"');
	$reportTableHead['testTypeName']		=	array('TestType','width="8%" align="left"', 'align="left"');
	$reportTableHead['maxMarks']		    =	array('Max Marks','width="8%" align="right"','align="right"');
	$reportTableHead['testDate']		    =	array('Date','width="6%" align="center"','align="center"');
	
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testSummryPrint.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:14
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
//
//*****************  Version 4  *****************
//User: Administrator Date: 28/05/09   Time: 10:49
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Rename "Subject" to "Course" in all modules(display+print) of teacher
//login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/04/09   Time: 11:28
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Modified Test Summary report for teachers
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/04/09   Time: 18:51
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected spelling mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/04/09   Time: 15:44
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created "Display Test Summery"
?>