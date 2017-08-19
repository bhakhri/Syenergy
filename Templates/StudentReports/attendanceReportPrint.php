<?php
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 17-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentAttendance');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $results = CommonQueryManager::getInstance()->getAttendanceCode($conditions);
    
    $attendanceCodeWise='';
    for($i=0;$i<count($results);$i++) {
      $id = $results[$i]['attendanceCodeId'];   
      $attendanceCodeWise .= ", FORMAT(SUM(IF(att.isMemberOfClass=0,0,
                                      IF(ac.attendanceCodeId=$id,
                                         IF(att.attendanceType=2,IF((ac.attendanceCodePercentage/100)=0,1,ac.attendanceCodePercentage/100),att.lectureAttended),0))),1) AS att".$id;
    }

	/*
	$arr = explode('-',$REQUEST_DATA['degree']);
	$universityId = $arr[0];
	$degreeId = $arr[1];
	$branchId = $arr[2];
	$studyPeriodId = $REQUEST_DATA['studyPeriodId'];
	*/

	$subjectId = $REQUEST_DATA['subjectId'];
	$sortField = $REQUEST_DATA['sortField'];
	if ($sortField == 'rollNo') {
		$sortField = 'numericRollNo';
	}
	//$classId = $REQUEST_DATA['class'];

	//fetch classId which match the criteria
	/*
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$studyPeriodId."'";   
	$classIdArray = $reportManager->getClassId($classFilter);
	*/

	$classId = $REQUEST_DATA['degree'];

	//fetch all students for this class and for this subject
    $reportRecordArray = $studentReportManager->getAttendanceData($classId, $subjectId, $sortField, $REQUEST_DATA['sortOrderBy'],'',$attendanceCodeWise);
	$cnt = count($reportRecordArray);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        // $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
		$reportRecordArray[$i]['Percentage'] = "0.00";
		if($reportRecordArray[$i]['lectureAttended'] > 0 && $reportRecordArray[$i]['lectureDelivered'] > 0 ) {
			$reportRecordArray[$i]['Percentage'] = "".round($reportRecordArray[$i]['lectureAttended'] / $reportRecordArray[$i]['lectureDelivered']*100,1)."";
		}
		else {
			$reportRecordArray[$i]['Percentage'] = $reportRecordArray[$i]['Percentage'];
		}
        $valueArray[] = array_merge(array('srNo'=>($records+$i+1)),$reportRecordArray[$i]); 
   }
   

   $classNameArray = $studentReportManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
   $className = $classNameArray[0]['className'];
   $className2 = str_replace("-",' ',$className);

   $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
   $subCode = $subCodeArray[0]['subjectCode'];

    
    $rowspan1='width="3%"';
    $rowspan='width="10%"';
    $colspan = "width='10%'";  
    if(count($results)>1) {
      $rowspan  .= " rowspan=2";
      $rowspan1 .= " rowspan=2";  
      $colspan  .= " colspan=".count($results);  
    }
   

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Studence Attendance Report');
	$reportManager->setReportInformation("$className2 Subject: $subCode");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					"$rowspan1", "align='right' ");
	$reportTableHead['rollNo']				=	array('Roll No.',			"$rowspan", 'align="left"');
	$reportTableHead['universityRollNo']	=	array('Univ. Roll No.',		"$rowspan", 'align="left"');
	$reportTableHead['studentName']			=	array('Student Name',		"$rowspan", 'align="left"');
	$reportTableHead['lectureDelivered']	=	array('Lect. Delivered',	"$rowspan", 'align="right"');
	$reportTableHead['lectureAttended']		=	array('Lect. Attended',		"$rowspan", 'align="right"');
//	$reportTableHead['lectureMissed']		=	array('Lect. Missed',		"width='4%' $rowspan1", 'align="right"');
	$reportTableHead['Percentage']			=	array('Percentage',			"$rowspan", 'align="center"');
    $reportTableHead['attendance']          =   array('Attendance Code',    "$colspan", 'align="center"',true);
    for($i=0;$i<count($results);$i++) { 
       $id = 'att'.$results[$i]['attendanceCodeId'];  
       $str = $results[$i]['attendanceCode'];
       $reportTableHead[$id]                =   array($str,  'width="10%" align="center"','align="center"'); 
    }
    
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
