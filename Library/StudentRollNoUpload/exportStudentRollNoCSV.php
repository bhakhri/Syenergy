 <?php 
//This file is used as CSV version of student roll.
//
// Author :Jaineesh
// Created on : 26.10.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");

    require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
   
	//to parse csv values    
	function parseCSVComments($comments) {
	 //$comments = str_replace('"', '""', $comments);
	 //$comments = str_ireplace('<br/>', "\n", $comments);
	 if(eregi(",", $comments) or eregi("\n", $comments)) {
	   return '"'.$comments.'"'; 
	 }
	 else {
	   return chr(160).$comments; 
	 }
	}

    $classId = trim($REQUEST_DATA['degree']);
	$studentListRollNo = trim($REQUEST_DATA['studentListRollNo']);
	
	if ($classId == '') {
		echo ('<script type="text/javascript">alert("Please select class");</script>');
		die;
	}

	if ($studentListRollNo == 1) {
		$conditions = "WHERE s.classId=".$classId." AND s.classId = cl.classId";
		$studentRecordArray = $studentManager->getStudentDetailInfo($conditions);
		$recordCount = count($studentRecordArray);

		$valueArray = array();

		$csvData ='';
		$csvData="Sr.No.,Roll No,University Roll No.,Student Name,Father Name,Date of Birth,Upload Roll No. Status";
		$csvData .="\n";
		
		for($i=0;$i<$recordCount;$i++) {
			  $csvData .= ($i+1).',';
			  $csvData .= trim($studentRecordArray[$i]['rollNo']).',';
			  $csvData .= trim($studentRecordArray[$i]['universityRollNo']).',';
			  $csvData .= trim($studentRecordArray[$i]['studentName']).',';
			  $csvData .= trim($studentRecordArray[$i]['fatherName']).',';
			  $csvData .= parseCSVComments($studentRecordArray[$i]['dateOfBirth']).',';
			  $csvData .= 'No';
			  $csvData .= "\n ";
	  }
	}

	if ($studentListRollNo == 2) {
		$conditions = "WHERE s.classId=".$classId." AND s.classId = cl.classId AND (s.rollNo = '' OR s.universityRollNo = '')";
		$studentRecordArray = $studentManager->getStudentDetailInfo($conditions);
		$recordCount = count($studentRecordArray);

		$valueArray = array();

		$csvData ='';
		$csvData="Sr.No.,Roll No.,University Roll No.,Student Name,Father Name,Date of Birth,Upload Roll No. Status";
		$csvData .="\n";
		
		for($i=0;$i<$recordCount;$i++) {
			  $csvData .= ($i+1).',';
			  $csvData .= trim($studentRecordArray[$i]['rollNo']).',';
			  $csvData .= trim($studentRecordArray[$i]['universityRollNo']).',';
			  $csvData .= trim($studentRecordArray[$i]['studentName']).',';
			  $csvData .= trim($studentRecordArray[$i]['fatherName']).',';
			  $csvData .= parseCSVComments($studentRecordArray[$i]['dateOfBirth']).',';
			  $csvData .= 'Yes';
			  $csvData .= "\n ";
	  }
	}

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'StudentRollNoReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>