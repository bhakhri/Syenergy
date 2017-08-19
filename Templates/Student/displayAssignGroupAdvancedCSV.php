 <?php
//This file is used as printing version for display Assign Group.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    require_once(MODEL_PATH . "/StudentManager.inc.php");
	 $studentManager = StudentManager::getInstance();


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





    $csvData ='';
	 $reportTableHead = array_keys($reportTableHead);
    $csvData .= implode(",", $reportTableHead);
    $csvData .="\n";
	 foreach($valueArray as $valueRecord) {
		$csvData .= $valueRecord['srNo'];
		$csvData .= "," . $valueRecord['rollNo'];
		$csvData .= "," . $valueRecord['universityRollNo'];
		$csvData .= "," . $valueRecord['studentName'];
		foreach($groupTypeArray as $groupTypeName) {
				$csvData .= ',"' . $valueRecord[$groupTypeName].'"';
		}
		 $csvData .="\n";
	 }


 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'AssignGroupAdvanced.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary/n");
echo $csvData;
die;
//$History : $
?>