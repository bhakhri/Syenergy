<?php
//-------------------------------------------------------
// Purpose: conatins logic of group-privileges copying
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
if (!isset($callGroupPrivilegesCopyCodeFile) or empty($callGroupPrivilegesCopyCodeFile) or $callGroupPrivilegesCopyCodeFile == false) {
	echo INVALID_DETAILS_FOUND;
	die;
}

$mappingArray = array();

$oldClassPrivilegesArray = $groupManager->getOldClassPrivileges($oldClassId);
if (is_array($oldClassPrivilegesArray) and count($oldClassPrivilegesArray) > 0) {
	$oldToNewGroupArray = $groupManager->getOldNewGroups($oldClassId, $newClassId);
	if (is_array($oldToNewGroupArray) and count($oldToNewGroupArray) > 0) {
		foreach($oldToNewGroupArray as $oldNewRecord) {
			$oldGroupId = $oldNewRecord['oldGroupId'];
			$newGroupId = $oldNewRecord['newGroupId'];
			$mappingArray[$oldGroupId] = $newGroupId;
		}
	}
	foreach($oldClassPrivilegesArray as $oldClassRecord) {
		$userId = $oldClassRecord['userId'];
		$roleId = $oldClassRecord['roleId'];
		$oldGroupId = $oldClassRecord['groupId'];
		$newGroupId = $mappingArray[$oldGroupId];
		$return = $groupManager->doGroupPrivilegesUpdation($oldClassId,$oldGroupId,$newClassId,$newGroupId);
		if ($return == false) {
			echo FAILURE_WHILE_COPYING_GROUP_PRIVILEGES;
			die;
		}
	}
}
// $History: ajaxCopyGroupPrivilegesCode.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:42p
//Created in $/LeapCC/Library/Group
//file added for new interface of session end activities
//




?>