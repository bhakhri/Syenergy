<?php 
// This File contains the show details of Student Internal Subject Re-appear detail report  print
//
// Author :Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
   require_once(MODEL_PATH . "/StudentManager.inc.php");
   require_once(BL_PATH . '/ReportManager.inc.php');
   
   define('MODULE','DisplayStudentReappearReport');
   define('ACCESS','view');
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();
   
   $studentManager = StudentManager::getInstance();
   $reportManager = ReportManager::getInstance();
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
    
    $heading = "<b>Student Internal Re-appear Status:</b>&nbsp;$heading1";
    $heading .= "<br><B>Cause of Detention / Re-appear:</b>&nbsp;$check";
    if($studentDetained != '') { 
      $heading .= "<br><B>Detained Student:</b>&nbsp;Yes";
    }
    $heading .= "<br><B>Date From:</b>&nbsp;$fromDate1&nbsp;&nbsp;<b>To</b>&nbsp;&nbsp;$toDate1";
                
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = "ORDER BY $sortField $sortOrderBy ";

    $recordArray = $studentManager->getClasswiseReappearDetails($conditions,$orderBy);
    
 	$cnt = count($recordArray);
	
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
      // add stateId in actionId to populate edit/delete icons in User Interface 
  	  $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Display Student Internal Re-appear Report');
    $reportManager->setReportInformation("$heading");
    
    
	$reportTableHead						   =  array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']			   =  array('#','width="3%" align="left" valign="top"', "align='left' valign='top' ");
    $reportTableHead['studentName']        =  array('Student Name','width=12% align="left" valign="top"', 'align="left" valign="top"');
	$reportTableHead['currentClassName']   =  array('Current Class Name','width=20% align="left" valign="top"', 'align="left" valign="top"');
	$reportTableHead['rollNo']             =  array('Roll No.','width="8%" align="left" valign="top"', 'align="left" valign="top"');
    $reportTableHead['universityRollNo']   =  array('Univ. Roll No.','width="12%" align="left" valign="top"', 'align="left" valign="top"');
    $reportTableHead['reappearClassName']  =  array('Re-appear Class Name','width=20% align="left" valign="top"', 'align="left" valign="top"');
    $reportTableHead['subjects']           =  array('Re-appear<br>Subject Code/Status','width=15% align="left" valign="top"', 'align="left" valign="top"');
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listReappearReportPrint.php $
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