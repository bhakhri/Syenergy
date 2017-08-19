<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Ajinder Singh 
// Created on : (15.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
?>
<?php
require_once($FE . "/Library/common.inc.php"); //for sessionId 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GroupsManager {

	private static $instance = null;
	
	private $companyId;
	private $companyCondition;
	
	private function __construct() {
		global $sessionHandler;
		$this->companyId  = $sessionHandler->getSessionVariable('CompanyId');
		$this->companyCondition = "companyId = ".$this->companyId;
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function getGroups($groupText = '') {
		$query = "
					SELECT 
								groupName, groupId 
					FROM		groups 
					WHERE		".$this->companyCondition." 
					AND			groupName LIKE '$groupText%' 
					ORDER BY	groupName 
					LIMIT 0,10";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countGroupName($groupName, $groupId = '') {

		$conditions = '';
		if ($groupId) {
			$conditions = " AND groupId != $groupId ";
		}

		$query = "
					SELECT 
									COUNT(groupName) as cnt 
					FROM 
									groups WHERE ".$this->companyCondition." 
					AND				groupName = '$groupName' $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupId($groupName) {
		$query = "
					SELECT 
									groupId 
					FROM			groups 
					WHERE			".$this->companyCondition." 
					AND				groupName = '$groupName' ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addGroup($groupId, $groupName, $parentGroupId) {
		$query = "
					INSERT INTO 
									groups SET  ".$this->companyCondition.", 
									groupId = $groupId,
									groupName =  '$groupName', 
									parentGroupId = $parentGroupId 
									";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function getMaxGroupId() {
		$query = "
					SELECT 
									max(groupId) as groupId
					FROM			groups 
					WHERE			".$this->companyCondition;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function editGroup($groupId, $groupName, $parentGroupId) {
		$query = "
					UPDATE groups	SET   
									groupName =  '$groupName', 
									parentGroupId = $parentGroupId 
					WHERE			".$this->companyCondition."
					AND				groupId = $groupId
									";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function getTotalGroups($filter = '') {
		$query = "
					SELECT 
									count(grp.groupId) as totalRecords
					FROM			groups grp, groups pgrp 
					WHERE			grp.".$this->companyCondition." 
					AND				grp.companyId = pgrp.companyId
					AND				grp.parentGroupId = pgrp.groupId
					AND				grp.groupId > 5
									$filter";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupsList($filter = '',$limit = '',$orderBy = 'grp.groupName') {
		$query = "
					SELECT 
									grp.groupId,
									grp.groupName,
									pgrp.groupName as parentGroupName
					FROM			groups grp, groups pgrp 
					WHERE			grp.".$this->companyCondition." 
					AND				grp.companyId = pgrp.companyId
					AND				grp.parentGroupId = pgrp.groupId
					AND				grp.groupId > 4
									$filter ORDER BY  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroup($conditions = '') {
		$query = "
					SELECT 
									grp.groupName,
									pgrp.groupName as parentGroup
					FROM			groups grp, groups pgrp 
					WHERE			grp.".$this->companyCondition."
					AND				grp.parentGroupId = pgrp.groupId $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function deleteGroup($groupId) {
		$query = "DELETE FROM groups WHERE ".$this->companyCondition." AND groupId = $groupId";
		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
	}

	public function getGroupName($groupId) {
		$query = "SELECT groupName from groups WHERE ".$this->companyCondition." AND groupId = $groupId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

}//end of class


// $History: GroupsManager.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:27p
//Created in $/LeapCC/Model/Accounts
//file added
//

?>