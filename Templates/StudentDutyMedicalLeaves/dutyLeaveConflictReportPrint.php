<?php 
//This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");      
set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $eventId=trim($REQUEST_DATA['eventId']);
    $showConflict=trim($REQUEST_DATA['showConflict']);
    if($showConflict==1){
        $showConflictString='Conflicted Data';
    }
    else if($showConflict==0){
        $showConflictString='Non Conflicted Data';
    }
    else{
        $showConflictString='Both';
    }
    $labelName=trim($REQUEST_DATA['labelName']);
    $className=trim($REQUEST_DATA['className']);
    $eventName=trim($REQUEST_DATA['eventName']);
    
    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Duty Leave Conflict Report');
    $reportManager->setReportInformation("Search By : Time Table : ".$labelName."  Class : ".$className."  Event : ".$eventName."  Show : ".$showConflictString);
    
    $reportTableHead                 = array();
    $reportTableHead['srNo']         = array('#','width="2%"', "align='center' ");
    $reportTableHead['eventTitle']   = array('Event','width=8% align="left"', 'align="left"');
    $reportTableHead['subjectCode']  = array('Subject','width=10% align="left"', 'align="left"');
    $reportTableHead['rollNo']       = array('Roll No.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['studentName']  = array('Name','width="14%" align="left" ', 'align="left"');
    $reportTableHead['dutyDate']     = array('Date','width="6%" align="center" ', 'align="center"');
    $reportTableHead['periodNumber'] = array('Period','width="5%" align="left" ', 'align="left"');
    
    
    
    if($showConflict!=0){
      $reportTableHead['conflictedWith'] = array('Conflicted with','width="12%" align="left" ', 'align="left"');  
    }
    $reportTableHead['dutyLeaveStatus'] = array('Status','width="5%" align="left" ', 'align="left"');
    
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    
    $valueArray=array();
    
    if($classId=='' or $labelId==''){
        //show the report
        $reportManager->setReportData($reportTableHead, $valueArray);
        $reportManager->showReport();
        die;
    }


//to fetch student duty leave status    
function getDutyLeaveStatus($key){
    global $globalDutyLeaveStatusArray;
    $ret=$globalDutyLeaveStatusArray[$key];
    if($ret==''){
        $ret=NOT_APPLICABLE_STRING;
    }
    return $ret;
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
          $dutyRecordArray[$i]['conflictedWith']='<b>Bulk Attendance</b>';
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
          $dutyRecordArray[$i]['conflictedWith']='<b>Daily Attendance</b>';
        }
      }
      
      if($isConflicted==0){       
		  //check conflict with medical leave
		  $medicalArray=$dutyManager->checkStudentMedicalLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate);
		  if($medicalArray[0]['totalRecords']!=0){
		 	$isConflicted=3;
		    $dutyRecordArray[$i]['conflictedWith']='<b>Medical Leave</b>';
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
    
    //show the report
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

// $History: testTypePrint.php $
?>
