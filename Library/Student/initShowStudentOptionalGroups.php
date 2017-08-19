<?php

//-----------------------------------------------------------------------------------------------
// THIS FILE IS USED TO SHOW OPTIONAL SUBJECTS GROUPS ALLOCATED TO STUDENTS OF A PARTICULAR CLASS
// Author : Kavish Manjkhola
// Created on : (10 Feb 2011)
// Copyright 2011-2000: Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjectsList');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);             
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
$degree = $REQUEST_DATA['degree'];

if($degree=='') {
  $degree='0';  
}

$sortBy = $REQUEST_DATA['sortBy'];
if ($sortBy == 'alphabetic') {
	$sortBy = 'studentName';
}
$resultArray = array();
$cnt = 0;
$hasParentSubjectIds = 0;
$noParentSubjectIds = 0;
$childSubjectIds = 0;

//Array To find the Major/Minor Subjects with Child Subjects to a class
$hasParentSubjectArray = $studentManager->hasParentSubjectDetails($degree);
$hasParentSubjectIds = UtilityManager::makeCSList($hasParentSubjectArray,'subjectId');
if($hasParentSubjectIds == '') {
	$hasParentSubjectIds = 0;
}
//Array To find the Major/Minor Subjects without Child Subjects to a class
$noParentSubjectArray = $studentManager->noParentSubjectDetails($degree);
$noParentSubjectIds = UtilityManager::makeCSList($noParentSubjectArray,'subjectId');

if(count($hasParentSubjectArray)==0 && count($noParentSubjectArray)==0) {
	$resultArray = Array('totalGroups' => 0);
}
else {
	//Array to find the corresponding Child Subject of Parent(major/minor) Subject
	$getChildSubjectsArray = $studentManager->getChildClassesDetails($degree,$hasParentSubjectIds);
	$subjectArray = array();
	//Formation of multidimensional array of child Subject Code,Child SubjectId, Parent SubjectId under Parent Subject Code,
	foreach ($hasParentSubjectArray as $record) {
		$subjectId = $record['subjectId'];
		$subjectArray[$subjectId] = array();
	}
	foreach($getChildSubjectsArray as $records) {
		$subjectArray[$records['parentOfSubjectId']][] = array('childSubjectId'=>$records['childSubjectId'], 'childSubjectCode'=>$records['childSubjectCode'], 'parentSubjectCode'=>$records['parentSubjectCode']);
	}


	$childSubjectIds = UtilityManager::makeCSList($getChildSubjectsArray,'childSubjectId');
	if ($childSubjectIds == '') {
		$childSubjectIds = 0;
	}
	if ($noParentSubjectIds != '') {
		$childSubjectIds .= ',';
		$childSubjectIds .= $noParentSubjectIds;
	}




	//Array to find the Groups of the optinal subjects in a particular class
	$classGroupDetailsArray = $studentManager->getClassGroupDetails($degree, $childSubjectIds);
	$cnt = count($classGroupDetailsArray);
	$groupArray = array();
	foreach($getChildSubjectsArray as $records) {
		$groupArray[$records['childSubjectId']] = array();
	}
	foreach($classGroupDetailsArray as $records) {
		$groupArray[$records['childsubjectId']][] = array('groupId' => $records['groupId'], 'groupName' => $records['groupShort']);
	}



	//Array to find the student details of the class
	$studentDetailsArray = $studentManager->getStudentDetails($degree,$sortBy);
	$studentIds = UtilityManager::makeCSList($studentDetailsArray,'studentId');

    if($studentIds=='') {
      $studentIds='0';
    }
	//Array to group alloted to student
	$studentGroupDetailsArray = $studentManager->studentGroupDetails($degree,$studentIds);
	$studentGroupArray = array();
	foreach($studentGroupDetailsArray as $records) {
		$studentGroupArray[$records['studentId']][] = array('groupId'=>$records['groupId'],'parentOfSubjectId'=>$records['parentOfSubjectId'], 'subjectId'=>$records['subjectId']);
	}


	//Array to find the group count
	$groupCountDetailsArray = $studentManager->getGroupCount($degree);




	//Final Array to be send to Interface file
	$resultArray = Array('hasParentSubjects' => $hasParentSubjectArray, 'noParentSubjects' => $noParentSubjectArray,'getChildSubjects' => $subjectArray, 'groupDetails' => $groupArray, 'studentDetails' => $studentDetailsArray, 'studentGroupDetails' => $studentGroupArray ,'groupCountDetails' => $groupCountDetailsArray, 'totalGroups' => $cnt);
}

//FINAL ARRAY TO BE SEND TO INTERFACE FILE
echo json_encode($resultArray);
?>