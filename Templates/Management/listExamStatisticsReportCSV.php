<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
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
    
    
    //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
          return '"'.$comments.'"'; 
       } 
       else {
          return $comments.chr(160); 
       }
    }

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

    $record = $managementManager->getTeacherTests($concatStr,$condition,$orderBy);
    $cnt1 = count($record);

    $search = $REQUEST_DATA['searchbox'];    
    $csvData = '';   
    $csvData .= "Search By:, ".parseCSVComments($search)."\n";
    $csvData .= "Sr. No.,Teacher,Class,Subject,Group,Test,Test Date,Max. Marks,Max. Scored,Min. Scored,Avg.,Present,Absent \n";
    $valueArray = array();
    for($i=0;$i<$cnt1;$i++) {  
       $record[$i]['testDate'] = UtilityManager::formatDate($record[$i]['testDate']);      
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['employeeName']).",".parseCSVComments($record[$i]['className']);
       $csvData .= ",".parseCSVComments($record[$i]['groupShort']);
       $csvData .= ",".parseCSVComments($record[$i]['subjectCode']).",".parseCSVComments($record[$i]['testName']);
       $csvData .= ",".parseCSVComments($record[$i]['testDate']).",".parseCSVComments($record[$i]['maxMarks']);
       $csvData .= ",".parseCSVComments($record[$i]['maxMarksScored']).",".parseCSVComments($record[$i]['minMarksScored']);
       $csvData .= ",".parseCSVComments($record[$i]['avgMarks']).",".parseCSVComments($record[$i]['presentCount']);
       $csvData .= ",".parseCSVComments($record[$i]['absentCount'])." \n";
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="ExamStatisticsReport.csv"');

	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 
// $History: listExamStatisticsReportCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:10p
//Created in $/LeapCC/Templates/Management
//initial checkin
//

?>