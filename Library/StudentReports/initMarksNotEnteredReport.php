<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$commonQueryManager = CommonQueryManager::getInstance();

	$studentReportsManager = StudentReportsManager::getInstance();
	 
	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$groupId = $REQUEST_DATA['groupId'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	$labelId = $REQUEST_DATA['labelId'];
	if ($groupId == '' or $groupId == 'all') {
		$groupIdSelected = false;
	}
	else {
		$groupIdSelected = true;
	}


	//1. classId  = all
	//2. classId selected, subjects all, groups all
	//3. classId selected, subjects selected, group all
	//4. classId selected, subject selected, group selected
	//5. classId selected, subject all, group selected

	$recordArray = array();
/*
	if ($classId == 'all') {
		//for all classes
		$testMainArray = array();
		$classArray = $studentReportsManager->getTestClasses();
		foreach($classArray as $classRecord) {
			$classId = $classRecord['classId'];
			$groupsArray = $commonQueryManager->getLastLevelGroups('groupName', " AND a.classId = $classId");
			$subjectsArray = $studentReportsManager->getSubjectList($classId);
			foreach($subjectsArray as $subjectRecord) {
				$subjectId = $subjectRecord['subjectId'];
				foreach($groupsArray as $groupRecord) {
					$groupIdFetched = $groupRecord['groupId'];
					$testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subjectId, $groupIdFetched);
					$testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
				}
			}
		}
	}
*/
	if ($classId == 'all') {
		//for all classes
		$testMainArray = array();
		$classArray = $studentReportsManager->getTestClasses(" AND b.classId IN (SELECT classId from time_table_classes where timeTableLabelId = $labelId)");
		foreach($classArray as $classRecord) {
			$classId = $classRecord['classId'];
			$subjectsArray = $studentReportsManager->getSubjectList($classId);
			foreach($subjectsArray as $subjectRecord) {
                if($subjectRecord['hasMarks']==1) {
				    $subjectId = $subjectRecord['subjectId'];
				    $groupsArray = $studentReportsManager->getClassSubjectGroups($classId,$subjectId);
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
			if ($groupId == 'all' or $groupId == '') {
				$subjectsArray = $studentReportsManager->getSubjectList($classId);
				foreach($subjectsArray as $subjectRecord) {
                    if($subjectRecord['hasMarks']==1) {
					    $subjectId = $subjectRecord['subjectId'];
					    $groupsArray = $studentReportsManager->getClassSubjectGroups($classId,$subjectId);
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
				foreach($subjectsArray as $subjectRecord) {
                    if($subjectRecord['hasMarks']==1) {
					    $subjectId = $subjectRecord['subjectId'];
					    $groupsArray = $studentReportsManager->getClassSubjectGroups($classId,$subjectId);
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
			if ($groupId == 'all' or $groupId == '') {
				$groupsArray = $studentReportsManager->getClassSubjectGroups($classId,$subjectId);
				foreach($groupsArray as $groupRecord) {
					$groupIdFetched = $groupRecord['groupId'];
                    $subject = $subjectId." AND d.hasMarks = 1 ";   
					$testArray = $studentReportsManager->getClassSubjectGroupTests($classId, $subject, $groupIdFetched);
					$testMainArray[$classId][$subjectId][$groupIdFetched] = $testArray;
				}
			}
			else {
				//for 1 subject and 1 group
				$groupsArray = $studentReportsManager->getClassSubjectGroups($classId,$subjectId);
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

	if (!count($testMainArray)) {
		$testMainArray = array();
	}



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
										$teacherArray = $studentReportsManager->getClassSubjectGroupTeacher($subjectId,$groupsArray[$j]);
										$recordArray[] = array('groupId'=>$groupsArray[$j],'testId'=>$diffRecord['testId'], 'testName'=>$diffRecord['testName'], 'className'=>$diffRecord['className'], 'subjectCode'=>$diffRecord['subjectCode'], 'groupName'=>$groupName, 'employeeCode'=>$diffRecord['employeeCode'], 'employeeName'=>$teacherArray[0]['employeeName']);
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
									$teacherArray = $studentReportsManager->getClassSubjectGroupTeacher($subjectId,$groupsArray[$j]);
									$recordArray[] = array('groupId'=>$groupsArray[$j],'testId'=>$diffRecord['testId'], 'testName'=>$diffRecord['testName'], 'className'=>$diffRecord['className'], 'subjectCode'=>$diffRecord['subjectCode'], 'groupName'=>$groupName, 'employeeCode'=>$diffRecord['employeeCode'], 'employeeName'=>$teacherArray[0]['employeeName']);
								}
							}
						}
					}
				}
			}
		}
	}

	$cnt = count($recordArray);


    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1)), $recordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 


// $History: initMarksNotEnteredReport.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Library/StudentReports
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/01/09    Time: 2:14p
//Updated in $/LeapCC/Library/StudentReports
//added condition for fetching classes based on time table label
//selected.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:44p
//Updated in $/LeapCC/Library/StudentReports
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/31/09    Time: 3:48p
//Updated in $/LeapCC/Library/StudentReports
//code modified as per flow of database.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/16/09    Time: 2:29p
//Updated in $/LeapCC/Library/StudentReports
//files modified to check and make correction to non working part.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:52p
//Updated in $/Leap/Source/Library/StudentReports
//fixed "no records found" bug, and fixed IE related issue 
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/09/08    Time: 1:45p
//Updated in $/Leap/Source/Library/StudentReports
//changed the function call from 
//fetching classes from time table 
//to 
//fetching classes from test
//for "marks not entered report"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:20p
//Updated in $/Leap/Source/Library/StudentReports
//removed unwanted code
//
?>
