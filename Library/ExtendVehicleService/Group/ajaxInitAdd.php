<?php
//-------------------------------------------------------
// Purpose: To add group detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
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
		require_once(MODEL_PATH . "/GroupManager.inc.php");
		$concatDegreeId = $REQUEST_DATA['degree'];
		$optional = $REQUEST_DATA['optional'];

		if($REQUEST_DATA['parentGroup']!=''){
			$classIDArr1 = GroupManager::getInstance()->getParentClass($REQUEST_DATA['parentGroup']);
		}

		$foundArray = GroupManager::getInstance()->getGroup(' WHERE (LCASE(groupName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['groupName']))).'" OR UCASE(groupShort)="'.add_slashes(strtoupper($REQUEST_DATA['groupShort'])).'")');
		if(trim($foundArray[0]['groupName'])=='') {
			if(isset($classIDArr1[0]['classId']) and trim($concatDegreeId)!= $classIDArr1[0]['classId']) {
				echo PARENTCLASS_NOT_EXIST;
				exit;
			}
			else{
				$optional = $REQUEST_DATA['optional'];
				$optionalSubject = trim($REQUEST_DATA['optionalSubject']);
				if ($optional == '1') {
					if (empty($optionalSubject)) {
						echo OPTIONAL_SUBJECT_NOT_FOUND;
						die;
					}
					else {
						$groupTypeId = $REQUEST_DATA['groupTypeName'];
						$groupTypeCodeArray = GroupManager::getInstance()->getGroupTypeCode($groupTypeId);
						$groupTypeCode = $groupTypeCodeArray[0]['groupTypeCode'];
						$groupTypeName = $groupTypeCodeArray[0]['groupTypeName'];
						$optionalSubjectTypeCodeArray = GroupManager::getInstance()->getSubjectTypeCode($optionalSubject);
						$optionalSubjectTypeCode = $optionalSubjectTypeCodeArray[0]['subjectTypeCode'];
						$optionalSubjectTypeName = $optionalSubjectTypeCodeArray[0]['subjectTypeName'];
						if ($groupTypeCode != $optionalSubjectTypeCode) {
							echo SUBJECT_TYPE_."'".$optionalSubjectTypeName."'"._DOES_NOT_MATCH_WITH_GROUP_TYPE_."'".$groupTypeName."'";
							die;
						}
					}
				}
				$returnStatus = GroupManager::getInstance()->addGroup($concatDegreeId,$optional);
			}
			if($returnStatus == false) {
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

// $History: ajaxInitAdd.php $
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/23/10    Time: 12:34p
//Updated in $/LeapCC/Library/Group
//fixed bug nos. 0002686, 0002685, 0002669
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Library/Group
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/11/09    Time: 2:28p
//Updated in $/LeapCC/Library/Group
//modification in code to show groups on that session
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/11/09    Time: 6:14p
//Updated in $/LeapCC/Library/Group
//put condition to check group name & code will not same at same classId
//on add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 10:56a
//Updated in $/LeapCC/Library/Group
//put new field isOptional & check conditions
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 6:49p
//Updated in $/LeapCC/Library/Group
//modified in message
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:10p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/25/08    Time: 5:55p
//Updated in $/Leap/Source/Library/Group
//class should not be different from parent group class, modified in add
//or edit group
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:20p
//Updated in $/Leap/Source/Library/Group
//modified in messages
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/21/08    Time: 1:01p
//Updated in $/Leap/Source/Library/Group
//modified in messages
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/11/08    Time: 1:50p
//Updated in $/Leap/Source/Library/Group
//modified for duplicate records
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/29/08    Time: 11:14a
//Updated in $/Leap/Source/Library/Group
//modified in code if class ID is 0
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:05p
//Updated in $/Leap/Source/Library/Group
//modified for add & edit
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/08/08    Time: 12:09p
//Updated in $/Leap/Source/Library/Group
//remove the echo, use to check the classid
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
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//changed duplicate message and single quote to double quotes in error
//messages
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:46p
//Updated in $/Leap/Source/Library/States
//To add comments and Refine the code: DONE

?>