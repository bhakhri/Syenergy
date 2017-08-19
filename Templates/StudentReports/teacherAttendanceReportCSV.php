<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    $groupId=trim($REQUEST_DATA['groupId']);
    $chkHierarchy=trim($REQUEST_DATA['chkHierarchy']);
    
    if($chkHierarchy=='') {
      $chkHierarchy=0;  
    }
    
    
    if($labelId=='' or $classId=='' or $employeeId=='' or $fromDate=='' or $toDate=='' or $groupId==''){
        echo 'Required Parametes Missing';
        die;
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";         

    ////////////
    
    $filter =' AND ttc.timeTableLabelId='.$labelId.' AND c.classId='.$classId.' AND ( att.fromDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND att.toDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" )';
    
    if($employeeId!=-1 and $employeeId!=''){
        $filter .=' AND e.employeeId='.$employeeId;
    }
    
    $groupConditions='';
    if($groupId!=-1){
       //find group hierarchy
       if($chkHierarchy==1) { 
         $groupHierarchyString=$studentReportsManager->getGroupHierarchy($classId,$groupId);
         $groupConditions=' AND att.groupId IN ('.$groupHierarchyString.')';
         $filter .=$groupConditions;
       }
       else {
         $filter .=' AND att.groupId IN ('.$groupId.')';   
       }
    }
    
    
    $teacherAttendanceRecordArray = $studentReportsManager->getTeacherAttendanceSummeryList($filter,' ',$orderBy);
    $cnt = count($teacherAttendanceRecordArray);
    
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
      $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$teacherAttendanceRecordArray[$i]);
    } 

	$csvData = '';
    $csvData .= 'Time Table, Class, Group, Teacher, ';
    $csvData .= 'From, To ';
    $csvData .= "\n";   
    $csvData .= "#, Class, Group, Subject Code, Subject Name, Teacher, Delivered, Adjustment, Tot Delivered \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['className']).', '.parseCSVComments($record['groupName']).','.parseCSVComments($record['subjectCode']).','.parseCSVComments($record['subjectName']).', '.parseCSVComments($record['employeeName']).', '.parseCSVComments($record['attendanceTaken']).', '.parseCSVComments($record['adjustmentTaken']).', '.parseCSVComments($record['totalDelivered']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="teacherAttendanceReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: teacherAttendanceReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:22
//Created in $/LeapCC/Templates/StudentReports
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
?>