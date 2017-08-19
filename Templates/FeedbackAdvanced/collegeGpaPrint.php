<?php 
//This file is used as printing version for TestType.
//
// Author :Abhay Kant
// Created on : 15-9-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$recordArray=array();
    
    	 global $sessionHandler;
    	 
    	 $optionRecordArray = $sessionHandler->getSessionVariable('IdToFeedbackReportOption');
         $valueArray   = $sessionHandler->getSessionVariable('IdToFeedbackReportData');
         
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Feedback College GPA Report (Advanced)');
    $reportManager->setReportInformation("Time Table : ".trim($REQUEST_DATA['timeTableName'])." Label : ".trim($REQUEST_DATA['labelName']).'<br/>'.$visibleFromToString);
	 
	$reportTableHead					   =   array();
	$reportTableHead['srNo']	  = array('#',	     'width="2%"', "align='center' ");
	$reportTableHead['questionName']  = array('Question','width="20%" align="left"', "align='left' ");
        for($i=0;$i<count($optionRecordArray);$i++) {
           $optValue = 'optStudent'.($i+1);
           $optText = $optionRecordArray[$i]['optionLabel'];
	   $reportTableHead[$optValue]  = array("$optText",'width="5%"', "align='center' ");           
        }    
       	$reportTableHead['weightedAvg']  = array('Weighted Avg.','width="5%"', "align='center' ");
	$reportTableHead['response']  = array('Response','width="5%"', "align='center' ");
	$reportTableHead['gpa']  = array('GPA','width="5%"', "align='center' ");
	
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();


//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created college gpa report for feedback modules
?>
