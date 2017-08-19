<?php

//---------------------------------------------------------------------------------------------
// THIS FILE IS USED TO SAVE OPTIONAL SUBJECTS GROUPS ALLOCATED STUDENTS OF A PARTICULAR CLASS
// Author : Kavish Manjkhola
// Created on : (14 Feb 2011)
// Copyright 2011-2000: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------

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

$classId = trim($REQUEST_DATA['degree']);
$sortBy = trim($REQUEST_DATA['sortBy']);
if ($sortBy == 'alphabetic') {
	$sortBy = 'studentName';
}
if ($classId == '' or $sortBy == '') {
	echo 'Missing required parameteres';
	die;
}

//Array to store Inconsistencies while allocating groups to students
$inconsistencyArray = array();

//Fetching data posted by user while allocating group to students
$chkData = $REQUEST_DATA['chk'];

//Array to find Optional Subjects with/without parents.
$hasParentSubjectArray = $studentManager->hasParentSubjectDetails($classId);
$hasParentSubjectIds = UtilityManager::makeCSList($hasParentSubjectArray,'subjectId');
if($hasParentSubjectIds == '') {
	$hasParentSubjectIds = 0;
}
$noParentSubjectArray = $studentManager->noParentSubjectDetails($classId);
$noParentSubjectIds = UtilityManager::makeCSList($noParentSubjectArray,'subjectId');
$noParentSubjectRecords = array();
$hasParentSubjectRecords = array();

//Re-formation of array with subjectId as key
foreach($noParentSubjectArray as $records) {
	$noParentSubjectRecords[$records['subjectId']] = array('subjectName' => $records['subjectName'], 'subjectCode' => $records['subjectCode']);
}

//Re-formation of array with subjectId as key
foreach($hasParentSubjectArray as $records) {
	$hasParentSubjectRecords[$records['subjectId']] = array('subjectName' => $records['subjectName'], 'subjectCode' => $records['subjectCode']);
}

//Array to find Child Subjects.
$getChildSubjectsArray = $studentManager->getChildClassesDetails($classId,$hasParentSubjectIds);
$childSubjectIds = UtilityManager::makeCSList($getChildSubjectsArray,'childSubjectId');
if ($childSubjectIds == '') {
	$childSubjectIds = 0;
}
if ($noParentSubjectIds != '') {
	$childSubjectIds .= ',';
	$childSubjectIds .= $noParentSubjectIds;
}


//Array to find the Groups of the optional subjects in a particular class.
$classGroupDetailsArray = $studentManager->getClassGroupDetails($classId, $childSubjectIds);
$cnt = count($classGroupDetailsArray);
$groupArray = array();
foreach($classGroupDetailsArray as $records) {
	$groupArray[$records['groupId']] = array('groupName' => $records['groupShort']);
}

//Array to find the student details of the class.
$studentDetailsArray = $studentManager->getStudentDetails($classId,$sortBy);
$studentArray = array();
foreach($studentDetailsArray as $records) {
	$studentArray[$records['studentId']] = array('studentName' => $records['studentName'], 'rollNo' => $records['rollNo'], 'universityRollNo' => $records['universityRollNo'] );
}

$subjectArray = array();
//Formation of multidimensional array of child Subject Code,Child SubjectId, Parent SubjectId under Parent Subject Code.
foreach($getChildSubjectsArray as $records) {
	$subjectArray[$records['parentOfSubjectId']][$records['childSubjectId']] = array('childSubjectCode'=>$records['childSubjectCode'], 'parentSubjectCode'=>$records['parentSubjectCode']);
}


//To validate records if no Checkbox is selected
if(!count($chkData)) {
	$subjectIds = UtilityManager::makeCSList($classGroupDetailsArray,'childsubjectId');
	$getAttendanceDetailsArray = $studentManager->getGroupAttendanceRecord($classId, $subjectIds);
	/*echo "<pre>";
	print_r($getAttendanceDetailsArray);
	die;*/
	$cnt = count($getAttendanceDetailsArray);
	for($i=0; $i<$cnt; $i++) {
		$stuId = $getAttendanceDetailsArray[$i]['studentId'];
		$grpId = $getAttendanceDetailsArray[$i]['groupId'];
		$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName'] ."</b>".'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_GROUP_.$groupArray[$grpId]['groupName'];
	}
	if (count($inconsistencyArray)) {
		$ctr = 1;
		echo "<u><b>Following inconsistencies were found during saving groups:</b></u><br>";
		foreach($inconsistencyArray as $record) {
			echo "<br>".$ctr.'. '.$record;
			$ctr++;
		}
	}
	else {
		echo NO_RECORD_FOUND;
	}
	die;
}

//To validate records on basis of checkboxes selected
foreach ($subjectArray as $parentSubjectId => $childSubjectIdArray) {
	//CHECK IF PARENT SUBJECT IS SELECTED BY USER.
	if (!array_key_exists($parentSubjectId, $chkData)) {
		//check if this subject was allotted to any student, if yes then check for the group in attendance table.
		$parentSubjectDetailsArray = $studentManager->getParentSubjectDetails($classId,$parentSubjectId);
		if(count($parentSubjectDetailsArray)>0) {
			$parentGroupIds = UtilityManager::makeCSList($parentSubjectDetailsArray,'groupId');
			$parentStudentIds = UtilityManager::makeCSList($parentSubjectDetailsArray,'studentId');
			$parentSubjectIds = UtilityManager::makeCSList($parentSubjectDetailsArray,'subjectId');
			$getAttendanceDetailsArray = $studentManager->getAttendanceDetails($classId, $parentGroupIds, $parentStudentIds, $parentSubjectIds);
			$cnt = count($getAttendanceDetailsArray);
			if($cnt>0) {
				for($i=0; $i<$cnt; $i++) {
					$stuId = $getAttendanceDetailsArray[$i]['studentId'];
					$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName']."</b>" .'('.$studentArray[$stuId]['rollNo'].')' . _FOR_SUBJECT_.$hasParentSubjectRecords[$parentSubjectId]['subjectCode'];
				}
			}
		}
	}
	else {
		foreach ($childSubjectIdArray as $childSubjectId => $subjectNameArray) {
			//CHECK IF CHILD SUBJECT IS SELECTED BY USER.
			if (!array_key_exists($childSubjectId, $chkData[$parentSubjectId])) {
				//check if this subject was allotted to any student, if yes then check for the group in attendance table.
				$childSubjectDetailsArray = $studentManager->getChildSubjectDetails($classId,$childSubjectId);
				if (count($childSubjectDetailsArray)>0) {
					$classGroupIds = UtilityManager::makeCSList($childSubjectDetailsArray,'groupId');
					$classStudentIds = UtilityManager::makeCSList($childSubjectDetailsArray,'studentId');
					$getAttendanceDetailsArray = $studentManager->getAttendanceDetails($classId, $classGroupIds, $classStudentIds, $childSubjectId);
					$counter = count($getAttendanceDetailsArray);
					for($i=0; $i<$counter; $i++) {
						$stuId = $getAttendanceDetailsArray[$i]['studentId'];
						$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName']."</b>" .'('.$studentArray[$stuId]['rollNo'].')' ._FOR_SUBJECT_.$subjectArray[$parentSubjectId][$childSubjectId]['childSubjectCode'];
					}
				}
			}
			else {
				foreach($groupArray as $groupId => $groupNameArray) {
					if (!array_key_exists($groupId, $chkData[$parentSubjectId][$childSubjectId])) {
						//check if this group was allotted to any student, if yes then check for the group in attendance table.
						$getGroupDetailsArray = $studentManager->getStudentGroupDetails($classId, $childSubjectId, $groupId);
						if(count($getGroupDetailsArray)>0) {
							$groupStudentsIds = UtilityManager::makeCSList($getGroupDetailsArray,'studentId');
							$getAttendanceDetailsArray = $studentManager->getAttendanceDetails($classId, $groupId, $classStudentIds, $childSubjectId);
							$cnt = count($getAttendanceDetailsArray);
							for($i=0; $i<$cnt; $i++) {
								$stuId = $getAttendanceDetailsArray[$i]['studentId'];
								$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName'] ."</b>".'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_GROUP_.$groupArray[$grpId]['groupName'];
							}
						}
					}
					else {
						foreach($studentArray as $studentId => $studentNameArray) {
							if (!array_key_exists($studentId, $chkData[$parentSubjectId][$childSubjectId][$groupId])) {
								//check if this group was allotted to any student, if yes then check for the group in attendance table.
								$getStudentDetailsArray = $studentManager->getStudentRecords($classId, $childSubjectId, $groupId, $studentId);
								if(count($getStudentDetailsArray)>0) {
									$getAttendanceDetailsArray = $studentManager->getAttendanceDetails($classId, $groupId, $studentId, $childSubjectId);
									$cnt = count($getAttendanceDetailsArray);
									for($i=0; $i<$cnt; $i++) {
										$stuId = $getAttendanceDetailsArray[$i]['studentId'];
										$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName']."</b>" .'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_GROUP_.$groupArray[$groupId]['groupName'];
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
#TO VALIDATE OPTIONAL SUBJECT(S)
foreach($noParentSubjectRecords as $subjectId => $subjectRecords) {
	if (!array_key_exists($subjectId, $chkData)) {
		//echo $subjectId;die;
		//check if this subject was allotted to any student, if yes then check for the group in attendance table.
		$subjectDetailsArray = $studentManager->subjectDetailsArray($classId,$subjectId);
		if(count($subjectDetailsArray)>0) {
			$groupIds = UtilityManager::makeCSList($subjectDetailsArray,'groupId');
			$studentIds = UtilityManager::makeCSList($subjectDetailsArray,'studentId');
			$getAttendanceDetailsArray = $studentManager->getStudentGroupAttendanceDetails($classId, $groupIds, $studentIds);
			$cnt = count($getAttendanceDetailsArray);
			for($i=0; $i<$cnt; $i++) {
				$stuId = $getAttendanceDetailsArray[$i]['studentId'];
				$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName'] ."</b>".'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_SUBJECT_.$noParentSubjectRecords[$subjectId]['subjectCode'];
			}
		}
	}
	else {
		foreach($groupArray as $groupId => $groupNameArray) {
			if (!array_key_exists($groupId, $chkData[$subjectId])) {
				//check if this group was allotted to any student, if yes then check for the group in attendance table.
				$groupRecordsArray = $studentManager->getStudentGroupDetails($classId, $subjectId, $groupId);
				if(count($groupRecordsArray)>0) {
					$studentIds = UtilityManager::makeCSList($groupRecordsArray,'studentId');
					$getAttendanceDetailsArray = $studentManager->getStudentGroupAttendanceDetails($classId, $groupId, $studentIds);
					$cnt = count($getAttendanceDetailsArray);
					for($i=0; $i<$cnt; $i++) {
						$stuId = $getAttendanceDetailsArray[$i]['studentId'];
						$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName'] ."</b>".'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_GROUP_.$groupArray[$groupId]['groupName'];
					}
				}
			}
			else {
				foreach($studentArray as $studentId => $studentNameArray) {
					//echo $studentId;
					if (!array_key_exists($studentId, $chkData[$subjectId][$groupId])) {
						//check if this group was allotted to any student, if yes then check for the group in attendance table.
						$getStudentDetailsArray = $studentManager->getStudentRecords($classId, $subjectId, $groupId, $studentId);
						if(count($getStudentDetailsArray)>0) {
							$studentIds = UtilityManager::makeCSList($getStudentDetailsArray,'studentId');
							$getAttendanceDetailsArray = $studentManager->getStudentGroupAttendanceDetails($classId, $groupId, $studentIds);
							$cnt = count($getAttendanceDetailsArray);
							for($i=0; $i<$cnt; $i++) {
								$stuId = $getAttendanceDetailsArray[$i]['studentId'];
								$inconsistencyArray[] = CANNOT_REMOVE_ALLOCATION_FOR."<b>".$studentArray[$stuId]['studentName'] ."</b>".'('.$studentArray[$stuId]['rollNo'].')' .  _FOR_GROUP_.$groupArray[$groupId]['groupName'];
							}
						}
					}
				}
			}
		}
	}
}
# FOR CHECKING ALLOCATION OF SAME SUBJECT MULTIPLE TIMES
$studentAllocationArray = array();
foreach($chkData as $parentId => $records) {
	if (array_key_exists($parentId, $noParentSubjectRecords)) {
		foreach($records as $groupId => $studentIdArray) {
			foreach($studentIdArray as $studentId) {
				if (!is_array($studentAllocationArray[$studentId])) {
					$studentAllocationArray[$studentId] = array();
				}
				if (!in_array($parentId, $studentAllocationArray[$studentId])) {
					$studentAllocationArray[$studentId][] = $parentId;
				}
				else {
					# DUPLICATE ALLOCATION FOUND FOR SAME GROUP
					$inconsistencyArray[] = DUPLICATE_GROUPS_SELECTED_FOR."<b>".$studentArray[$studentId]['studentName']."</b>".'('.$studentArray[$studentId]['rollNo'].')'.  _FOR_SUBJECT_.$noParentSubjectRecords[$parentId]['subjectCode'];
				}
			}
		}
	}
	else {
		foreach ($records as $childSubjectId => $groupIdArray) {
			foreach ($groupIdArray as $groupId => $studentIdArray) {
				foreach ($studentIdArray as $studentId) {
					if (!is_array($studentAllocationArray[$studentId])) {
						$studentAllocationArray[$studentId] = array();
					}
					if (!in_array($childSubjectId, $studentAllocationArray[$studentId])) {
						$studentAllocationArray[$studentId][] = $childSubjectId;
					}
					else {
						# DUPLICATE ALLOCATION FOUND FOR SAME GROUP
						$inconsistencyArray[] = DUPLICATE_GROUPS_SELECTED_FOR."<b>".$studentArray[$studentId]['studentName']."</b>".'('.$studentArray[$studentId]['rollNo'].')'.  _FOR_SUBJECT_.$subjectArray[$parentId][$childSubjectId]['childSubjectCode'];
					}
				}
			}
		}
	}
}
//TO PRINT INCONSISTENCIES FOUND DURING ALLOCATION OF GROUPS TO STUDENTS
if (count($inconsistencyArray)) {
	$ctr = 1;
	echo "<u><b>Following inconsistencies were found during saving groups:</b></u><br>";
	foreach($inconsistencyArray as $record) {
		echo "<br>".$ctr.'. '.$record;
		$ctr++;
	}
}
else {
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		$return = $studentManager->removeStudentGroupAllocationRecords($classId);
		if ($return == false) {
			echo ERROR_WHILE_REMOVING_CURRENT_GROUP_ALLOCATION;
			die;
		}
		$insertArray = array();
		$parentOfSubjectId = 0;
		foreach ($chkData as $parentId => $records) {
			if (array_key_exists($parentId, $noParentSubjectRecords)) {
				foreach($records as $groupId => $studentIdArray) {
					foreach($studentIdArray as $studentId) {
						$insertArray[] = "($parentId, $studentId, $classId, $groupId,$parentOfSubjectId)";
					}
				}
			}
			else {
				foreach ($records as $childSubjectId => $groupIdArray) {
					foreach ($groupIdArray as $groupId => $studentIdArray) {
						foreach ($studentIdArray as $studentId) {
							$insertArray[] = "($childSubjectId, $studentId, $classId, $groupId, $parentId)";
						}
					}
				}
			}
		}
		if (count($insertArray)) {
			$insertStr = implode(',', $insertArray);
			$returnStatus = $studentManager->addStudentGroup($insertStr);
			if ($returnStatus == false) {
				echo ERROR_WHILE_UPDATING_GROUP_ALLOCATION;
				die;
			}
		}
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			echo SUCCESS;
		}
		else {
			echo FAILURE;
		}
	}
	else {
		echo FAILURE;
	}
}