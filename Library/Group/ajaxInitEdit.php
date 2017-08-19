<?php
//-------------------------------------------------------
// Purpose: To update group table data
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/GroupManager.inc.php");
define('MODULE','GroupMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$groupManager = GroupManager::getInstance();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['degree']) || trim($REQUEST_DATA['degree']) == '') {
        $errorMessage .= CHOOSE_DEGREE_NAME. "\n";
    }
    /*if (!isset($REQUEST_DATA['batch']) || trim($REQUEST_DATA['batch']) == '') {
        $errorMessage .= CHOOSE_BATCH_NAME. "\n";
    }
    if (!isset($REQUEST_DATA['studyPeriod']) || trim($REQUEST_DATA['studyPeriod']) == '') {
        $errorMessage .= CHOOSE_PERIOD_NAME. "\n";
    }*/
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupName']) || trim($REQUEST_DATA['groupName']) == '')) {
        $errorMessage .= ENTER_GROUP_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupShort']) || trim($REQUEST_DATA['groupShort']) == '')) {
        $errorMessage .= ENTER_GROUP_SHORT. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupTypeName']) || trim($REQUEST_DATA['groupTypeName']) == '')) {
        $errorMessage .= CHOOSE_GROUP_TYPE. "\n";
    }

    if (trim($errorMessage) == '') {
        

        $concatDegreeId = $REQUEST_DATA['degree'];
		$groupID = $REQUEST_DATA['groupId'];
		$parentGroupID = $REQUEST_DATA['parentGroup'];
		$optional = $REQUEST_DATA['optional'];
		if($groupID === $parentGroupID) {
			echo ("Parent Group cannot be parent of itself");
			exit(0);
		}

		if($REQUEST_DATA['parentGroup']!=''){
			$classIDArr1 = $groupManager->getParentClass($REQUEST_DATA['parentGroup']);
		}

		if ($parentGroupID != '') {
			$selfParentArray = $groupManager->checkSelfParent($groupID, $parentGroupID);
			$selfParentCount = $selfParentArray[0]['cnt'];
			if ($selfParentCount > 0) {
				echo 'Sub-group should not be parent of main group';
				die;
			}
		}

		$foundGroupArr = $groupManager->checkGroupInTimeTable($groupID);
		$foundGroupCount = $foundGroupArr[0]['found'];
		if($foundGroupCount > 0){
			echo 'This group is used in time table, you cannot edit this group';
			die;
		}

		$foundGroupArr = $groupManager->getGroupCompare($groupID);
		$foundGroupCount = $foundGroupArr[0]['found'];
		if ($foundGroupCount > 0) {
			echo 'This group is allocated to student, you cannot edit this group';
			die;
		}

		$foundStudentGroupArr = $groupManager->getStudentOptionalGroup($groupID);
		$foundStudentGroupCount = $foundStudentGroupArr[0]['found'];
		if ($foundStudentGroupCount > 0) {
			echo 'This group is already allocated to student, you cannot edit this group';
			die;
		}

		$foundParentArr = $groupManager->getParent($groupID);
		$foundParentCount = $foundParentArr[0]['parentGroupId'];
		if ($foundParentCount > 0) {
			echo 'Cant make parent, it is already parent of child';
			die;
		}

		if($REQUEST_DATA['optional'] == '') {
			$foundOptionalParentArr = $groupManager->getOptionalParent($groupID);
			$foundOptionalParentCount = $foundOptionalParentArr[0]['parentGroupId'];
			if ($foundOptionalParentCount > 0) {
				echo 'Cant make non-optional, it is already parent of child';
				die;
			}
		}

		$foundArray = $groupManager->getGroup(' WHERE (LCASE(groupName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['groupName']))).'" OR UCASE(groupShort)="'.add_slashes(strtoupper($REQUEST_DATA['groupShort'])).'") AND groupId!='.$REQUEST_DATA['groupId']);

		if(trim($foundArray[0]['groupName'])=='') {  //DUPLICATE CHECK
			//$foundArray2 = GroupManager::getInstance()->getGroup(' WHERE UCASE(groupShort)="'.add_slashes(strtoupper($REQUEST_DATA['groupShort'])).'" AND groupId!='.$REQUEST_DATA['groupId']);
					if(isset($classIDArr1[0]['classId']) and trim($concatDegreeId)!=$classIDArr1[0]['classId']) {
						echo PARENTCLASS_NOT_EXIST;
						exit;
					}
					else {
						$optional = $REQUEST_DATA['optional'];
						$optionalSubject = trim($REQUEST_DATA['optionalSubject']);
						if ($optional == '1') {
							if (empty($optionalSubject)) {
								echo OPTIONAL_SUBJECT_NOT_FOUND;
								die;
							}
							else {
								$groupTypeId = $REQUEST_DATA['groupTypeName'];
								$groupTypeCodeArray = $groupManager->getGroupTypeCode($groupTypeId);
								$groupTypeCode = $groupTypeCodeArray[0]['groupTypeCode'];
								$groupTypeName = $groupTypeCodeArray[0]['groupTypeName'];
								$optionalSubjectTypeCodeArray = $groupManager->getSubjectTypeCode($optionalSubject);
								$optionalSubjectTypeCode = $optionalSubjectTypeCodeArray[0]['subjectTypeCode'];
								$optionalSubjectTypeName = $optionalSubjectTypeCodeArray[0]['subjectTypeName'];
								if ($groupTypeCode != $optionalSubjectTypeCode) {
									echo SUBJECT_TYPE_."'".$optionalSubjectTypeName."'"._DOES_NOT_MATCH_WITH_GROUP_TYPE_."'".$groupTypeName."'";
									die;
								}
							}
						}
						$returnStatus = $groupManager->editGroup($REQUEST_DATA['groupId'],$concatDegreeId,$optional);
					}

					if($returnStatus === false) {
						echo FAILURE;
					}
					else {
						echo SUCCESS;
               }
				}
				else {
					if(strtoupper($foundArray[0]['groupShort'])==trim(strtoupper($REQUEST_DATA['groupShort']))) {
					 echo GROUP_ALREADY_EXIST;
					 die;
				   }
				   else if(strtoupper($foundArray[0]['groupName'])==trim(strtoupper($REQUEST_DATA['groupName']))) {
					   echo GROUP_NAME_EXIST;
					   die;
				   }
			}
		}
		else {
			echo $errorMessage;
		}

// $History: ajaxInitEdit.php $
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:19p
//Updated in $/LeapCC/Library/Group
//add field optional subjet for optional group
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/23/10    Time: 12:34p
//Updated in $/LeapCC/Library/Group
//fixed bug nos. 0002686, 0002685, 0002669
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/26/09    Time: 12:20p
//Updated in $/LeapCC/Library/Group
//Gurkeerat: fixed issue 1251
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Library/Group
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/11/09    Time: 2:28p
//Updated in $/LeapCC/Library/Group
//modification in code to show groups on that session
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/10/09    Time: 12:45p
//Updated in $/LeapCC/Library/Group
//modified in message
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/11/09    Time: 6:14p
//Updated in $/LeapCC/Library/Group
//put condition to check group name & code will not same at same classId
//on add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/11/09    Time: 3:52p
//Updated in $/LeapCC/Library/Group
//added optional field functionality
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/10/09    Time: 10:56a
//Updated in $/LeapCC/Library/Group
//put new field isOptional & check conditions
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/17/08    Time: 2:01p
//Updated in $/Leap/Source/Library/Group
//modification for validations
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/03/08    Time: 3:25p
//Updated in $/Leap/Source/Library/Group
//check already parent of group
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 9/03/08    Time: 1:45p
//Updated in $/Leap/Source/Library/Group
//applied cyclic check
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:11p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/25/08    Time: 5:55p
//Updated in $/Leap/Source/Library/Group
//class should not be different from parent group class, modified in add
//or edit group
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:20p
//Updated in $/Leap/Source/Library/Group
//modified in messages
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/13/08    Time: 1:41p
//Updated in $/Leap/Source/Library/Group
//modified in alert message
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/13/08    Time: 1:16p
//Updated in $/Leap/Source/Library/Group
//modified to choose parent group name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/11/08    Time: 1:51p
//Updated in $/Leap/Source/Library/Group
//modified for duplicate records
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/29/08    Time: 11:15a
//Updated in $/Leap/Source/Library/Group
//modified in code if class ID is 0
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:06a
//Updated in $/Leap/Source/Library/Group
//modified in functions for edit, add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search
?>