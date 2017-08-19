 <?php
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AssignGroupAdvanced');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
	 $studentManager = StudentManager::getInstance();

	$degree = $REQUEST_DATA['degree'];
	$sortBy = $REQUEST_DATA['sortBy'];
	$groupsArray = $studentManager->getClassAllGroups($degree);

	$groupTotalArray = array();
	foreach($groupsArray as $groupRecord) {
		$groupTotalArray[$groupRecord['groupId']] = array('groupShort'=>$groupRecord['groupShort'], 'groupTypeName'=>$groupRecord['groupTypeName']);
	}

	$groupTypeArray = array();
	$groupNameArray = array();
	if ($sortBy == 'alphabetic') {
		$sortBy = 'studentName';
	}
	foreach($groupsArray as $groupRecord) {
		$groupNameArray[$groupRecord['groupTypeName']][] = array('groupId'=>$groupRecord['groupId'], 'groupName'=>$groupRecord['groupShort']);
		if (!in_array($groupRecord['groupTypeName'], $groupTypeArray)) {
			$groupTypeArray[] = $groupRecord['groupTypeName'];
		}
	}
	$studentGroupAllocationArray = $studentManager->getClassStudentGroupAllocation($degree, $sortBy);
	$groupStudentAllocationCountArray = $studentManager->countClassStudentGroupAllocation($degree);
	$newCountArray = array();
	foreach($groupStudentAllocationCountArray as $allocationRecord) {
		$newCountArray[$allocationRecord['groupId']] = $allocationRecord['groupStudentCount'];
	}
	$resultArray = Array('totalGroups' => count($groupsArray), 'groupTypes' => $groupTypeArray, 'groups' => $groupNameArray, 'studentGroups'=>$studentGroupAllocationArray, 'countGroupStudent' => $newCountArray);

	$reportTableHead                        =    array();
	$reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
	$reportTableHead['rollNo']				=    array('Roll No.',' width=8% align="left" ','align="left" ');
	$reportTableHead['universityRollNo']				=    array('U.Roll No.',' width=8% align="left" ','align="left" ');
	$reportTableHead['studentName']				=    array('Student Name',' width=22% align="left" ','align="left" ');
	foreach($groupTypeArray as $groupType) {
		$reportTableHead[$groupType]				=    array($groupType,' width=15% align="left" ','align="left" ');
	}

	$valueArray  = array();
	$i = 0;
	foreach($studentGroupAllocationArray as $studentGroupAllocation) {
		$j = $i + 1;
		$valueArray[$i]['srNo'] = $j;
		$valueArray[$i]['rollNo'] = $studentGroupAllocation['rollNo'];
		$valueArray[$i]['universityRollNo'] = $studentGroupAllocation['universityRollNo'];
		$valueArray[$i]['studentName'] = $studentGroupAllocation['studentName'];
		$groupsAllocatedArray = explode(',',$studentGroupAllocation['groupsAllocated']);
		$thisStudentAllocation = array();
		foreach($groupsAllocatedArray as $groupAllocated) {
			$thisStudentAllocation[$groupTotalArray[$groupAllocated]['groupTypeName']][] = $groupTotalArray[$groupAllocated]['groupShort'];
		}
		foreach($groupTypeArray as $groupTypeName) {
			if (is_array($thisStudentAllocation[$groupTypeName])) {
				asort($thisStudentAllocation[$groupTypeName]);
				$valueArray[$i][$groupTypeName] = implode(',', $thisStudentAllocation[$groupTypeName]);
			}
		}
		$i++;
	}




   $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Assign Group Advanced Report ');
	$reportManager->setReportInformation("Search By : $search");


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();


//$History : $
//

?>
