<?php 
// This file is used as csv version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");      
set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

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

function getDutyLeaveStatus($key){
    global $globalDutyLeaveStatusArray;
    $ret=$globalDutyLeaveStatusArray[$key];
    if($ret==''){
        $ret=NOT_APPLICABLE_STRING;
    }
    return $ret;
}

    $csvData = "#, Event, Subject, Roll No., Name, Date , Period";

    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $eventId=trim($REQUEST_DATA['eventId']);
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
       header('Content-Disposition: attachment;  filename="dutyLeaveConflictReport.csv"');
       header("Content-Transfer-Encoding: binary\n");
       echo $csvData;
       die;     
    }
    
    require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
    $dutyManager = DutyLeaveManager::getInstance(); 

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $filter =' AND dl.classId='.$classId;
    
    if($eventId!="-2"){
     $filter .=' AND de.eventId='.$eventId;
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $dutyRecordArray = $dutyManager->dutyLeaveList($filter,$limit,$orderBy);
    $cnt = count($dutyRecordArray);
    $totalAllowed=0;
    for($i=0;$i<$cnt;$i++) {
       
       $isConflicted=0; //no conflict
       $excludeArray=array();
       $selectElementId='';
        
       $studentId=$dutyRecordArray[$i]['studentId'];
       $classId=$dutyRecordArray[$i]['classId'];
	   $groupId=$dutyRecordArray[$i]['groupId'];
	   $subjectId=$dutyRecordArray[$i]['subjectId'];
       $periodId=$dutyRecordArray[$i]['periodId'];
       $dutyDate=$dutyRecordArray[$i]['dutyDate'];
       $eventId=$dutyRecordArray[$i]['eventId'];
       $dateArray=explode(',',$dutyDate);
       $daysOfWeek=date('N',mktime(0, 0, 0, $dateArray[1]  , $dateArray[2], $dateArray[0]));
       
       if(trim($dutyRecordArray[$i]['rollNo'])==''){
           $dutyRecordArray[$i]['rollNo']=NOT_APPLICABLE_STRING;
       }
       
       $dutyRecordArray[$i]['dutyDate']=UtilityManager::formatDate($dutyRecordArray[$i]['dutyDate']);
       $dutyRecordArray[$i]['conflictedWith']='No Conflict';
       
	   /*
       //find group info for this student
       $groupArray=$dutyManager->getStudentGroupInfo($studentId,$classId);
       if(is_array($groupArray) and count($groupArray)>0){
          $groupIds=UtilityManager::makeCSList($groupArray,'groupId');
       }
       else{
          $groupIds=-1; 
       }
       
       
       //find subjectId for this student
       $subjectArray=$dutyManager->getStudentSubjectInfoFromTimeTable($studentId,$classId,$groupIds,$periodId,$daysOfWeek,$labelId);
       if(is_array($subjectArray) and count($subjectArray)>0){
           $subjectId=$subjectArray[0]['subjectId'];
           $dutyRecordArray[$i]['subjectCode']=$subjectArray[0]['subjectCode'];
       }
       else{
           $subjectId=-1;
           $dutyRecordArray[$i]['subjectCode']=NOT_APPLICABLE_STRING;
           continue;
       }
       */
       
       $lectureDelivered=0;
       $lectureAttended=0;
       //check conflict with bulk attendance
       $bulkAttendanceArray=$dutyManager->getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$dutyDate);
       if($bulkAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=1;  //conflict with bulk attendance
          $dutyRecordArray[$i]['conflictedWith']='Bulk Attendance';
          //fetch student's lecture delivered and attended details
          $studentAttendanceArray=$dutyManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
          $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
          $lectureAttended=round($studentAttendanceArray[0]['attended']);
       }
       
      if($isConflicted==0){ 
        //check conflict with daily attendacen
        $dailyAttendanceArray=$dutyManager->getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$dutyDate);
        if($dailyAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=2; //conflict with daily attendance
          $dutyRecordArray[$i]['conflictedWith']='Daily Attendance';
        }
      }
      
      if($isConflicted==0){       
		  //check conflict with medical leave
		  $medicalArray=$dutyManager->checkStudentMedicalLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate);
		  if($medicalArray[0]['totalRecords']!=0){
		 	$isConflicted=3;
		    $dutyRecordArray[$i]['conflictedWith']='Medical Leave';
		  }
	  }
      
      if($showConflict==1){ //if user wants to see conflicted results
          if($isConflicted!=0){
             $dutyRecordArray[$i]['dutyLeaveStatus']=getDutyLeaveStatus($dutyRecordArray[$i]['rejected']);
             $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
             //createJSON($valueArray);
             $totalAllowed++;
          }
      }
      else if($showConflict==0){//if user wants to see non-conflicted results
          if($isConflicted==0){
             $dutyRecordArray[$i]['dutyLeaveStatus']=getDutyLeaveStatus($dutyRecordArray[$i]['rejected']);
             $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
             $totalAllowed++;
          }
      }
      else{
          $dutyRecordArray[$i]['dutyLeaveStatus']=getDutyLeaveStatus($dutyRecordArray[$i]['rejected']);
          $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
          $totalAllowed++;
      }
      
    }
    
    foreach($valueArray as $record) {
       $csvData .= $record['srNo'].', '.parseCSVComments($record['eventTitle']).', '.parseCSVComments($record['subjectCode']).', '.parseCSVComments($record['rollNo']).','.parseCSVComments($record['studentName']).', '.parseCSVComments($record['dutyDate']).','.parseCSVComments($record['periodNumber']);
       if($showConflict!=0){
         $csvData .=','.parseCSVComments($record['conflictedWith']); 
       }
       $csvData .=','.parseCSVComments($record['dutyLeaveStatus']); 
       $csvData .= "\n";
    }
    

	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="dutyLeaveConflictReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;
// $History: testTypeCSV.php $
?>
