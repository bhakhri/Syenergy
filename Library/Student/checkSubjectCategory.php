<?php
//-------------------------------------------------------
// Purpose: To count and fetch subject categories
//
// Author : Ajinder Singh
// Created on : (07.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjects');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId = $REQUEST_DATA['degree'];
$subjectId = $REQUEST_DATA['subjectId'];
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
$hasParentCategoryArray = $studentManager->hasParentCategory($classId, $subjectId);
$hasParentCategory = $hasParentCategoryArray[0]['hasParentCategory'];
if ($hasParentCategory == 1) {
	$categorySubjectArray = $studentManager->getMappedSubjects($classId, $subjectId);
	echo json_encode($categorySubjectArray);
}
else {
	echo SUBJECT_DOES_NOT_HAVE_PARENT_CATEGORY;
}

// for VSS
// $History: checkSubjectCategory.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:30a
//Created in $/LeapCC/Library/Student
//file added for checking and fetching category subjects.







?>