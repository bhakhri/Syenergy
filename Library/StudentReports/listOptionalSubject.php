<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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


	/////////////////////////

	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['degree'];

	// to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';

    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$freeClassGroupArray = array();

	$countSubjectArray = $studentManager->getCountOptionalSubject($classId);
	$optionalSubjectArray = $studentManager->getOptionalSubject($classId,$limit,$orderBy);
	$cntOptionalSubject = count($optionalSubjectArray);


	if($cntOptionalSubject > 0 and is_array($optionalSubjectArray)) {
		//echo($recordCount);
		//$prevRollNo = '';
		for($s=0;$s<$cntOptionalSubject;$s++) {
			if($optionalSubjectArray[$s]['parentOfSubjectId'] != '') {
				$thisRollNo = $optionalSubjectArray[$s]['rollNo'];
				/*
				if ($prevRollNo == $thisRollNo and $thisRollNo != '') {
					$optionalSubjectArray[$s]['rollNo'] = '';
					$optionalSubjectArray[$s]['studentName'] = '';
				}
				*/
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

			$valueArray = array_merge(array('parentSubject'=>$parentSubject,'srNo' => ($records+$s+1)),$optionalSubjectArray[$s]);

			if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
			}
			else {
				$json_val .= ','.json_encode($valueArray);
			}
			//$prevRollNo = $thisRollNo;
		}
	}

   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$countSubjectArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';


// for VSS
// $History: listOptionalSubject.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/17/10    Time: 4:44p
//Created in $/LeapCC/Library/StudentReports
//new file for optional subject report
//
?>