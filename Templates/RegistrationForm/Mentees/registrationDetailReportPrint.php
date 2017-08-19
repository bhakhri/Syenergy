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
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/MenteesManager.inc.php");
    $menteesManager  = MenteesManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

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
    
    if($userId!='0') {
      $employeeNameArray = $menteesManager->getEmployeeName($userId);
      $employeeNames = $employeeNameArray[0]['employeeNames'];
      if($employeeNames=='') {
        $employeeNames=NOT_APPLICABLE_STRING;  
      }
      $search="Mentor Name:&nbsp;$employeeNames<br>";
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
      $search .="Roll No.:&nbsp;$rollNo<br>"; 
    }
    if($studentName!='') {
      $filter .= " AND CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%'" ;  
      $search .="Student Name:&nbsp;$studentName<br>"; 
    }
    if($userId!='0') {
      $filter .= " AND sm.userId = '$userId' ";
    }
    
    if($registered=='1') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') != '0000-00-00' ";  
      $search .="Registration Status:&nbsp;Registered<br>";
    }
    else  if($registered=='2') {
      $filter .= " AND IFNULL(sr.registrationDate,'0000-00-00') = '0000-00-00' ";  
      $search .="Registration Status:&nbsp;Pending<br>";
    }
    
   
    $registrationRecordArray = $menteesManager->getstudentRegistrationDetails($filter,$orderBy);
    $cnt = count($registrationRecordArray);
    for($i=0;$i<$cnt;$i++) {
      $span1='';
      $span2='';
      $regDate = UtilityManager::formatDate($registrationRecordArray[$i]['registrationDate']);
      if($regDate=='--') {
        $span1='<span style="color: red;">'; 
        $span2='</span>';  
      }
      $registrationRecordArray[$i]['registrationDate'] = $span1.$regDate.$span2;
      $registrationRecordArray[$i]['className'] = $span1.$registrationRecordArray[$i]['className'].$span2;
      $registrationRecordArray[$i]['universityRollNo'] = $span1.$registrationRecordArray[$i]['universityRollNo'].$span2;
      $registrationRecordArray[$i]['studentName'] = $span1.$registrationRecordArray[$i]['studentName'].$span2;
      $registrationRecordArray[$i]['fatherName'] = $span1.$registrationRecordArray[$i]['fatherName'].$span2;
      $registrationRecordArray[$i]['studentMobileNo'] = $span1.$registrationRecordArray[$i]['studentMobileNo'].$span2;
      $registrationRecordArray[$i]['employeeName1'] = $span1.$registrationRecordArray[$i]['employeeName1'].$span2;
      
      $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),
                                       $registrationRecordArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Registration Report ');
    $reportManager->setReportInformation($search);    
    
    $reportTableHead                        =    array();
                //associated key          col.label,             col. width,      data align
    $reportTableHead['srNo']                 = array('#',                   'width="5%"  align="center"',  'align="center"');
    $reportTableHead['className']            = array('Class Name',          'width="15%" align="left"', 'align="left"');
    $reportTableHead['universityRollNo']     = array('Roll No.',            'width="15%" align="left"', 'align="left"');
    $reportTableHead['studentName']          = array('Student Name',        'width="15%" align="left"', 'align="left"');
    $reportTableHead['fatherName']           = array("Father's Name",       'width="15%" align="left"', 'align="left"');
    $reportTableHead['studentMobileNo']      = array("Student's Mobile No.",'width="15%" align="left"', 'align="left"');
    $reportTableHead['employeeName1']     = array('Mentor Name',        'width="12%" align="center"', 'align="center"');
    $reportTableHead['registrationDate']     = array('Date of Reg.',        'width="12%" align="center"', 'align="center"');
    
    
    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();


?>
