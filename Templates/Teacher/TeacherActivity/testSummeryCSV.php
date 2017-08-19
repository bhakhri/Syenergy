<?php 
//This file is used as csv version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

    $orderBy=" $sortField $sortOrderBy"; 

    
    $testRecordArray = $studentManager->getTeacherTestsSummery($REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['groupId'],$REQUEST_DATA['timeTableLabelId'],$orderBy);
    $cnt = count($testRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$testRecordArray[$i]['testDate']=UtilityManager::formatDate($testRecordArray[$i]['testDate']);
		$valueArray[] = array_merge(array('srNo' => ($i+1) ),$testRecordArray[$i]);
    }

	$csvData = '';
	$csvData .= "Sr, Class, Subject, Group, Topic, TestType, Max Marks, Date \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].',  '.parseCSVComments($record['className']).',  '.parseCSVComments($record['subjectCode']).',  '.parseCSVComments($record['groupName']).',  '.parseCSVComments($record['testTopic']).',  '.parseCSVComments($record['testTypeName']).',  '.parseCSVComments($record['maxMarks']).',  '.parseCSVComments($record['testDate']);
		$csvData .= "\n";
	}
    $csvData .= "Total Tests Conducted : $cnt \n";
    
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="testSummary.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: testSummeryCSV.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:14
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/04/09   Time: 11:28
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Modified Test Summary report for teachers
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/04/09   Time: 18:51
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected spelling mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/04/09   Time: 15:44
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created "Display Test Summery"
?>