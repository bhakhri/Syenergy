 <?php 
//This file is used as printing version for Attendance Deduct
//
// Author :Prveen Sharma
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");    
    define('MODULE','AttendanceIncentiveDetails');    
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    $incentiveDetailId = trim($REQUEST_DATA['incentiveDetailId']);
    if($incentiveDetailId==1){
	$weightageFormat=1;
		
	}
	else
		{
		$weightageFormat=2;
		}
    $assignStudentIncentive = AssignFinalGradeManager::getInstance();    
    
    $foundArray =   $assignStudentIncentive->getIncentiveDetailListPrint($weightageFormat);
    
    $valueArray = array();
    for($i=0; $i<count($foundArray); $i++ ) {
      $valueArray[] = array_merge(array('srNo' => ($i+1) ),$foundArray[$i]);
    }
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);                   
    $reportManager->setReportWidth(600);
    $reportManager->setReportHeading('Attendance Incentive Detail Report');
    $reportManager->setReportInformation("AS On ".$formattedDate);
                    
    $reportTableHead                            =    array();
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']                    =    array('SrNo.',            'width="10%" align="center"', "align='center'"); 
    $reportTableHead['attendancePercentageFrom']    =    array('Attendance % From',  'width=40% align="left" ','align="left" ');
 $reportTableHead['attendancePercentageTo']    =    array('Attendance % To',  'width=40% align="left" ','align="left" ');
	if($weightageFormat==1){
         $reportTableHead['weigthage']          =    array('Marks Weightage',    'width="40%" align="left" ','align="left"');
	}
	else {
		$reportTableHead['weigthage']           =    array('Discount Amount',    'width="40%" align="left" ','align="left"');
	}
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
?> 
