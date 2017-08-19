<?php
//-------------------------------------------------------
//  This File contains logic for groups
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	$mode = add_slashes(trim($REQUEST_DATA['mode']));

	$accessMode = strtolower($mode);
	define('MODULE','Groups');
	define('ACCESS',$accessMode);
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	$groupName		=	add_slashes(trim($REQUEST_DATA['groupName']));
	$parentGroup	=	add_slashes(trim($REQUEST_DATA['parentGroup']));
	$groupId		=	$REQUEST_DATA['groupId'];

	require_once(MODEL_PATH . '/Accounts/GroupsManager.inc.php');
	$groupsManager = GroupsManager::getInstance();
	
	if ($mode == 'Delete') {

		echo 'Group Deletion Stopped';
		die;

		require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
		$voucherManager = VoucherManager::getInstance();

		$parentLedgerArray = $voucherManager->getGroupLedgers($groupId);
		$voucherManager->getGroupTree($parentGroupsArray);
		$parentGroupTree = $voucherManager->treeArray;
		$ledgerArray = $voucherManager->getLedgers($parentGroupTree);
		$ledgerArray = $voucherManager->mergeArrays($ledgerArray, $parentLedgerArray);
		$ledgerList = UtilityManager::makeCSList($ledgerArray, 'ledgerName');

		if ($ledgerList != '') {
			echo LEDGERS_EXIST_UNDER_THIS_GROUP;
			die;
		}
		$returnStatus = $groupsManager->deleteGroup($groupId);
		if($returnStatus === false) {
			echo FAILURE;
			die;
		}
		else {
			echo DELETE;
			die;
		}
	}
	else {
		if (empty($groupName) or $groupName == '') {
			echo ENTER_GROUP_NAME;
			die;
		}
		elseif (empty($parentGroup) or $parentGroup == '') {
			echo ENTER_PARENT_GROUP;
			die;
		}

		$groupName = ucwords($groupName);

		if ($mode == 'Add') {
			$countGroupNameArray = $groupsManager->countGroupName($groupName);
		}
		elseif ($mode == 'Edit') {
			$countGroupNameArray = $groupsManager->countGroupName($groupName, $groupId);
		}

		$cnt = $countGroupNameArray[0]['cnt'];
		if ($cnt) {
			echo GROUP_NAME_ALREADY_EXISTS;
			die;
		}

		$parentGroup = ucwords($parentGroup);
		$parentGroupIdArray = $groupsManager->getGroupId($parentGroup);
		$parentGroupId = $parentGroupIdArray[0]['groupId'];
		if (empty($parentGroupId)) {
			echo INVALID_PARENT_GROUP_ENTERED;
			die;
		}


		if ($mode == 'Add') {
			$maxGroupIdArray = $groupsManager->getMaxGroupId();
			$maxGroupId = $maxGroupIdArray[0]['groupId'];
			$maxGroupId++; //
			$returnStatus = $groupsManager->addGroup($maxGroupId, $groupName, $parentGroupId);
		}
		elseif ($mode == 'Edit') {
			$returnStatus = $groupsManager->editGroup($groupId, $groupName, $parentGroupId);
		}

		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	


// $History: ajaxInitAction.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:47p
//Updated in $/LeapCC/Library/Accounts/Groups
//stopped deletion.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Groups
//file added
//



?>