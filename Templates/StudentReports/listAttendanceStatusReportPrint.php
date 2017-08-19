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
    }
    else {
       $studentRecordArray = $studentReportsManager->getAttendanceStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');
    }
    
    
    $cnt = count($studentRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$studentRecordArray[$i]['tillDate'] = UtilityManager::formatDate($studentRecordArray[$i]['tillDate']);
		$valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
    }

	$reportInformation = '';

    $classNameArray = $studentReportsManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = '$labelId'");
    $labelName = $classNameArray[0]['labelName'];
    
	$className = " All ";
	if ($classId != 'all') {
		$classNameArray = $studentReportsManager->getSingleField('class', 'className', "WHERE classId  = $classId");
		$className = $classNameArray[0]['className'];
	}
	
    $subCode = "";
    if ($subjectId != 'all') {
	   $subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectName, subjectCode', "where subjectId  = '$subjectId'");
       $subCode ="<br>Subject: ".$subCodeArray[0]['subjectName']." (".$subCodeArray[0]['subjectCode'].")";
	}
    
    $subCode = "";
    if ($employeeId != 'all') {
       $subCodeArray = $studentReportsManager->getSingleField('employee', 'employeeName, employeeCode', "where employeeId  = '$employeeId' ");
       $subCode ="<br>Teacher: ".$subCodeArray[0]['employeeName']." (".$subCodeArray[0]['employeeCode'].")";
    }
    
    if($showTodayAttendance=='on') {
       $subCode .= "<br>Attendance Not Marked Today: ".UtilityManager::formatDate($todayDate);
    }
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Last Attendance Taken Report');
	$reportManager->setReportInformation("<b>For:</b>Time Table: $labelName<br>Class: $className $subCode");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="5%" align=left', "align='left' ");
    $reportTableHead['employeeName']            =    array('Teacher',                'width="20%" align="left"','align="left"'); 
    $reportTableHead['beHalfEmployeeName']      =    array('On Behalf of',                'width="20%" align="left"','align="left"'); 
	$reportTableHead['className']				=	array('Class',					'width=22% align="left"', 'align="left"');
	$reportTableHead['subjectCode']				=	array('Subject',				'width="10%" align="left" ', 'align="left"');
	$reportTableHead['groupShort']				=	array('Group',				'width="15%" align="left"', 'align="left"');
    if($showTodayAttendance=='on') { 
       $reportTableHead['periodName']                =    array('Period',                'width="20%" align="center"','align="center"'); 
    }
    else {
       $reportTableHead['tillDate']                =    array('Till Date',                'width="10%" align="center"','align="center"'); 
    }
    
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksNotEnteredReportPrint.php $
//


?>