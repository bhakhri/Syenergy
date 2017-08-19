<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignGroupsToStudents');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	$classId = $REQUEST_DATA['degree'];
	$groupType = $REQUEST_DATA['groupType'];

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	$groupTypeNameArray = $studentManager->getGroupTypeName($groupType);
	$groupTypeName = $groupTypeNameArray[0]['groupTypeName'];



	if ($groupType == 1) { //Tutorial
		//find last levels groups for tutorial
		$lastLevelGroupsArray = $commonQueryManager->getLastLevelGroups('a.groupId'," AND a.classId = $classId and a.groupTypeId = $groupType");
	}
	else if ($groupType == 3) { //Theory
		$tutGroupsExist = $sessionHandler->getSessionVariable('TUTORIAL_GROUP_EXISTS');
		if ($tutGroupsExist) {
			//first find last level groups for tutorial, and then its parents
			$lastLevelGroupsArray = $commonQueryManager->getLastLevelGroups('a.groupId'," AND a.classId = $classId and a.groupTypeId = 1");
			if (count($lastLevelGroupsArray) == 0) {
				echo PLEASE_CREATE_.'Tutorial'._GROUPS_FIRST;
				die;
			}
			$lastLevelGroups = UtilityManager::makeCSList($lastLevelGroupsArray, 'groupId');
			$lastLevelGroupsArray = $studentManager->getClassGroups($classId, $lastLevelGroups, 3);
		}
		else {
			$lastLevelGroupsArray = $commonQueryManager->getLastLevelTypeGroups('a.groupId',$groupType, " where a.classId = $classId and a.groupTypeId = $groupType");
		}
		
	}
	else { //All others
		//find last level groups for all others
		//$lastLevelGroupsArray = $commonQueryManager->getLastLevelGroups('a.groupId'," AND a.classId = $classId and a.groupTypeId = $groupType");
		
		require_once(BL_PATH . "/HtmlFunctions.inc.php");
		$parentGroupsArray = $studentManager->getParentGroups($classId,3);
		$allGroups = array();
		foreach($parentGroupsArray as $groupRecord) {
			$groupId = $groupRecord['groupId'];
			//$allGroups[] = $groupId;
			$childGroupArray = $studentManager->getGroupChildren($classId, " where parentGroupId = $groupId AND groupTypeId = $groupType");
			if (count($childGroupArray)) {
				foreach($childGroupArray as $key => $value) {
					$allGroups[] = (int)$value;
				}
			}
		}
		$str = '';
		foreach($allGroups as $key => $value) {
			if (!empty($str)) {
				$str .= ',';
			}
			$str .= $value;
		}
		if ($str == '') {
			echo PLEASE_CREATE_.$groupTypeName._GROUPS_FIRST;
			die;
		}
		$lastLevelGroupsArray = $studentManager->getGroupDetails($str);
		
	}
	if (count($lastLevelGroupsArray) == 0) {
		echo PLEASE_CREATE_.$groupTypeName._GROUPS_FIRST;
		die;
	}
	echo json_encode($lastLevelGroupsArray);

   
// for VSS
// $History: getClassGroups.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 9/07/09    Time: 6:12p
//Updated in $/LeapCC/Library/Student
//updated message.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-08-20   Time: 2:11p
//Updated in $/LeapCC/Library/Student
//Added Role based config DEFINE
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/14/09    Time: 5:54p
//Updated in $/LeapCC/Library/Student
//fetched group name, and applied validation if no group exists for
//selected group type.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/31/09    Time: 5:06p
//Updated in $/LeapCC/Library/Student
//made the coding, if tutorial groups are not mandatory
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/04/09    Time: 6:50p
//Updated in $/LeapCC/Library/Student
//fixed coding issue.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/30/08   Time: 4:35p
//Updated in $/LeapCC/Library/Student
//changed condition for hierarchy
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Library/Student
//updated code for student group assignment.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:36p
//Created in $/LeapCC/Library/Student
//file added for student group allocation
//


?>
