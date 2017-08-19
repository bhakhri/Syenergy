<?php 
//  This File contains Student External Marks Reports Print
//
// Author :Parveen Sharma
// Created on : 29-Apr-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExternalMarksReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentManager = StudentReportsManager::getInstance();

require_once(BL_PATH . '/ReportManager.inc.php');
$reportManager = ReportManager::getInstance();

    
	$timeName = $REQUEST_DATA['timeName'];
    $className = $REQUEST_DATA['className'];
	$categoryName = $REQUEST_DATA['categoryName'];
	 
	$subjectName = $REQUEST_DATA['subjectName'];


    $degree = $REQUEST_DATA['degree'];
	$testCategoryId = $REQUEST_DATA['testCategoryId'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$groupId = $REQUEST_DATA['groupId'];
	$groupName = $REQUEST_DATA['groupName'];
    
  
    $condition =" AND tt.classId =$degree AND 
				tt.subjectId=$subjectId AND
				tt.groupId=$groupId AND 
				tt.testTypeCategoryId=$testCategoryId";  
 
    $externalMarksArray = $studentManager->getTestTypeList($condition);
    
    $valueArray = array();    
    for($i=0; $i<count($externalMarksArray); $i++) {
       
	   $externalMarksArray[$i]['testDate'] = UtilityManager::formatDate($externalMarksArray[$i]['testDate']);
       $valueArray[] = array_merge(array('srNo' => $i+1),$externalMarksArray[$i]);
    }
    

	$reportManager->setReportWidth(600);
	$reportManager->setReportHeading('Test Type Distribution Consolidated Report');
	$reportManager->setReportInformation("<b>Degree:</b> $className, <b>Category:</b> $categoryName, <b>Group:</b> $groupName, <b>Subject:</b> ".$externalMarksArray[0][subjectCode]);

	$reportTableHead							=	array();
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']		=	array('#','width="4%" align=left', "align='left' ");
	$reportTableHead['employeeName']=	array('Faculty','width="15%" align="left"', 'align="left"');
	$reportTableHead['testTopic']	=	array('Test Topic','width="25%" align="left"', 'align="left"');
	$reportTableHead['testAbbr']	=	array('Abbr','width="15%" align="left"', 'align="left"');
	$reportTableHead['testIndex']	=	array('Index','width="15%" align="left"', 'align="left"');
	$reportTableHead['maxMarks']	=	array('Max Marks','width="15%" align="left"', 'align="left"');
	$reportTableHead['testDate']	=	array('Test Date','width="15%" align="left"', 'align="left"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History : studentExternalMarksPrint.php $
//
?>