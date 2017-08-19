<?php 
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();


	$arr = explode('-',$REQUEST_DATA['degree']);
	$universityId = $arr[0];
	$degreeId = $arr[1];
	$branchId = $arr[2];
	$studyPeriodId = $REQUEST_DATA['studyPeriodId'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$sortField = $REQUEST_DATA['sortField'];

	//fetch classId which match the criteria
	$classFilter = " WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$studyPeriodId."'";   
	$classIdArray = $studentReportsManager->getClassId($classFilter);

	$classId = $classIdArray[0]['classId'];
	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
	$className = $classNameArray[0]['className'];
	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Subjectwise Consolidated Report');
	$reportManager->setRecordsPerPage(40);
	
	
	
	if ($subjectId == 'all') {

		$subjectsArray = $studentReportsManager->getClassConsolidatedSubjects($classId);
		$valueArray['subjects'] = $subjectsArray;
		$totalSubjects = count($subjectsArray);

		foreach($subjectsArray as $subjectRecord) {
			$subjectId = $subjectRecord['subjectId'];
			$subjectCode = $subjectRecord['subjectCode'];
			$queryPart .= ", 
							(
									SELECT 
											COUNT(b.studentId) AS cnt 
									FROM	student b 
									WHERE	b.studentId 
									IN (
										SELECT 
													c.studentId 
										FROM		".TEST_TRANSFERRED_MARKS_TABLE." c 
										WHERE		c.classId = $classId AND 
													c.subjectId = $subjectId 
										GROUP BY	CONCAT(c.studentId, c.classId, c.subjectId) 
										HAVING		ROUND(SUM(c.marksScored)/SUM(c.maxMarks)*100) 
										BETWEEN		a.rangeFrom AND a.rangeTo
									   )
							) AS $subjectCode
						";

		}
		$dataArray = $studentReportsManager->getAllSubjectConsolidatedMarks($queryPart);		
		$cnt = count($dataArray);
		for($i=0;$i<$cnt;$i++) {
			$dataArray2[] = array_merge(array('srNo' => ($records+$i+1) ),$dataArray[$i]);
		}
		$valueArray['data'] = $dataArray2;

		$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align		
		$reportTableHead['srNo']				=	array('#',					'width="2%" align="left" rowspan="2"', "align='left'");
		$reportTableHead['rangeLabel']			=	array('Range',				'width="10%" align="left" rowspan="2"', 'align="left"');
		$reportTableHead['studentCount']		=	array('No. of Students',	"width='88%' align='right' colspan='$totalSubjects'", 'align="center"');

		
		$reportManager->setReportData($reportTableHead, $valueArray);
		$reportManager->showSubjectWiseConsolidatedReport();
	}
	else {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
		$subCode = $subCodeArray[0]['subjectCode'];
		$reportManager->setReportInformation("$className2 Subject: $subCode");
		$dataArray = $studentReportsManager->getSubjectWiseConsolidatedMarks($classId, $subjectId);
		$cnt = count($dataArray);
		$valueArray = array();

		for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$dataArray[$i]);
		}

		$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align		
		$reportTableHead['srNo']				=	array('#',					'width="10%" align="left"', "align='left'");
		$reportTableHead['rangeLabel']			=	array('Range',				'width=45% align="left"', 'align="left"');
		$reportTableHead['studentCount']		=	array('No. of Students',	'width="45%" align="right" ', 'align="right"');
		$reportManager->setReportData($reportTableHead, $valueArray);
		$reportManager->showReport();
	}



//$History: listSubjectWiseConsolidatedReportPrint.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:20p
//Updated in $/LeapCC/Templates/StudentReports
//added multiple table defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:36p
//Updated in $/Leap/Source/Templates/StudentReports
//added code for "all subjects"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/21/08    Time: 11:28a
//Updated in $/Leap/Source/Templates/StudentReports
//removed following functions:
//1. getRangeValues()
//2. getStudentsInRange()
//and added following function:
//1. getSubjectWiseConsolidatedMarks()
//for subjectwise consolidated report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:39p
//Created in $/Leap/Source/Templates/StudentReports
//file added for subject wise consolidated report
//

?>

