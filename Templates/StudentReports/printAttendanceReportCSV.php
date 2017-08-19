 <?php 
//This file is used as CSV version for display Attendance Report
//
// Author :Jaineesh
// Created on : 02.06.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $results = CommonQueryManager::getInstance()->getAttendanceCode($conditions);
    
    $attendanceCodeWise='';
    $csvField='';
    for($i=0;$i<count($results);$i++) {
      $id = $results[$i]['attendanceCodeId'];   
      $str = $results[$i]['attendanceCode'];  
      $csvField .=",$str";
      $attendanceCodeWise .= ", FORMAT(SUM(IF(att.isMemberOfClass=0,0,
                                      IF(ac.attendanceCodeId=$id,
                                         IF(att.attendanceType=2,IF((ac.attendanceCodePercentage/100)=0,1,ac.attendanceCodePercentage/100),att.lectureAttended),0))),1) AS att".$id;
    }

    
    $subjectId = $REQUEST_DATA['subjectId'];
	$sortField = $REQUEST_DATA['sortField'];
	if ($sortField == 'rollNo') {
		$sortField = 'numericRollNo';
	}

	$classId = $REQUEST_DATA['degree'];
    
    $reportRecordArray = $studentReportManager->getAttendanceData($classId, $subjectId, $sortField, $REQUEST_DATA['sortOrderBy'],'',$attendanceCodeWise);
    

	$recordCount = count($reportRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData  ="#,Roll No.,Univ. Roll No.,Student Name,Lect. Delivered,Lect. Attended,Percentage,Attendance Code";
    $csvData .="\n";
    $csvData .=",,,,,,".$csvField;  
    $csvData .="\n";   
    
    for($i=0;$i<$recordCount;$i++) {
    	$reportRecordArray[$i]['Percentage'] = "0.00";
		if($reportRecordArray[$i]['lectureAttended'] > 0 && $reportRecordArray[$i]['lectureDelivered'] > 0 ) {
			$reportRecordArray[$i]['Percentage'] = "".round($reportRecordArray[$i]['lectureAttended'] / $reportRecordArray[$i]['lectureDelivered']*100,1)."";
		}
		else {
			$reportRecordArray[$i]['Percentage'] = $reportRecordArray[$i]['Percentage'];
		}

		$csvData .= ($i+1).",";
		$csvData .= $reportRecordArray[$i]['rollNo'].",";
		$csvData .= $reportRecordArray[$i]['universityRollNo'].",";
		$csvData .= $reportRecordArray[$i]['studentName'].",";
		$csvData .= $reportRecordArray[$i]['lectureDelivered'].",";
		$csvData .= $reportRecordArray[$i]['lectureAttended'].",";
		$csvData .= $reportRecordArray[$i]['Percentage'];
        for($j=0;$j<count($results);$j++) {
          $id = 'att'.$results[$j]['attendanceCodeId'];  
          $csvData .= ",".$reportRecordArray[$j][$id];
        }
		$csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'AttendanceReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>