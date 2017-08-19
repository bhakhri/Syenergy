<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	define('MODULE','MarksStatusReport');
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

	$conditions = '';
	if ($classId != 'all') {
		$conditions = " AND ttc.classId = $classId";
		if ($subjectId != 'all') {
			$conditions .= " AND t.subjectId = $subjectId";
		}
	}

	$studentRecordArray = $studentReportsManager->getMarksStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');


    $cnt = count($studentRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
    }

	$reportInformation = '';

	$className = " All ";

	if ($classId != 'all') {
		$classNameArray = $studentReportsManager->getSingleField('class', 'className', "WHERE classId  = $classId");
		$className = $classNameArray[0]['className'];
	}
	$subCode = " All ";
	if ($subjectId != 'all') {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
		$subCode = $subCodeArray[0]['subjectCode'];
	}


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Marks Status Report');
	$reportManager->setReportInformation("<b>For:</b> Degree: $className, Subject: $subCode");

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']					=	array('#',						'width="5%" align=left', "align='left' ");
	$reportTableHead['className']				=	array('Degree',					'width=22% align="left"', 'align="left"');
	$reportTableHead['subjectCode']				=	array('Subject',				'width="10%" align="left" ', 'align="left"');
	$reportTableHead['employeeName']				=	array('Faculty',				'width="15%" align="left"', 'align="left"');
	$reportTableHead['groupShort']				=	array('Group',				'width="10%" align="left"', 'align="left"');
	$reportTableHead['testTypeName']				=	array('Test Type',				'width="10%" align="left"','align="left"');
	$reportTableHead['testAbbr']				=	array('Test Abbr.',				'width="10%" align="left"','align="left"');
	$reportTableHead['testIndex']				=	array('Index',				'width="6%" align="right"','align="right"');
	$reportTableHead['maxMarks']				=	array('M.M.',				'width="6%" align="right"','align="right"');
	$reportTableHead['studentCount']				=	array('Students',				'width="8%" align="right"','align="right"');


	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : listMarksNotEnteredReportPrint.php $
//


?>