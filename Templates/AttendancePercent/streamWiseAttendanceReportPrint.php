<?php
//--------------------------------------------------------
//This file returns the array of of Percentage Wise attendance report
// Author :Aditi Miglani
// Created on : 8-Nov-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/StreamWiseAttendanceManager.inc.php");
    $streamWiseAttendanceManager = StreamWiseAttendanceManager::getInstance();
  
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    global $sessionHandler;

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();


    $labelId = $REQUEST_DATA['labelId'];
    $fromDate = $REQUEST_DATA['fromDate'];  
    $toDate = $REQUEST_DATA['toDate'];  

   

    //Fetch Report Header Details
    $timeTableNameArray =  $studentManager->getSingleField('time_table_labels','labelName',"WHERE timeTableLabelId = $labelId");
	$labelName = $timeTableNameArray[0]['labelName'];
      
    $search = 'For <B>Time Table:</B>'.$labelName ;

    $headArray = $sessionHandler->getSessionVariable('IdToStreamStudentHeader');
    $valueArray = array_merge($sessionHandler->getSessionVariable('IdToStreamStudentData'));

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Stream Wise Attendance Report Print');
    $reportManager->setReportInformation($search);
    
    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']            =    array('#',             'width="2%"  align="left"', "align='left'");

   for($i=1;$i<count($headArray);$i++) {
     $headLabel = $headArray[$i]['headLabel'];
     $headName = $headArray[$i]['headName'];
     $reportTableHead[$headName]        =    array("$headLabel", 'width=15%   align="left" ','align="left" ');
   }

    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
    
?>    
 
