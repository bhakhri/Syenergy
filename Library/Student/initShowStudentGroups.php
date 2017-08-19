<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignGroupAdvanced');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$degree = $REQUEST_DATA['degree'];
$sortBy = $REQUEST_DATA['sortBy'];
$groupsArray = $studentManager->getClassAllGroups($degree);
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
echo json_encode($resultArray);


//$History: initShowStudentGroups.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:14p
//Created in $/LeapCC/Library/Student
//file added for assign groups advanced
//



?>

