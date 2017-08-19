 <?php 
//This file is used as printing version for offense.
//
// Author :Jaineesh
// Created on : 05-10-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance(); 
	$offenceId = $REQUEST_DATA['id'];
    require_once(MODEL_PATH . "/OffenseManager.inc.php");
    $offenseManager = OffenseManager::getInstance();
	$offenseArray = $offenseManager->getStudentOffenseDetail($offenceId);
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Studentwise Offense Report ');
    
	$offenseName = $offenseManager->getOffenseName($offenceId);
	
	$reportManager->setReportInformation("offense: ".$offenseName[0]['offenseName']);
	$recordCount = count($offenseArray);
	
	$offensePrintArray[] =  Array();
	if($recordCount >0 && is_array($offenseArray) ) { 
		for($i=0; $i<$recordCount; $i++ ) {
			$bg = $bg =='row0' ? 'row1' : 'row0';
			$offenseArray[$i]['offenseDate'] = strip_slashes($offenseArray[$i]['offenseDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING:UtilityManager::formatDate($offenseArray[$i]['offenseDate']);
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$offenseArray[$i]);
		
		}
	}
	
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['rollNo']			    =    array('Roll No. ',' width=10% align="left" ','align="left" ');
    $reportTableHead['className']			=    array('Class',' width="15%" align="left" ','align="left"');
    $reportTableHead['studentMobileNo']		=    array('Mobile No.',' width="10%" align="left" ','align="left"');
    $reportTableHead['studentEmail']		=    array('Email',' width="15%" align="left" ','align="left"');
    $reportTableHead['offenseDate']			=    array('Date',' width="15%" align="center" ','align="center"');
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//Modify By SAtinder
//
?>
