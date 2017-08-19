<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','OptionalGroupReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	
	 require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

	/////////////////////////
    
	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['degree'];
	

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$freeClassGroupArray = array();

	$withoutOptionalSubjectArray = $studentManager->getWithoutOptionalSubject($classId,'',$orderBy);
	$cntWihoutOptionalSubject = count($withoutOptionalSubjectArray);

	if($cntWihoutOptionalSubject > 0 and is_array($withoutOptionalSubjectArray)) {
		//echo($recordCount);
		for($s=0;$s<$cntWihoutOptionalSubject;$s++) {

			$valueArray[] = array_merge(array('srNo' => ($records+$s+1)),$withoutOptionalSubjectArray[$s]);
		}
	}
	  $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Compulsory Group Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	
    $reportTableHead                        =		array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=		array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['studentName']			=		array('Student Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['universityRollNo']	=		array('University Roll No.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['rollNo']			=			array('Roll No.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['groupName']			=        array('Group',        ' width="15%" align="left" ','align="left"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
	?>