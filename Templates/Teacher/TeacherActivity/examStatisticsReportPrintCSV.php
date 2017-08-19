<?php 
//This file is used as CSV Exam Statistics details 
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifTeacherNotLoggedIn(true);
	UtilityManager::headerNoCache();
	
	require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");     
    $employeeReportsManager = EmployeeReportsManager::getInstance();     

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

	 // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         $comments = str_ireplace('<br>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments;  
         }
    }

    $teacherId = $sessionHandler->getSessionVariable('EmployeeId');
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = " $sortField $sortOrderBy";

	$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
	$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
	$concatStr = '';
	foreach($teacherSubjectsArray as $teacherSubjectRecord) {
		$subjectId = $teacherSubjectRecord['subjectId'];
		$classId = $teacherSubjectRecord['classId'];
		if ($concatStr != '') {
			$concatStr .= ',';
		}
		$concatStr .= "'$subjectId#$classId'";
	}
	$teacherTestsArray = $teacherManager->getTeacherTests($teacherId, $concatStr,$orderBy);

	$cnt = count($teacherTestsArray);
    
    
    $csvData  = "Employee Name:,".parseCSVComments($REQUEST_DATA['employeeName']).",Employee Code:,".parseCSVComments($REQUEST_DATA['employeeCode']);
    $csvData .= "\n";
    $csvData .= "Sr. No., Class, Subject, Group, Test, Test Date, M.Marks, Max.Scored, Min.Scored, Avg., Pre., Ab. \n";    
    
    $k=0;
    for($i=0; $i<$cnt; $i++) {
        // Findout Topics & Pending Topics List 
        $className = $teacherTestsArray[$i]['className'];    
        $subjectCode = $teacherTestsArray[$i]['subjectCode'];
        $groupShort = $teacherTestsArray[$i]['groupShort'];
        $testName   = $teacherTestsArray[$i]['testName'];
        $testDate     = $teacherTestsArray[$i]['testDate'];    
        $maxMarks   = $teacherTestsArray[$i]['maxMarks']; 
		$maxMarksScored = $teacherTestsArray[$i]['maxMarksScored'];
		$minMarksScored=  $teacherTestsArray[$i]['minMarksScored'];
		$avgMarks = $teacherTestsArray[$i]['avgMarks'];
		$presentCount = $teacherTestsArray[$i]['presentCount'];
		$absentCount = $teacherTestsArray[$i]['absentCount'];
        
       $csvData .= ($k+1).','.parseCSVComments($className).','.parseCSVComments($subjectCode).','.parseCSVComments($groupShort);
       $csvData .= ','.parseCSVComments($testName).','.parseCSVComments($testDate).','.parseCSVComments($maxMarks).','.parseCSVComments($maxMarksScored).','.parseCSVComments($minMarksScored).','.parseCSVComments($avgMarks).','.parseCSVComments($presentCount).','.parseCSVComments($absentCount);
       $csvData .= "\n"; 
       $k++; 
    }


	ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream; charset="utf-8"',true);
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="ExamStatisticsReport.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
 
 

// $History: topicwiseReportPrintCSV.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/14/09   Time: 12:25p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopicId  null check added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/23/09   Time: 2:36p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//sorting order updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//topicswise report format updated (classname added)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/01/09   Time: 10:47a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//check attendance, marks condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/01/09   Time: 10:33a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//file added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/16/09    Time: 6:00p
//Updated in $/LeapCC/Templates/EmployeeReports
//report formatting updated (condition changes)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//issue fix 1519, 1518, 1517, 1473, 1442, 1451 
//validiations & formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/26/09    Time: 4:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//code updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 3:37p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//condition, formatting & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:20p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//initial checkin 
//

?>