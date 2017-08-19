<?php
//This file is used as printing version for attendance report.
//
// Author :Ajinder Singh
// Created on : 17-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
	$cleaningRoomManager = CleaningRoomManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'et.tempEmployeeName';
    
     $orderBy = "$sortField $sortOrderBy"; 

	$tempEmployeeId = $REQUEST_DATA['tempEmployee'];
	$hostelId = $REQUEST_DATA['hostel'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];


	if ($hostelId == '' && $tempEmployeeId == '') {
		$conditions = "AND Dated BETWEEN '$startDate' AND '$toDate'";
	//	$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	if ($tempEmployeeId != '' && $hostelId == '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)";
		//$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId != '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)
						AND hcr.hostelId IN($hostelId)";
		//$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId == '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.hostelId IN($hostelId)";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	$valueArray = array();
 $reportHead  = "<b>Safaiwala</b>:&nbsp;".$REQUEST_DATA['employeeText'];
    $reportHead .= "&nbsp;&nbsp;<b>Hostel</b>:&nbsp;".$REQUEST_DATA['hostelText']."<br>";  
    $reportHead .= "<b>Date </b>BETWEEN:&nbsp;".UtilityManager::formatDate($REQUEST_DATA['startDate'])."&nbsp;&nbsp;AND&nbsp;&nbsp;".UtilityManager::formatDate($REQUEST_DATA['toDate']);  

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
//        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
		$cleaningRoomRecordArray[$i]['Dated'] = UtilityManager::FormatDate($cleaningRoomRecordArray[$i]['Dated']);
        $valueArray[] = array(	'srNo' => $i+1 ,
								'tempEmployeeName' => $cleaningRoomRecordArray[$i]['tempEmployeeName'],
								'hostelName' => $cleaningRoomRecordArray[$i]['hostelName'],
								'Dated' => $cleaningRoomRecordArray[$i]['Dated'],
								'toiletsCleaned' => $cleaningRoomRecordArray[$i]['toiletsCleaned'],
								'noOfRoomsCleaned' => $cleaningRoomRecordArray[$i]['noOfRoomsCleaned'],
								'attachedRoomToiletsCleaned' => $cleaningRoomRecordArray[$i]['attachedRoomToiletsCleaned'],
								'dryMoppingInSqMeter' => $cleaningRoomRecordArray[$i]['dryMoppingInSqMeter'],
								'wetMoppingInSqMeter' => $cleaningRoomRecordArray[$i]['wetMoppingInSqMeter'],
								'roadCleanedInSqMeter' => $cleaningRoomRecordArray[$i]['roadCleanedInSqMeter'],
								'noOfGarbageBinsDisposal' => $cleaningRoomRecordArray[$i]['noOfGarbageBinsDisposal'],
								'noOfHoursWorked' => $cleaningRoomRecordArray[$i]['noOfHoursWorked']
							);
   }

  /* $classNameArray = $studentReportManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
   $className = $classNameArray[0]['className'];
   $className2 = str_replace("-",' ',$className);

   $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
   $subCode = $subCodeArray[0]['subjectCode'];
*/


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Cleaning History Report');
	$reportManager->setReportInformation($reportHead);        

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					'width="4%" align="left"', "align='left'");
	$reportTableHead['tempEmployeeName']	=	array('Employee Name',			'width=12% align="left"', 'align="left"');
	$reportTableHead['hostelName']			=	array('Hostel Name',		'width="12%" align="left" ', 'align="left"');
	$reportTableHead['Dated']				=	array('Date',		'width="10%" align="center"', 'align="center"');
	$reportTableHead['toiletsCleaned']		=	array('Toilet(s) Cleaned',	'width="5%" align="right"','align="right"');
	$reportTableHead['noOfRoomsCleaned']	=	array('Room(s) Cleaned',		'width="5%" align="right"','align="right"');
	$reportTableHead['attachedRoomToiletsCleaned']	=	array('Attached Room Toilet(s) Cleaned',		'width="10%" align="right"','align="right"');
	$reportTableHead['dryMoppingInSqMeter']	=	array('Dry Mopping',		'width="8%" align="right"','align="right"');
	$reportTableHead['wetMoppingInSqMeter']	=	array('Wet Mopping',		'width="8%" align="right"','align="right"');
	$reportTableHead['roadCleanedInSqMeter']	=	array('Road Cleaned',		'width="8%" align="right"','align="right"');
	$reportTableHead['noOfGarbageBinsDisposal']	=	array('Garbage Disposal',		'width="8%" align="right"','align="right"');
	$reportTableHead['noOfHoursWorked']	=	array('No. of Hrs.',		'width="8%" align="right"','align="right"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
