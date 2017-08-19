<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php   
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRegistrationReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn();
    }
    else{
      UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache(); 
    
    require_once(MODEL_PATH . "/MenteesManager.inc.php");
    $menteesManager  = MenteesManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"'; 
         } 
         else {
            return chr(160).$comments; 
         }
    }
     
    $userId = trim($REQUEST_DATA['mentorName']);
    $rollNo  = trim($REQUEST_DATA['rollNo']);
    $studentName  = trim($REQUEST_DATA['studentName']);
    $registered  = trim($REQUEST_DATA['registered']);

    if($userId=='') {
      $userId='0';  
    }
    
    if($registered=='') {
      $registered='3';  
    }
    
    
    $csvData ='';
    if($userId!='0') {
      $employeeNameArray = $menteesManager->getEmployeeName($userId);
      $employeeNames = $employeeNameArray[0]['employeeNames'];
      if($employeeNames=='') {
        $employeeNames=NOT_APPLICABLE_STRING;  
      }
      $csvData .="Mentor Name,".parseCSVComments($employeeNames)."\n";
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';
    }
    if($sortField=='undefined') {
      $sortField='className';
    }
    $orderBy = " $sortField $sortOrderBy";
    
    
    $having = "";
    $filter = "";
    if($rollNo!='') {
      $filter .= " AND s.rollNo LIKE '$rollNo%'" ;  
      $csvData .="Roll No.,".parseCSVComments($rollNo)."\n";
    }
    
    if($studentName!='') {
      $filter .= " AND CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%'" ;  
      $csvData .="Student Name,".parseCSVComments($studentName)."\n";
    }
    if($userId!='0') {
      $filter .= " AND sm.userId = '$userId' ";
    }
    
    if($registered=='1') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') != '0000-00-00' ";  
      $search .="Registration Status:&nbsp;Registered<br>";
      $csvData .="Registration Status,Registered\n";
    }
    else  if($registered=='2') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') = '0000-00-00' ";  
      $csvData .="Registration Status,Pending\n";
    }

    //$record = $menteesManager->getstudentRegistrationDetails($filter,$having,$classIds, $orderBy);
    $record = $menteesManager->getstudentRegistrationDetails($filter,$orderBy);   
    $cnt = count($record);
    $csvData .= "#,Class Name,Roll No.,Student Name,Father's Name,Student's Mobile No.,Mentor Name,Date of Reg.\n";
    for($i=0;$i<$cnt;$i++) {
      $regDate = UtilityManager::formatDate($record[$i]['registrationDate']);
      $csvData .= ($i+1);
      $csvData .= ','.parseCSVComments($record[$i]['className']);
      $csvData .= ','.parseCSVComments($record[$i]['universityRollNo']);
      $csvData .= ','.parseCSVComments($record[$i]['studentName']);
      $csvData .= ','.parseCSVComments($record[$i]['fatherName']);
      $csvData .= ','.parseCSVComments($record[$i]['studentMobileNo']);
      $csvData .= ','.parseCSVComments($record[$i]['employeeName1']);
      $csvData .= ','.parseCSVComments($regDate);
      $csvData .= "\n";
    }
    
    if($i==0) {
      $csvData .= ",,,No Data Found";    
    }
    
    UtilityManager::makeCSV($csvData,'StudentRegistrationList.csv');
    
    die;

?>
