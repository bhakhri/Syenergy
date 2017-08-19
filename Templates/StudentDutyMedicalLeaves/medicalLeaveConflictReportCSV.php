<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");      
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/MedicalLeaveManager.inc.php");
    $medicalLeaveManager = MedicalLeaveManager::getInstance(); 

//to parse csv values    
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

function getMedicalLeaveStatus($key){
    global $globalMedicalLeaveStatusArray;
    $ret=$globalMedicalLeaveStatusArray[$key];
    if($ret==''){
        $ret=NOT_APPLICABLE_STRING;
    }
    return $ret;
}

    $csvData = "#, Subject, Roll No., Name, Date , Period";

    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $showConflict=trim($REQUEST_DATA['showConflict']);

    if($showConflict!=0){
        $csvData .=", Conflicted with";
    }
    $csvData .=", Action \n";

    if($classId=='' or $labelId==''){
       ob_end_clean();
       header("Cache-Control: public, must-revalidate");
       header('Content-type: application/octet-stream; charset=utf-8');
       header("Content-Length: " .strlen($csvData) );
       header('Content-Disposition: attachment;  filename="medicalLeaveConflictReport.csv"');
       header("Content-Transfer-Encoding: binary\n");
       echo $csvData;
       die;     
    }
    
    

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $filter =' AND ml.classId='.$classId;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $medicalLeaveRecordArray = $medicalLeaveManager->medicalLeaveList($filter,$limit,$orderBy);
    $cnt = count($medicalLeaveRecordArray);
    $totalAllowed=0;
    for($i=0;$i<$cnt;$i++) {
       
       $isConflicted=0; //no conflict
       $excludeArray=array();
       $selectElementId='';
        
       $studentId=$medicalLeaveRecordArray[$i]['studentId'];
       $classId=$medicalLeaveRecordArray[$i]['classId'];
       $groupId=$medicalLeaveRecordArray[$i]['groupId'];
       $subjectId=$medicalLeaveRecordArray[$i]['subjectId'];
       $periodId=$medicalLeaveRecordArray[$i]['periodId'];
       $medicalLeaveDate=$medicalLeaveRecordArray[$i]['medicalLeaveDate'];
       $subjectId=$medicalLeaveRecordArray[$i]['subjectId'];
       $dateArray=explode(',',$medicalLeaveDate);
       $daysOfWeek=date('N',mktime(0, 0, 0, $dateArray[1]  , $dateArray[2], $dateArray[0]));
       
       if(trim($medicalLeaveRecordArray[$i]['rollNo'])==''){
           $medicalLeaveRecordArray[$i]['rollNo']=NOT_APPLICABLE_STRING;
       }
       
       $medicalLeaveRecordArray[$i]['medicalLeaveDate']=UtilityManager::formatDate($medicalLeaveRecordArray[$i]['medicalLeaveDate']);
       $medicalLeaveRecordArray[$i]['conflictedWith']='No Conflict';
       
       
       $lectureDelivered=0;
       $lectureAttended=0;
       //check conflict with bulk attendance
       $bulkAttendanceArray=$medicalLeaveManager->getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$medicalLeaveDate);
       if($bulkAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=1;  //conflict with bulk attendance
          $medicalLeaveRecordArray[$i]['conflictedWith']='Bulk Attendance';
          //fetch student's lecture delivered and attended details
          $studentAttendanceArray=$medicalLeaveManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
          $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
          $lectureAttended=round($studentAttendanceArray[0]['attended']);
       }
       
      if($isConflicted==0){ 
        //check conflict with daily attendacen
        $dailyAttendanceArray=$medicalLeaveManager->getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$medicalLeaveDate);
        if($dailyAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=2; //conflict with daily attendance
          $medicalLeaveRecordArray[$i]['conflictedWith']='Daily Attendance';
        }
      }
      
      if($isConflicted==0){       
		  //check conflict with duty leave
		  $dutyArray=$medicalLeaveManager->checkStudentDutyLeaveExistence($studentId,$classId,$subjectId,$periodId,$medicalLeaveDate);
		  if($dutyArray[0]['totalRecords']!=0){
		 	$isConflicted=3;
		    $medicalLeaveRecordArray[$i]['conflictedWith']='Duty Leave';
		  }
	  }
      
      if($showConflict==1){ //if user wants to see conflicted results
          if($isConflicted!=0){
             $medicalLeaveRecordArray[$i]['medicalLeaveStatus']=getMedicalLeaveStatus($medicalLeaveRecordArray[$i]['approvedStatus']);
             $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
             //createJSON($valueArray);
             $totalAllowed++;
          }
      }
      else if($showConflict==0){//if user wants to see non-conflicted results
          if($isConflicted==0){
             $medicalLeaveRecordArray[$i]['medicalLeaveStatus']=getMedicalLeaveStatus($medicalLeaveRecordArray[$i]['approvedStatus']);
             $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
             $totalAllowed++;
          }
      }
      else{
          $medicalLeaveRecordArray[$i]['medicalLeaveStatus']=getMedicalLeaveStatus($medicalLeaveRecordArray[$i]['approvedStatus']);
          $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
          $totalAllowed++;
      }
      
    }
    
    foreach($valueArray as $record) {
       $csvData .= $record['srNo'].', '.parseCSVComments($record['subjectCode']).', '.parseCSVComments($record['rollNo']).','.parseCSVComments($record['studentName']).', '.parseCSVComments($record['medicalLeaveDate']).','.parseCSVComments($record['periodNumber']);
       if($showConflict!=0){
         $csvData .=','.parseCSVComments($record['conflictedWith']); 
       }
       $csvData .=','.parseCSVComments($record['medicalLeaveStatus']); 
       $csvData .= "\n";
    }
    

	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="medicalLeaveConflictReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;
?>
