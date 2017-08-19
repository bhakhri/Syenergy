<?php
//-------------------------------------------------------
// Purpose: To delete state detail
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['groupId']) || trim($REQUEST_DATA['groupId']) == '') {
        $errorMessage = INVALID_GROUP;
    }
	$groupID = $REQUEST_DATA['groupId'];
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GroupManager.inc.php");
		
		$parentGroupArr = GroupManager::getInstance()->getParent($groupID);
		if ($parentGroupArr[0]['parentGroupId'] > 0) {
			echo 'Parent-Child relation exist cannot delete parent, first delete the child';
			die;
		}

		$studentGroupArr = GroupManager::getInstance()->getGroupCompare($groupID);
		if ($studentGroupArr[0]['found'] > 0) {
			echo 'This group is allocated to student, you cannot delete this group';
			die;
		}

		$studentOptionalGroupArr = GroupManager::getInstance()->getStudentOptionalGroup($groupID);
		if ($studentOptionalGroupArr[0]['found'] > 0) {
			echo 'This group is already allocated to student, you cannot delete this group';
			die;
		}

		$classVisibleRoleArr = GroupManager::getInstance()->getVisibleToRoleGroup($groupID);
		if ($classVisibleRoleArr[0]['found'] > 0) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

		$timeTableGroupArr = GroupManager::getInstance()->getTimeTableGroup($groupID);
		if ($timeTableGroupArr[0]['found'] > 0) {
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

        $groupManager =  GroupManager::getInstance();
        if($recordArray[0]['found']==0) {
            if($groupManager->deleteGroup($REQUEST_DATA['groupId']) ) {
                echo DELETE;
            }
            else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/19/10    Time: 6:28p
//Updated in $/LeapCC/Library/Group
//fixed query error during delete group to check whether group is using
//in visible_to_class_role table
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/16/09   Time: 3:57p
//Updated in $/LeapCC/Library/Group
//put condition to check if group is allocated to student or optional
//subject
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/17/08    Time: 2:01p
//Updated in $/Leap/Source/Library/Group
//modification for validations
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:10p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/08    Time: 4:00p
//Updated in $/Leap/Source/Library/Group
//modified message
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:04p
//Updated in $/Leap/Source/Library/Group
//modified for edit 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search

?>