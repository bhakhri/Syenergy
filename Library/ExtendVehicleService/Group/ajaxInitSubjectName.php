<?php
//-------------------------------------------------------
// Purpose: To get values of parent group name from the database
//
// Author : Jaineesh
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GroupMaster');
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/GroupManager.inc.php");
	$foundArray = null;
	$optional = $REQUEST_DATA['optional'];
	$classId = $REQUEST_DATA['degree'];
	if ($classId == '') {
		echo CLASS_NOT_SELECTED;
		die;

	}
	$groupManager = GroupManager::getInstance();

	$allOptionalSubjectArray = array();

	$optionalSubjectArray = $groupManager->getClassAllOptionalSubjects($classId);
	echo json_encode($optionalSubjectArray);
	die;


	if ($optional == 1) {
		$optionalSubjectArray = $groupManager->getOptionalSubjects($classId);
		if($optionalSubjectArray[0]['subjectId'] != '') {
			foreach ($optionalSubjectArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				$subjectCode = $subjectRecord['subjectCode'];
				$hasParentCategory = $subjectRecord['hasParentCategory'];
				$subjectCategoryId = $subjectRecord['subjectCategoryId'];
				if ($hasParentCategory == 1) {
					$otherCategorySubjectArray = $groupManager->getSubjectsOtherCategory($subjectCategoryId);
					if($otherCategorySubjectArray[0]['subjectId'] != '') {
						foreach($otherCategorySubjectArray as $otherCategorySubjectRecord) {
							$mmSubjectId = $otherCategorySubjectRecord['subjectId'];
							$mmSubjectCode = $otherCategorySubjectRecord['subjectCode'];
							$allOptionalSubjectArray[$mmSubjectId] = array('subjectId' => $mmSubjectId, 'subjectCode' => $mmSubjectCode);
						}
					}
				}
				else {
					$allOptionalSubjectArray[$subjectId] = array('subjectId' => $subjectId, 'subjectCode' => $subjectCode);
				}
			}
		}

		$allOptionalSubjectArray = array_values($allOptionalSubjectArray);

		echo json_encode($allOptionalSubjectArray);
		/*
		$foundArray = array();

		foreach($allOptionalSubjectArray as $key => $valueArray) {
			$subjectId = $valueArray['subjectId'];
			$subjectCode = $valueArray['subjectCode'];

			$foundArray = array('subjectId' => $subjectId, 'subjectCode' => $subjectCode);

			if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
			}
			else {
				$json_val .= ','.json_encode($valueArray);
			}
		}

		echo $json_val;
		*/
		/*
		$optionalSubjectCount = $optionalSubjectCountArray[0]['cnt'];
		if ($optionalSubjectCount > 0) {

			$mmSubjectCountArray = $groupManager->getMMSubjects($classId, " AND hasParentCategory = 1");
			$mmSubjectCount = $mmSubjectCountArray[0]['cnt'];
			if ($mmSubjectCount > 0) {
				$mmSubjectCategiryArray = $groupManager->
			}
		}
		*/

		/*
		$foundArray = GroupManager::getInstance()->getOptionalSubject($optional,$classId);
		$count = count($foundArray);
		if($count > 0) {
			$foundOptionalSubjectArray = GroupManager::getInstance()->getOptionalParentSubject($optional,$classId);
			$subjectCategoryId = $foundOptionalSubjectArray[0]['subjectCategoryId'];
			$countOptionalSubject = count($foundOptionalSubjectArray);
			if($countOptionalSubject > 0) {
				$subjectCategoryList = UtilityManager::makeCSList($foundOptionalSubjectArray,'subjectCategoryId',',');
				$foundOptionalSubArray = GroupManager::getInstance()->getOptionalParentSub($subjectCategoryList);
				echo json_encode($foundOptionalSubArray);
			}
		}
		else {
			$foundOptionalSubjectArray = GroupManager::getInstance()->getOptionalParentSubject($optional,$classId);
			$subjectCategoryId = $foundOptionalSubjectArray[0]['subjectCategoryId'];
			$countOptionalSubject = count($foundOptionalSubjectArray);
			if($countOptionalSubject > 0) {
				$subjectCategoryList = UtilityManager::makeCSList($foundOptionalSubjectArray,'subjectCategoryId',',');
				$foundOptionalSubArray = GroupManager::getInstance()->getOptionalParentSub($subjectCategoryList);
				echo json_encode($foundOptionalSubArray);
			}
		}
		*/
	}
	//$a = print_r($foundArray, true);
	//logError($a);


// $History: ajaxInitSubjectName.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:19p
//Created in $/LeapCC/Library/Group
//new file to get subject
//
?>