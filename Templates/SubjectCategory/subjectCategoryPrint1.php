<?php
//This file is used as printing version for subject details
// Author :cheena
// Created on : 01-08-2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SubjectCategory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);

    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
    $subjectCategoryManager =  SubjectCategoryManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();


 	$subjectCategoryId = $REQUEST_DATA['categoryId'];
    $condition = "subjectCategoryId = '".$subjectCategoryId."'";
	
    $subjectRecordArray = $subjectCategoryManager->getSubjectListNew($condition,'');
    $cnt = count($subjectRecordArray);

    for($i=0;$i<$cnt;$i++) {
        // add groupId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('action' => $subjectRecordArray[$i]['subjectId'],
                                        'srNo' => ($records+$i+1)), $subjectRecordArray[$i]);
    }


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Subject Details Report Print');
   // $reportManager->setReportInformation("SearchBy: ".$REQUEST_DATA['searchbox']);

		$reportTableHead			 =  array();
		//associated key				            col.label,			col. width,	  data align
		$reportTableHead['srNo']		=  array('#','width="3%" align="left"', "align='left' ");
		$reportTableHead['subjectName']         =  array('Subject Name','width=25% align="left"', 'align="left"');
		$reportTableHead['subjectCode']		=  array('Subject Code','width=15% align="left"', 'align="left"');
		$reportTableHead['subjectTypeName']     =  array('Subject Type','width=10% align="left"', 'align="left"');
		$reportTableHead['hasAttendance']       =  array('Attendance Marked','width=15% align="left"','align ="left"');
		$reportTableHead['hasMarks']   =  array('Marks Computed','width=15% align="left"', 'align="left"');

	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>
