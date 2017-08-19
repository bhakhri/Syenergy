<?php 
//This file is used as printing version for Attendance Threshold.
//
// Author :Jaineesh
// Created on : 19-02-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  


	require_once(MODEL_PATH . "/DashBoardManager.inc.php");
	$dashboardManager = DashBoardManager::getInstance();
	 
	$classId = $REQUEST_DATA['classId'];
	$subjectId = $REQUEST_DATA['subjectId'];


     $ttTableName = TIME_TABLE_TABLE.'  t, employee e';
	 $employeeTypeArray = $dashboardManager->getSingleField($ttTableName,'distinct e.employeeName',"WHERE t.employeeId = e.employeeId AND 
     t.classId = $classId AND t.subjectId = $subjectId");
     $employeeNames = UtilityManager::makeCSList($employeeTypeArray,'employeeName'); 
	 
	$attendaceThreshold =	$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
	$lectureDelivered = $dashboardManager->lecturesDelivered($classId,$subjectId);
	
	$lectures = $lectureDelivered[0]['lectureDelivered'];
	if($lectures=='') {
	   $lectures = 0;
       $attendance = "&nbsp;&nbsp;<b>Lectures Delivered:</b>".$lectures;
	}
	else {
  	   $uptoDate = UtilityManager::formatDate($lectureDelivered[0]['uptoDate']);
	   $attendance = "&nbsp;&nbsp;<b>Show Attendance Upto:</b> ".$uptoDate."&nbsp;&nbsp;<b>Lectures Delivered:</b>".$lectures;
	}

	if($classId != '' && $subjectId != '') {
		$studentAttendanceThresholdArray = $dashboardManager->getAttendanceThresholdRecordsPrint($classId,$subjectId);
		$cnt = count($studentAttendanceThresholdArray);
	}
		if($cnt == 0) {
			$getClassNameArray = $dashboardManager->getClassName($classId);
			$className = $getClassNameArray[0]['className'];
			$getSubjectNameArray = $dashboardManager->getSubjectName($subjectId);
			$subjectCode = $getSubjectNameArray[0]['subjectCode'];
		}
		else {
			$className = $studentAttendanceThresholdArray[0]['className'];
			$subjectCode = $studentAttendanceThresholdArray[0]['subjectCode'];
		}

	$valueArray = array();
	$roleName ="";
    for($i=0;$i<$cnt;$i++) {
		
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$studentAttendanceThresholdArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportInformation("<b>Class Name:</b>".$className."$nbsp;&nbsp;<b>
											 Subject Code:</b>".$subjectCode."<br><b>
											 Attendance Threshold Value:</b>".$attendaceThreshold.",&nbsp;&nbsp;
											<b>Employee Name:</b>".$employeeNames."<br>&nbsp;&nbsp;".$attendance);

	$reportManager->setReportHeading("Student Attendance Threshold Report");
	 
//userName  roleId  dateTimeIn  roleUserName  
	$reportTableHead				  =	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		  =	array('#','width="3%" align="left"', "align='left'");
	$reportTableHead['studentName']	  =	array('Student Name','width=15% align="left"', 'align="left"');
	$reportTableHead['groupName']     =	array('Group Name','width=10% align="left"', 'align="left"');
	$reportTableHead['employeeName']  =	array('Employee Name','width=10% align="left"', 'align="left"');
	$reportTableHead['percentage']	  =	array('Percentage','width="10%" align="right" ', 'align="right"');
	 
	$reportManager->setRecordsPerPage(50);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listAttendanceThresholdReportPrint.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/15/10    Time: 10:20a
//Updated in $/LeapCC/Templates/Index
//show subject Name with subject code
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/13/10    Time: 7:12p
//Updated in $/LeapCC/Templates/Index
//solved problem of attendance threshold
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/19/10    Time: 3:02p
//Created in $/LeapCC/Templates/Index
//new file for print of attendance threshold 
//

?>