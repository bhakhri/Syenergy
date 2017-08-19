<?php
//This file is used as printing version for test Time wise report.
//
// Author :Arvind Singh Rawat
// Created on : 22-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>
<?php

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DateWiseTestReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();


    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    $toDate= add_slashes($REQUEST_DATA['toDate']);      
    
    $reportHead = "";
    $reportHead .= "Degree:&nbsp;".$REQUEST_DATA['className']."<br>";
    $reportHead .= "Subject:&nbsp;".$REQUEST_DATA['subjectName'].",&nbsp;&nbsp;Group:&nbsp;".$REQUEST_DATA['groupName']."&nbsp;&nbsp;";
    $reportHead .= "Test Type Category:&nbsp;".$REQUEST_DATA['testTypeName']."<br>";
    $reportHead .= "From Date:&nbsp;".UtilityManager::formatDate($fromDate).",&nbsp;To Date:&nbsp;".UtilityManager::formatDate($toDate);

    $classId = add_slashes($REQUEST_DATA['classId']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);
    $groupId = add_slashes($REQUEST_DATA['groupId']);
    $testTypeCategoryId= add_slashes($REQUEST_DATA['testTypeCategoryId']);
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testType';
    
    $orderBy =" $sortField $sortOrderBy";
    
  
    $recordArray = array();
    $conditions = '';
    if(strtolower($classId) != 'all') {
		$conditions.=" AND t.classId='$classId' ";
	}
    if(strtolower($subjectId) != 'all') {
        $conditions.=" AND s.subjectId='$subjectId' ";
    }     
    
    if(strtolower($groupId) != 'all'){
        $conditions.=" AND gr.groupId='$groupId' ";
    }
    
    if(strtolower($testTypeCategoryId) != 'all'){
        $conditions.=" AND t.testTypeCategoryId='$testTypeCategoryId' ";
    }
    
    $conditions.=" AND t.testDate BETWEEN '$fromDate' AND '$toDate' ";

    
    $recordArray = $studentReportsManager->getTestRecordNew($conditions,$orderBy);
    $cnt = count($recordArray);
    $valueArray = array();

    for($i=0;$i<$cnt;$i++) {
      $recordArray[$i]['testDate'] = UtilityManager::formatDate($recordArray[$i]['testDate']);
      $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
   }
    

   $reportManager->setReportWidth(800);
   $reportManager->setReportHeading('Date Wise Test Report');
   $reportManager->setReportInformation($reportHead);

   $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
   $reportTableHead['srNo']                =    array('#',               'width="2%" align="left"', "align='left' ");
   $reportTableHead['testType']            =    array('Test Type',       'width="12%" align="left"', 'align="left"');
   $reportTableHead['subjectName']         =    array('Subject Name',    'width="22%" align="left"', 'align="left" ');
   $reportTableHead['subjectCode']         =    array('Subject Code',    'width="12%" align="left"', 'align="left" ');
   $reportTableHead['groupName']           =    array('Group',           'width="12%" align="left"', 'align="left" ');
   $reportTableHead['employeeName']        =    array('Employee',        'width="18%" align="left"', 'align="left" ');
   $reportTableHead['testDate']            =    array('Test Date',       'width="12%" align="center"', 'align="center" ');
   
   $reportManager->setRecordsPerPage(50);
   $reportManager->setReportData($reportTableHead, $valueArray);
   $reportManager->showReport();
   

// $History: datewiseTestReportPrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/24/09   Time: 3:13p
//Updated in $/LeapCC/Templates/StudentReports
//report title updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/24/09   Time: 3:10p
//Updated in $/LeapCC/Templates/StudentReports
//page title name update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/23/09   Time: 5:55p
//Updated in $/LeapCC/Templates/StudentReports
//# field is align correctly 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Templates/StudentReports
//class base format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 4:43p
//Updated in $/LeapCC/Templates/StudentReports
//report format update 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/19/09    Time: 5:22p
//Created in $/LeapCC/Templates/StudentReports
//file added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 2:09p
//Updated in $/Leap/Source/Templates/ScStudentReports
//search for & condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/29/09    Time: 1:20p
//Updated in $/Leap/Source/Templates/ScStudentReports
//issue fix
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/22/08   Time: 5:41p
//Created in $/Leap/Source/Templates/ScStudentReports
//initial checjkin
//

?>
