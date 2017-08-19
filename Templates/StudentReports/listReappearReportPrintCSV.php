<?php 
// This File contains the show details of Student Internal Subject Re-appear detail report print CSV
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  //to parse csv values    
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
   require_once(MODEL_PATH . "/StudentManager.inc.php");
   
   define('MODULE','DisplayStudentReappearReport');
   define('ACCESS','view');
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();
   
    $studentManager = StudentManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    
    $conditions = "";
    
    $reappearClassId  = add_slashes($REQUEST_DATA['reappearClassId']);   
    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate = add_slashes($REQUEST_DATA['endDate']);
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
    $subjectId = add_slashes(trim($REQUEST_DATA['subjectId']));
    $reappearStatusId = add_slashes(trim($REQUEST_DATA['reappearStatusId']));    
    $assignmentChk = add_slashes(trim($REQUEST_DATA['assignmentChk']));
    $midSemesterChk = add_slashes(trim($REQUEST_DATA['midSemesterChk']));
    $attendanceChk = add_slashes(trim($REQUEST_DATA['attendanceChk']));
    $studentDetained = add_slashes(trim($REQUEST_DATA['studentDetained']));
    
    if($reappearClassId!='') {
       $conditions .= " AND reapperClassId IN ($reappearClassId) ";
    }
    else {
       $conditions .= " AND reapperClassId IN (0) ";  
    }
    
    if($subjectId!='' ) {
      $conditions .= " AND sub.subjectId IN ($subjectId) ";
    }
    
    
    if($startDate!='' && $endDate =='')  {
       $conditions .= " AND dateOfEntry >='$startDate' ";
       $fromDate1 = UtilityManager::formatDate($startDate);
    }

    if($startDate=='' && $endDate!='') {
        $conditions .= " AND dateOfEntry <='$endDate'";
        $toDate1 =  UtilityManager::formatDate($endDate);
    }

    if($startDate!='' && $endDate!=''){
       $conditions .= " AND ((dateOfEntry BETWEEN '$startDate' AND '$endDate') OR (dateOfEntry BETWEEN '$startDate' AND '$endDate'))";
       
       $fromDate1 = UtilityManager::formatDate($startDate);
       $toDate1 =  UtilityManager::formatDate($endDate);
    }
    
    if($rollNo!='') {
      $conditions .= " AND rollNo LIKE '$rollNo%' ";
    }
    
    $check="Assignment Work, Mid Semester Tests, Attendance";
    if($assignmentChk!='') {
      $conditions .= " AND sr.assignmentStatus = '$assignmentChk' ";
      $check = "Assignment Work";
    } 
    
    if($midSemesterChk!='') {
      $conditions .= " AND sr.midSemesterStatus = '$midSemesterChk'  ";
      $check .= ", Mid Semester Tests";
    }
    
    if($attendanceChk != '') {
      $conditions .= " AND sr.attendanceStatus = '$attendanceChk' ";
      $check .= ", Attendance ";
    }
    
    if($studentDetained != '') {
      $conditions .= " AND sr.detained = '$studentDetained' ";        
    }
    
    $heading1 = "";            
    if($reappearStatusId!='' ) {
      $conditions .= " AND sr.reppearStatus IN ($reappearStatusId) ";
      $statusArr = explode(',',$reappearStatusId);
      
      if(count($statusArr)>0) { 
        global $reppearStatusArr; 
        
        for($i=0; $i<count($statusArr); $i++) {
          $id = $statusArr[$i];   
          if($i==0) {
             $heading1 .= $reppearStatusArr[$id];
          }
          else {
            $heading1 .= ", ".$reppearStatusArr[$id];
          }
        }
      } 
    }
    else {
        $heading1 = "All";
    }
    
    $heading = "Student Internal Re-appear Status,".parseCSVComments($heading1);
    $heading .= "\nCause of Detention / Re-appear,".parseCSVComments($check);
    if($studentDetained != '') { 
      $heading .= "\nDetained Student,Yes";
    }
    $heading .= "\nDate From,$fromDate1,To,$toDate1";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = "ORDER BY $sortField $sortOrderBy ";

    $record = $studentManager->getClasswiseReappearDetails($conditions,$orderBy);
    $cnt = count($record);
    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', ", ", $comments);
       $comments = str_ireplace('<br>', ", ", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
          return '"'.$comments.'"'; 
       } 
       else {
          return $comments.chr(160); 
       }
    } 
    $csvData = '';   
    $csvData .= $heading."\n";
    $csvData .= "Sr. No., Student Name, Current Class Name, Roll No., Univ. Roll No., Re-appear Class Name, Re-appear (Subject Code/Status) \n";
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface 
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['studentName']).",".parseCSVComments($record[$i]['currentClassName']);
       $csvData .= ",".parseCSVComments($record[$i]['rollNo']).",".parseCSVComments($record[$i]['universityRollNo']);
       $csvData .= ",".parseCSVComments($record[$i]['reappearClassName']).",".parseCSVComments($record[$i]['subjects'])." \n";
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="StudentInternalReappearReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: listReappearReportPrintCSV.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/19/10    Time: 12:08p
//Updated in $/LeapCC/Templates/StudentReports
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/18/10    Time: 4:12p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//

?>