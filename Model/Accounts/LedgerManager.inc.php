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

class LedgerManager {

	private static $instance = null;
	
	private $companyId;
	private $companyCondition;
	private $companyConditionWithJoin;
	
	private function __construct() {
		global $sessionHandler;
		$this->companyId  = $sessionHandler->getSessionVariable('CompanyId');
		$this->companyCondition = " companyId = ".$this->companyId;
		$this->companyConditionWithJoin = " led.companyId = grp.companyId AND led.companyId = ".$this->companyId;
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function getLedgerGroups($groupText) {
		$query = "
					SELECT 
								groupName, groupId 
					FROM		groups 
					WHERE		".$this->companyCondition." 
					AND			groupName LIKE '$groupText%' 
					ORDER BY	groupName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countLedgerName($ledgerName, $ledgerId = '') {

		$conditions = '';
		if ($ledgerId) {
			$conditions = " AND ledgerId != $ledgerId ";
		}

		$query = "
					SELECT 
									COUNT(ledgerName) as cnt 
					FROM 
									ledger WHERE ".$this->companyCondition." 
					AND				ledgerName = '$ledgerName' $conditions";
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

	public function addLedger($ledgerName, $parentGroupId, $opBalance) {
		$query = "
					INSERT INTO 
									ledger SET  ".$this->companyCondition.", 
									ledgerName =  '$ledgerName', 
									parentGroupId = $parentGroupId 
									$opBalance";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function editLedger($ledgerId, $ledgerName, $parentGroupId, $opBalance) {
		$query = "
					UPDATE ledger	SET   
									ledgerName =  '$ledgerName', 
									parentGroupId = $parentGroupId 
									$opBalance
					WHERE			".$this->companyCondition."
					AND				ledgerId = $ledgerId
									";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
	}

	public function getTotalLedgers($filter = '') {
		$query = "
					SELECT 
									COUNT(led.ledgerId) as totalRecords
					FROM			ledger led, groups grp 
					WHERE			".$this->companyConditionWithJoin."
					AND				led.parentGroupId = grp.groupId $filter ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getLedgersList($filter = '',$limit,$orderBy) {
		$query = "
					SELECT 
									led.ledgerId, 
									led.ledgerName, 
									grp.groupName,
									led.opDrAmount,
									led.opCrAmount
					FROM			ledger led, groups grp 
					WHERE			".$this->companyConditionWithJoin."
					AND				led.parentGroupId = grp.groupId $filter ORDER BY  $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getLedger($conditions = '') {
		$query = "
					SELECT 
									led.ledgerId, 
									led.ledgerName, 
									grp.groupName,
									led.opDrAmount,
									led.opCrAmount
					FROM			ledger led, groups grp 
					WHERE			".$this->companyConditionWithJoin."
					AND				led.parentGroupId = grp.groupId $conditions ORDER BY led.ledgerName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function deleteLedger($ledgerId) {
		$query = "DELETE FROM ledger WHERE ".$this->companyCondition." AND ledgerId = $ledgerId";
		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
	}

	public function getLedgerName($ledgerId) {
		$query = "SELECT ledgerName from ledger WHERE ".$this->companyCondition." AND ledgerId = $ledgerId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

}//end of class


// $History: LedgerManager.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:27p
//Created in $/LeapCC/Model/Accounts
//file added
//

?>