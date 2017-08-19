 <?php
//This file is used to export generatedPO to excel sheet.
//
// Created on : 19.Nov.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
ini_set("memory_limit","250M");        
set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
    $dutyManager = DutyLeaveManager::getInstance();

    /////////////////////////

	$rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
	$eventId = add_slashes(trim($REQUEST_DATA['eventId']));
	$statusId = add_slashes(trim($REQUEST_DATA['statusId']));
	$classId = add_slashes(trim($REQUEST_DATA['classId']));


	$condition ='';
	if($rollNo==''){
     echo ENTER_ROLL_NO_REG_NO_UNI_NO;
    }

	if($rollNo!=""){
       $condition .=" AND (s.rollNo LIKE '$rollNo' OR s.regNo LIKE '$rollNo' OR s.universityRollNo LIKE '$rollNo') ";
    }

	if($eventId!="-1"){
       $condition .=" AND dl.eventId = '$eventId' ";
    }

    if($statusId!="-1"){
       $condition .=" AND dl.rejected= '$statusId' ";
    }

	if($classId != "-1") {
		$condition .= " And dl.classId= '$classId' ";
	}

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    $dutyTotalRecordArray = $dutyManager->getStudentDutyLeaveCount($condition);
	$dutyRecordArray = $dutyManager->getStudentDutyLeave($condition,$limit,$orderBy);
	$cnt = count($dutyRecordArray);

	global $globalDutyLeaveStatusArray;
    $csvData  ='';
	$csvData .="\n";
    $csvData .="#,Event,Subject,Teacher,Date,Period,Status";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
	   $dutyRecordArray[$i]['dutyDate'] = UtilityManager::formatDate($dutyRecordArray[$i]['dutyDate']);
       if($dutyRecordArray[$i]['rejected'] != -1){
         $dutyRecordArray[$i]['rejected'] = $globalDutyLeaveStatusArray[$dutyRecordArray[$i]['rejected']];
       }
       else{
         $dutyRecordArray[$i]['rejected']=$globalDutyLeaveStatusArray[DUTY_LEAVE_UNRESOLVED];
       }
       $csvData .= ($i+1).",";
	   $csvData .= $dutyRecordArray[$i]['eventTitle'].",";
	   $csvData .= $dutyRecordArray[$i]['subjectCode'].",";
	   $csvData .= $dutyRecordArray[$i]['employeeName'].",";
	   $csvData .= $dutyRecordArray[$i]['dutyDate'].",";
	   $csvData .= $dutyRecordArray[$i]['periodNumber'].",";
       $csvData .= $dutyRecordArray[$i]['rejected'].",";
	   $csvData .= "\n";
	}
    if($cnt == 0){
	  $csvData .=" No Data Found ";
	}
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'StudentDutyLeaveReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
?>