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

	
	$optionalSubjectArray = $studentManager->getOptionalSubject($classId,'',$orderBy);
	$cntOptionalSubject = count($optionalSubjectArray);

	if($cntOptionalSubject > 0 and is_array($optionalSubjectArray)) {
		//echo($recordCount);
		$prevRollNo = '';
		for($s=0;$s<$cntOptionalSubject;$s++) {
			if($optionalSubjectArray[$s]['parentOfSubjectId'] != '') {
				$thisRollNo = $optionalSubjectArray[$s]['rollNo'];
				if ($prevRollNo == $thisRollNo and $thisRollNo != '') {
					$optionalSubjectArray[$s]['rollNo'] = '';
					$optionalSubjectArray[$s]['studentName'] = '';
				}
				$optionalSubjectId = $optionalSubjectArray[$s]['parentOfSubjectId'];
				$optionalParentSubjectArray = $studentManager->getOptionalParentSubject($optionalSubjectId);
				$optionalParentSubjectId = $optionalParentSubjectArray[0]['subjectId'];
				if($optionalParentSubjectId != '') {
					if($optionalSubjectArray[$s]['parentOfSubjectId'] == $optionalParentSubjectId) {
						$parentSubject = $optionalParentSubjectArray[0]['subjectCode'];
					}
				}
			}
			else {
				$parentSubject = NOT_APPLICABLE_STRING;
			}

			$valueArray[] = array_merge(array('parentSubject'=>$parentSubject,'srNo' => ($records+$s+1)),$optionalSubjectArray[$s]);
		}
	}
	// $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Optional Group Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	
    $reportTableHead                        =		array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=		array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['studentName']			=		array('Student Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['universityRollNo']	=		array('University Roll No.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['rollNo']			=			array('Roll No.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['subjectCode']			=		array('Optional Subject',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['groupName']			=        array('Group',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['parentSubject']			=    array('Optional Subject Parent',        ' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
	?>