<?php 
//This file is used as csv version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $studentManager = TeacherManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
	//used to parse csv data
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

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
                                                                      

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';

    $orderBy=" ORDER BY $sortField $sortOrderBy"; 

    
    $studentRecordArray = $studentManager->getTeacherAttendanceSummeryList($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId'],$orderBy);
    $cnt = count($studentRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$valueArray = array();
	$TD=0;
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$studentRecordArray[$i]);
  	    $TD +=$studentRecordArray[$i]['totalDelivered'];
    }

	$csvData = '';
	$csvData .= "Sr, Class, Subject, Group, Lecture Delivered \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',  '.parseCSVComments($record['className']).',  '.parseCSVComments($record['subjectCode']).',  '.parseCSVComments($record['groupName']).',  '.parseCSVComments($record['totalDelivered']);
		$csvData .= "\n";
	}
	$csvData .= "Total Lecture Delivered : ".$TD." \n";
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="attendanceSummary.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: attenanceSummeryCSV.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 19:24
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Attendance Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/04/09   Time: 18:47
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Completed Attendance Summery Report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/04/09   Time: 15:44
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created "Display Test Summery"
?>