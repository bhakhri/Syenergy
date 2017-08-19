 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    set_time_limit(0); 
    global $FE;  
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;           
    if($sessionHandler->getSessionVariable('RoleId')==3) {
      UtilityManager::ifParentNotLoggedIn(true);  
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else if($sessionHandler->getSessionVariable('RoleId')==4) {
      UtilityManager::ifStudentNotLoggedIn(true);   
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else {
       UtilityManager::ifNotLoggedIn(true);  
       $studentId= $REQUEST_DATA['studentId'];          
    }
    UtilityManager::headerNoCache();

 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    require_once(MODEL_PATH."/StudentManager.inc.php");    
    $studentManager = StudentManager::getInstance(); 
 
    require_once(MODEL_PATH."/PercentageWiseReportManager.inc.php");    
    $percentageWiseReportManager = PercentageWiseReportManager::getInstance();             
    
   //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 
       }
    }
 
    global $sessionHandler;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName1';
    $sortField1= $sortField;
    if($sortField1 =='subjectName1') {
      $sortField1 = "subjectName";
    }
    $orderBy = " $sortField1 $sortOrderBy"; 
    
    $attendance = trim($sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'));
    $toDate = $REQUEST_DATA['startDate2'];
    $classId = $REQUEST_DATA['classId'];
    $consolidatedView = $REQUEST_DATA['consolidatedView'];
    
    $showDate ='';
    $where = " AND att.studentId = '$studentId' ";
    if($classId!=0) {
      $where .= " AND att.classId = '$classId' "; 
    }
    if($toDate!='') {
      $where .= " AND att.toDate <= '$toDate' ";
      $showDate = "\nShow Attendance Upto,".parseCSVComments(UtilityManager::formatDate($toDate));   
    }

    $consolidated='1';
    if($REQUEST_DATA['consolidatedView']=='1') {
      $consolidated='';
    }    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate); 
    
    $csvData ='';
    $studentNameArray = $studentManager->getSingleField('student', "CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName ", "WHERE studentId  = $studentId");
    $studentName = $studentNameArray[0]['studentName'];
   
    $csvData = "For ".parseCSVComments($studentName);
    $csvData .= $showDate;
    $csvData .="\n";   
    if($consolidated==''){
       $csvData .="#,Subject,Study Period,Group,Teacher,From,To,Attended,Duty Leaves,Delivered,%age";
    }
    else {
       $csvData .="#,Subject,Study Period,Teacher,From,To,Attended,Duty Leaves, Medical Leaves, Delivered,%age";
    }
    $csvData .="\n";

    //$recordArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($where,$orderBy,$consolidated);
    $recordArray = $percentageWiseReportManager->getFinalAttendance($where,$orderBy,'',$consolidated,'');
    for($i=0; $i<count($recordArray); $i++) {
       $per = number_format($recordArray[$i]['per'],2,'.','');
       $recordArray[$i]['fromDate'] = UtilityManager::formatDate($recordArray[$i]['fromDate']);    
       $recordArray[$i]['toDate'] = UtilityManager::formatDate($recordArray[$i]['toDate']);
       $recordArray[$i]['per'] = $per;
      
       $csvData .= ($i+1).",";
       $csvData .= parseCSVComments($recordArray[$i]['subjectName1']).",";
       $csvData .= parseCSVComments($recordArray[$i]['periodName']).",";
       if($consolidated==''){ 
         $csvData .= parseCSVComments($recordArray[$i]['groupName']).",";
       }
       $csvData .= parseCSVComments($recordArray[$i]['employeeName']).",";
       $csvData .= parseCSVComments($recordArray[$i]['fromDate']).",";
       $csvData .= parseCSVComments($recordArray[$i]['toDate']).",";
       $csvData .= parseCSVComments($recordArray[$i]['attended']).",";
       $csvData .= parseCSVComments($recordArray[$i]['leaveTaken']).",";
       if($consolidated=='1') {
       	$csvData .= parseCSVComments($recordArray[$i]['medicalLeaveTaken']).",";
       }
       $csvData .= parseCSVComments($recordArray[$i]['delivered']).",";   
       $csvData .= parseCSVComments($recordArray[$i]['per']).",";
       $csvData .= "\n";
    } 
    
    if(count($recordArray)==0) {
      $csvData .= ",,,No Data Found"; 
    }
    
    UtilityManager::makeCSV($csvData,'StudentAttendanceReport.csv');   
    die;         

?>
