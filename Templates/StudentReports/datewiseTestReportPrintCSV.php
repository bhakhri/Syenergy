<?php
//This file is used as printing version for test Time wise report.
//
// Author :Arvind Singh Rawat
// Created on : 22-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    
     //to parse csv values    
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         $comments = str_ireplace('<br>', "\n", $comments);
         $comments = str_ireplace('&nbsp;', ",", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
    
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

    $csvData = "";
    $csvData .= "Degree: ".$REQUEST_DATA['className']."\n";
    $csvData .= ",Subject: ".$REQUEST_DATA['subjectName']." Group ".$REQUEST_DATA['groupName'];
    $csvData .= ",Test Type Category: ".$REQUEST_DATA['testTypeName']."\n";
    $csvData .= ",From Date: ".UtilityManager::formatDate($fromDate).", To Date: ".UtilityManager::formatDate($toDate);
    $csvData .= "\n";
    $csvData .= "#, Test Type, Subject Name, Subject Code, Group, Employee, Test Date ";
    $csvData .= "\n";     
      
    for($i=0;$i<$cnt;$i++) {
      $recordArray[$i]['testDate'] = UtilityManager::formatDate($recordArray[$i]['testDate']);
      $csvData .= ($i+1).",".parseCSVComments($recordArray[$i]['testType']).",".parseCSVComments($recordArray[$i]['subjectName']);
      $csvData .= ",".parseCSVComments($recordArray[$i]['subjectCode']).",".parseCSVComments($recordArray[$i]['groupName']);
      $csvData .= ",".parseCSVComments($recordArray[$i]['employeeName']).",".parseCSVComments($recordArray[$i]['testDate']); 
      $csvData .= "\n";
   }
    if($cnt==0) {
		$csvData .= ",,,No Data Found";
	}
   
   UtilityManager::makeCSV($csvData, "DatewiseTestReport.csv");   
   die;

// $History: datewiseTestReportPrintCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/14/09   Time: 3:26p
//Created in $/LeapCC/Templates/StudentReports
//file added
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
