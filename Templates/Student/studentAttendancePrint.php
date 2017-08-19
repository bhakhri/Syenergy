<?php 
//This file is used as printing version for Subject To class.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    require_once(MODEL_PATH."/StudentManager.inc.php");    
    $studentManager = StudentManager::getInstance(); 
    
    require_once(MODEL_PATH."/PercentageWiseReportManager.inc.php");    
    $percentageWiseReportManager = PercentageWiseReportManager::getInstance();             
    
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
      $showDate = "<br>Show Attendance Upto ".UtilityManager::formatDate($toDate);  
    }

    $consolidated='1';
    if($REQUEST_DATA['consolidatedView']=='1') {
      $consolidated='';
    }    
    
    
    //$studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($where,$orderBy,$consolidated);
    $studentAttendanceArray = $percentageWiseReportManager->getFinalAttendance($where,$orderBy,'',$consolidated,'');
    for($i=0; $i<count($studentAttendanceArray); $i++) {
        $per = number_format($studentAttendanceArray[$i]['per'],2,'.','');
        $studentAttendanceArray[$i]['fromDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['fromDate']);    
        $studentAttendanceArray[$i]['toDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['toDate']);
        
        $studentAttendanceArray[$i]['per'] = $per;
        if($attendance!='') {
          if($studentAttendanceArray[$i]['per'] < $attendance) {
             $studentAttendanceArray[$i]['per'] = "<span style='color:#FF0000;text-decoration:underline;'>".$per."</span>";         
          }
        }
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),$studentAttendanceArray[$i]);
   }

   //$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

   $reportTableHead = array();
   //associated key                  col.label,            col. width,      data align        
   $reportTableHead['srNo']           =    array('#',            ' width="2%"  align="left"', "align='left'");
   $reportTableHead['subjectName1']   =    array('Subject',      ' width=20%   align="left" ','align="left" ');
   $reportTableHead['periodName']     =    array('Study Period', ' width="12%" align="left" ','align="left"');
   if($consolidated=='') {
     $reportTableHead['groupName']    =    array('Group',        ' width="12%" align="left" ','align="left"');
   }
   $reportTableHead['employeeName']   =    array('Teacher',      ' width=15%   align="left" ','align="left" ');
   $reportTableHead['fromDate']       =    array('From',         ' width="12%" align="center" ','align="center"');
   $reportTableHead['toDate']         =    array('To',           ' width="12%" align="center" ','align="center"');
   $reportTableHead['attended']       =    array('Attended',     ' width="10%" align="right" ','align="right"');
   $reportTableHead['leaveTaken']     =    array('Duty Leaves', ' width=10%   align="right" ','align="right" ');
   if($consolidated=='1') {
   $reportTableHead['medicalLeaveTaken']=  array('Medical Leaves', ' width=10%   align="right" ','align="right" ');
   }
   $reportTableHead['delivered']      =    array('Delivered',    ' width="10%" align="right" ','align="right"');
   $reportTableHead['per']            =    array('%age',         ' width="10%" align="right" ','align="right"');
   
   
   $studentNameArray = $studentManager->getSingleField('student', "CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName ", "WHERE studentId  = $studentId");
   $studentName = $studentNameArray[0]['studentName'];
   
   
   $reportManager->setReportWidth(800);
   $reportManager->setReportInformation("For ".$studentName.$showDate);
   $reportManager->setReportHeading("Attendance Detail Report");
   $reportManager->setRecordsPerPage(40);
   $reportManager->setReportData($reportTableHead, $valueArray);
   $reportManager->showReport();  

?> 
