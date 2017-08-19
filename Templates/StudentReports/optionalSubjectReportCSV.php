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
	 function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }


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

   $csvData ='';
    $csvData.="#,Student Name,University Roll No.,Roll No.,Optional Subject,Group,Optional Subject Parent";
    $csvData .="\n";
    
    for($i=0;$i<count($valueArray);$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($valueArray[$i]['studentName']).",";
		  $csvData .= parseCSVComments($valueArray[$i]['universityRollNo']).",";
		    $csvData .= parseCSVComments($valueArray[$i]['rollNo']).",";
			$csvData .= parseCSVComments($valueArray[$i]['subjectCode']).",";
			$csvData .= parseCSVComments($valueArray[$i]['groupName']).",";
		  $csvData .= parseCSVComments($valueArray[$i]['parentSubject'])."\n";
    }
 UtilityManager::makeCSV($csvData,'OptionalGroupReport.csv');
 die;
//$History : $
?>