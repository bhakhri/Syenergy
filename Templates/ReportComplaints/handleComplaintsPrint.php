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
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
	$reportComplaintsManager = ReportComplaintsManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
    
    $orderBy = "$sortField $sortOrderBy";
	
	$hostelId = $REQUEST_DATA['hostel'];
	$roomId = $REQUEST_DATA['room'];
	$studentId = $REQUEST_DATA['reportedBy'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];


	if ($hostelId == '' && $roomId == '' && $studentId =='') {
		$conditions = "AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);	
	}
	
	if ($hostelId != '' && $roomId != '' && $studentId == '') {
		$conditions = "AND c.hostelRoomId = $roomId AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);
	}
	
	if ($hostelId != '' && $roomId != '' && $studentId !='') {
		$conditions = "AND c.studentId=$studentId AND c.hostelRoomId = $roomId AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);
	}
	
	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
//        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
		$reportCompalintRecordArray[$i]['complaintOn'] = UtilityManager::FormatDate($reportCompalintRecordArray[$i]['complaintOn']);
		if ($reportCompalintRecordArray[$i]['completionDate'] == "0000-00-00") {
			$reportCompalintRecordArray[$i]['completionDate'] = NOT_APPLICABLE_STRING;	
		}
		else {
			$reportCompalintRecordArray[$i]['completionDate']  = UtilityManager::formatDate($reportCompalintRecordArray[$i]['completionDate']);	
		}
        $valueArray[] = array(	'srNo' => $i+1 ,
								'subject' => $reportCompalintRecordArray[$i]['subject'],
								'categoryName' => $reportCompalintRecordArray[$i]['categoryName'],
								'roomName' => $reportCompalintRecordArray[$i]['roomName'],
								'hostelName' => $reportCompalintRecordArray[$i]['hostelName'],
								'studentName' => $reportCompalintRecordArray[$i]['studentName'],
								'complaintOn' => $reportCompalintRecordArray[$i]['complaintOn'],
								'completionDate' => $reportCompalintRecordArray[$i]['completionDate'],
								'updateComplaintStatus' => $reportCompalintRecordArray[$i]['updateComplaintStatus'],
								'completionRemarks' => $reportCompalintRecordArray[$i]['completionRemarks']
							);
   }

  /* $classNameArray = $studentReportManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
   $className = $classNameArray[0]['className'];
   $className2 = str_replace("-",' ',$className);

   $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
   $subCode = $subCodeArray[0]['subjectCode'];
*/



	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Handle Complaints Report');
	//$reportManager->setReportInformation("$className2 Subject: $subCode");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']					=	array('#',					'width="4%" align="left"', "align='left' ");
	$reportTableHead['subject']					=	array('Subject',			'width=12% align="left"', 'align="left"');
	$reportTableHead['categoryName']			=	array('Category Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['roomName']				=	array('Room No.',		'width="12%" align="left"', 'align="left"');
	$reportTableHead['hostelName']				=	array('Hostel',	'width="14%" align="left"','align="left"');
	$reportTableHead['studentName']				=	array('Reported By',		'width="14%" align="left"','align="left"');
	$reportTableHead['complaintOn']				=	array('Complaint On',		'width="14%" align="center"','align="center"');
	$reportTableHead['completionDate']			=	array('Complete On',		'width="14%" align="center"','align="center"');
	$reportTableHead['updateComplaintStatus']	=	array('Status',		'width="14%" align="left"','align="left"');
	$reportTableHead['completionRemarks']	=	array('Remarks',		'width="14%" align="left"','align="left"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
