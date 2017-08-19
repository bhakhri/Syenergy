<?php 
//This file is used as printing version for TestType.
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn(true);   
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $managementManager = DashBoardManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $degreeId =  add_slashes($REQUEST_DATA['degreeId']);
    $teacherId =  add_slashes($REQUEST_DATA['teacherId']);
    $subjectId =  add_slashes($REQUEST_DATA['subjectId']);
    $groupId =  add_slashes($REQUEST_DATA['groupId']);
    $testTypeCategoryId =  add_slashes($REQUEST_DATA['testTypeCategoryId']);
    
    $condition = '';
    $condition1 = '';
    if($degreeId != '') {
       $condition .= " AND a.classId IN ($degreeId)" ;
       $condition1 .= " AND c.classId IN ($degreeId)" ;
    }
    
    if($teacherId != '') {
       $condition .= " AND a.employeeId  IN ($teacherId)" ;
       $condition1 .= " AND a.employeeId  IN ($teacherId)" ;
    }
    
    if($subjectId != '') {
       $condition .= " AND a.subjectId IN ($subjectId)" ;
       $condition1 .= " AND a.subjectId IN ($subjectId)" ;  
    }
    
    if($groupId != '') {
       $condition .= " AND a.groupId IN ($groupId)" ;
       $condition1 .= " AND a.groupId IN ($groupId)" ;
    }
    
    if($testTypeCategoryId != '') {
       $condition .= " AND c.testTypeCategoryId IN ($testTypeCategoryId)" ;
    }
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    $orderBy = " $sortField $sortOrderBy";         

    
    $activeTimeTableLabelArray = $managementManager->getActiveTimeTable();
    $activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
    $teacherSubjectsArray = $managementManager->getTeacherSubjects($activeTimeTableLabelId,$condition1);
    $concatStr = "'0#0'";
    foreach($teacherSubjectsArray as $teacherSubjectRecord) {
        $subjectId = $teacherSubjectRecord['subjectId'];
        $classId = $teacherSubjectRecord['classId'];
        $employeeId = $teacherSubjectRecord['employeeId']; 
        if ($concatStr != '') {
            $concatStr .= ',';
        }
        $concatStr .= "'$subjectId#$classId'";
    }

    $teacherTestsArray = $managementManager->getTeacherTests($concatStr,$condition,$orderBy);
    $cnt1 = count($teacherTestsArray);

    $valueArray = array();     
    for($i=0;$i<$cnt1;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface  
       $teacherTestsArray[$i]['testDate'] = UtilityManager::formatDate($teacherTestsArray[$i]['testDate']);
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$teacherTestsArray[$i]);
       
       
       
    }
    
    //$search = $REQUEST_DATA['searchbox'];
    $reportManager->setReportWidth(780);
    $reportManager->setReportHeading('Exam Statistics Report');
    $reportManager->setReportInformation("SearchBy: $search");
     
    $reportTableHead                           =  array();
    //associated key                  col.label,            col. width,      data align    
    $reportTableHead['srNo']           =  array('#',        'width="2%" align="left"', "align='left' ");
    $reportTableHead['employeeName']   =  array('Teacher',  'width=12% align="left"', 'align="left"');
    $reportTableHead['className']      =  array('Class',    'width=12% align="left"', 'align="left"');
    $reportTableHead['subjectCode']    =  array('Subject',  'width="10%" align="left" ', 'align="left"');
    $reportTableHead['groupShort']     =  array('Group',    'width="10%" align="left" ', 'align="left"');
    $reportTableHead['testName']       =  array('Test',     'width=12% align="left"', 'align="left"');
    $reportTableHead['testDate']       =  array('Test Date', 'width=10% align="center"', 'align="center"');    
    $reportTableHead['maxMarks']       =  array('Max.<br>Marks','width=6% align="right"', 'align="right"');  
    $reportTableHead['maxMarksScored'] =  array('Max.<br>Scored','width=6% align="right"', 'align="right"');  
    $reportTableHead['minMarksScored'] =  array('Min.<br>Scored','width=6% align="right"', 'align="right"');  
    $reportTableHead['avgMarks']       =  array('Avg.',     'width=6% align="right"', 'align="right"');  
    $reportTableHead['presentCount']   =  array('Present',  'width=6% align="right"', 'align="right"');  
    $reportTableHead['absentCount']    =  array('Absent',   'width=6% align="right"', 'align="right"');  
    
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
    
// $History: listExamStatisticsReportPrint.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:10p
//Created in $/LeapCC/Templates/Management
//initial checkin
//

?>