<?php 
//This file is used as CSV version for attendance report.
//
// Author :Ajinder Singh
// Created on : 02-09-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	$commonQueryManager = CommonQueryManager::getInstance();
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$studentReportsManager = StudentReportsManager::getInstance();

 	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$classId = $REQUEST_DATA['degree'];
	$classIdReq = $REQUEST_DATA['class'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$groupId = $REQUEST_DATA['groupId'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	if ($groupId == '' or $groupId == 'all') {
		$groupIdSelected = false;
	}
	else {
		$groupIdSelected = true;
	}

/////////////////////////////////////////////////////////////////////////////////////
	$recordArray = array();

	if ($classId == 'all') {
		//for all classes
		$testMainArray = array();
		$classArray = $studentReportsManager->getTestClasses();
		foreach($classArray as $classRecord) {
			$classId = $classRecord['classId'];
			$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " WHERE a.classId = $classId");
			$subjectsArray = $studentReportsManager->getSubjectList($classId);
			foreach($subjectsArray as $subjectRecord) {
                if($subjectRecord['hasMarks']==1) {
				    $subjectId = $subjectRecord['subjectId'];
				    foreach($groupsArray as $groupRecord) {
					    $groupIdFetched = $groupRecord['groupId'];
					    $testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subjectId, $groupIdFetched);
					    $testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
				    }
                }
			}
		}
	}
	else {
		//for 1 class, and all subjects and all groups
		if ($subjectId == 'all') {
			if ($groupId == 'all') {
				$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " WHERE a.classId = $classId");
				$subjectsArray = $studentReportsManager->getSubjectList($classId);
				foreach($subjectsArray as $subjectRecord) {
                    if($subjectRecord['hasMarks']==1) {
					    $subjectId = $subjectRecord['subjectId'];
					    foreach($groupsArray as $groupRecord) {
						    $groupIdFetched = $groupRecord['groupId'];
						    $testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subjectId, $groupIdFetched);
						    $testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
					    }
                    }
				}
			}
			else {
				//for 1 class, all subjects and 1 group
				$subjectsArray = $studentReportsManager->getSubjectList($classId);
				$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " WHERE a.classId = $classId");
				foreach($subjectsArray as $subjectRecord) {
                    if($subjectRecord['hasMarks']==1) {
					    $subjectId = $subjectRecord['subjectId'];
					    foreach($groupsArray as $groupRecord) {
						    $groupIdFetched = $groupRecord['groupId'];
						    $testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subjectId, $groupIdFetched);
						    $testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
					    }
                    }
				}
			}
		}
		else {
			//for 1 subject, and all groups
			if ($groupId == 'all') {
				$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " WHERE a.classId = $classId");
				foreach($groupsArray as $groupRecord) {
					$groupIdFetched = $groupRecord['groupId'];
                    $subject = $subjectId." AND d.hasMarks = 1 ";   
					$testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subject, $groupIdFetched);
					$testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
				}
			}
			else {
				//for 1 subject and 1 group
				$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " WHERE a.classId = $classId");
				foreach($groupsArray as $groupRecord) {
					$groupIdFetched = $groupRecord['groupId'];
                    $subject = $subjectId." AND d.hasMarks = 1 ";   
					$testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subject, $groupIdFetched);
					$testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
				}
			}
		}
	}


	$uniqueArray = array(); //array to check for duplicate values

	foreach($testMainArray as $classId => $subjectGroupTestsArray) {
		foreach($subjectGroupTestsArray as $subjectId => $groupTestsArray) {
			$diffArray = array();
			$groupsArray = array();
			$groupsArray = array_keys($groupTestsArray);
			$diffArray = array_values($groupTestsArray);
			$diffCount = count($diffArray);
			for($i = 0; $i < $diffCount; $i++) {
				for($j = 0; $j < $diffCount; $j++) {
					if ($i != $j) {
						$diffTestsArray = array_diff_assoc($diffArray[$i], $diffArray[$j]);
						if (count($diffTestsArray) and $groupIdSelected === true) {
							foreach($diffTestsArray as $diffRecord) {
								if ($groupsArray[$j] == $groupId) {
									$groupNameArray = $studentReportsManager->getSingleField('`group`', 'groupName', " WHERE groupId = ".$groupsArray[$j]);
									$groupName = $groupNameArray[0]['groupName'];
									$thisVal = $groupsArray[$j] . '#'. $diffRecord['testName'] . '#' . $diffRecord['subjectCode'];
									if (!in_array($thisVal, $uniqueArray)) {
										$uniqueArray[] = $thisVal;
										$recordArray[] = array('groupId'=>$groupsArray[$j],'testId'=>$diffRecord['testId'], 'testName'=>$diffRecord['testName'], 'className'=>$diffRecord['className'], 'subjectCode'=>$diffRecord['subjectCode'], 'groupName'=>$groupName, 'employeeCode'=>$diffRecord['employeeCode'], 'employeeName'=>$diffRecord['employeeName']);
									}
								}
							}
						}
						elseif (count($diffTestsArray) and $groupIdSelected === false) {
							foreach($diffTestsArray as $diffRecord) {
								$groupNameArray = $studentReportsManager->getSingleField('`group`', 'groupName', " WHERE groupId = ".$groupsArray[$j]);
								$groupName = $groupNameArray[0]['groupName'];
								$thisVal = $groupsArray[$j] . '#'. $diffRecord['testName'] . '#' . $diffRecord['subjectCode'];
								if (!in_array($thisVal, $uniqueArray)) {
									$uniqueArray[] = $thisVal;
									$recordArray[] = array('groupId'=>$groupsArray[$j],'testId'=>$diffRecord['testId'], 'testName'=>$diffRecord['testName'], 'className'=>$diffRecord['className'], 'subjectCode'=>$diffRecord['subjectCode'], 'groupName'=>$groupName, 'employeeCode'=>$diffRecord['employeeCode'], 'employeeName'=>$diffRecord['employeeName']);
								}
							}
						}
					}
				}
			}
		}
	}

	$cnt = count($recordArray);

	$valueArray = array();
	for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1)), $recordArray[$i]);
	}

	if ($classIdReq == 'all') {
	   $className2 = 'All ';
	}
	else {
	   $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
	   $className = $classNameArray[0]['className'];
	   $className2 = str_replace("-",' ',$className);
	}

	if ($subjectId == 'all') {
	   $subCode = 'All';
	}
	else {
	   $subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "where subjectId  = $subjectId");
	   $subCode = $subCodeArray[0]['subjectCode'];
	}

	if ($groupId == 'all' or $groupId == '' ) {
	   $groupName = 'All';
	}
	else {
		$groupNameArray = $studentReportsManager->getSingleField('`group`', 'groupName', " WHERE groupId = ".$groupId);
		$groupName = $groupNameArray[0]['groupName'];
	}
	
	$csvData = '';
	$csvData .= "Sr, Class, Subject, Group, Faculty, Test Name \n";
	foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['className'].','.$record['subjectCode'].','.$record['groupName'].','.$record['employeeName'].','.$record['testName'];
		$csvData .= "\n";
	}

UtilityManager::makeCSV($csvData,'MarksNotEnteredReport.csv');
die;

// $History : $
//
?>