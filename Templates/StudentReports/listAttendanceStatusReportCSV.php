<?php
//This file is used as printing version for test attendance updation report.
//
// Author :Jaineesh
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	define('MODULE','AttendanceStatusReport');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

 	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

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
    
    $classId = $REQUEST_DATA['degree'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $sortOrderBy = $REQUEST_DATA['sortOrderBy'];
    $sortField = $REQUEST_DATA['sortField'];
    $labelId = $REQUEST_DATA['labelId'];
    $employeeId = $REQUEST_DATA['employeeId'];
    $showTodayAttendance = $REQUEST_DATA['showTodayAttendance'];   
    $todayDate = $REQUEST_DATA['txtDate'];  
    $isTimeTableCheck=  trim($REQUEST_DATA['timeTableCheck']);
    if($isTimeTableCheck=='on') {
      $isTimeTableCheck=1;  
    }
    else {
      $isTimeTableCheck=0;    
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    $orderBy = " $sortField $sortOrderBy";         
    
    if($classId=='') {
      $classId=0;  
    }

    $conditions = '';
    if ($classId != 'all') {
        $conditions = " AND g.classId = $classId";
    }
    if($subjectId != 'all') {
      $conditions .= " AND tt.subjectId = $subjectId";
    }
    if($employeeId != 'all') {
      $conditions .= " AND tt.employeeId = $employeeId";
    }

   
    if($showTodayAttendance=='on') {
       $studentRecordArray = $studentReportsManager->getLastNotAttendanceTaken($labelId, $sortField, $sortOrderBy, $conditions, '',$todayDate);
       $showStr = "Period";
    }
    else {
       $studentRecordArray = $studentReportsManager->getAttendanceStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');
       $showStr = "Till Date";
    }

    
	$csvData ='';
    $classNameArray = $studentReportsManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = '$labelId'");
    $labelName = $classNameArray[0]['labelName'];
    $csvData .="Time Table,".parseCSVComments($labelName);   
    
    
    $className = " All ";
    if ($classId != 'all') {
       $classNameArray = $studentReportsManager->getSingleField('class', 'className', "WHERE classId  = '$classId'");
       $className = $classNameArray[0]['className'];
    }     
    $csvData .="\n";
    $csvData .="Class,".parseCSVComments($className);   
    
    
    $subCode = "";
    if ($subjectId != 'all') {
       $subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectName, subjectCode', "where subjectId  = '$subjectId' ");
       $subCode = $subCodeArray[0]['subjectName']." (".$subCodeArray[0]['subjectCode'].")";
       $csvData .="\n";
       $csvData .="Subject,".parseCSVComments($subCode);   
    }
    
    $subCode = "";
    if ($employeeId != 'all') {
       $subCodeArray = $studentReportsManager->getSingleField('employee', 'employeeName, employeeCode', "where employeeId  = '$employeeId' ");
       $csvData .="\n";
       $csvData .="Teacher: ".parseCSVComments($subCodeArray[0]['employeeName']." (".$subCodeArray[0]['employeeCode'].")");
    }
    
    if($showTodayAttendance=='on') {
       $csvData .="\n";
       $csvData .="Attendance Not Marked Today: ".UtilityManager::formatDate($todayDate);
    }
    $csvData .="\n";
    $csvData .="#,Teacher,On Behalf of,Class,Subject,Group,$showStr"; 
    $csvData .="\n";

    for($i=0;$i<count($studentRecordArray);$i++) {
		//$offenseArray[$i]['offenseDate']  = UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
        $csvData .= ($i+1).",";
        $csvData .= $studentRecordArray[$i]['employeeName'].",";
        $csvData .= $studentRecordArray[$i]['beHalfEmployeeName'].",";
		$csvData .= $studentRecordArray[$i]['className'].",";
		$csvData .= $studentRecordArray[$i]['subjectCode'].",";
		$csvData .= $studentRecordArray[$i]['groupShort'].",";
		$studentRecordArray[$i]['tillDate'] = UtilityManager::formatDate($studentRecordArray[$i]['tillDate']);
        if($showTodayAttendance=='on') {
		  $csvData .= $studentRecordArray[$i]['periodName'].",";   
        }
        else {
          $csvData .= $studentRecordArray[$i]['tillDate'].",";     
        }
		$csvData .= "\n";
	}

	UtilityManager::makeCSV($csvData, 'AttendanceStatusReport.csv');
	die;

?>