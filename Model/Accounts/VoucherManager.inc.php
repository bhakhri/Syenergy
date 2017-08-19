<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Ajinder Singh 
// Created on : (15.06.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
?>
<?php
require_once($FE . "/Library/common.inc.php"); //for sessionId 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH . "/UtilityManager.inc.php");

class VoucherManager {

	private static $instance = null;
	
	private $companyId;
	private $companyCondition;
	public $treeArray = Array();
	
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

	public function getChildGroups($parentGroupId) {
		if (empty($parentGroupId)) {
			$parentGroupId = 0;
		}
		$query = "
					SELECT 
								groupId, groupName 
					FROM		groups 
					WHERE		".$this->companyCondition."
					AND			parentGroupId IN ($parentGroupId)
					ORDER BY	showOrder";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupTree($parentGroupsArray) {
		$parentGroupList = '';
		if ($parentGroupsArray[0] != '') {
			$parentGroupList = UtilityManager::makeCSList($parentGroupsArray, 'groupId');
		}
		if ($parentGroupList != '') {
			$this->treeArray[] = $parentGroupsArray;
			$query = "
						SELECT 
									groupId 
						FROM		groups 
						WHERE		".$this->companyCondition."
						AND			parentGroupId IN ($parentGroupList)";
			$childArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			$this->getGroupTree($childArray);
		}
	}

	public function getLedgers($parentGroupTree, $groupText = '') {
		$parentGroupList = '0';
		foreach($parentGroupTree as $parentGroupRecord) {
			$parentGroupList .= ', '.UtilityManager::makeCSList($parentGroupRecord, 'groupId');
		}
		$query = "SELECT ledgerId, ledgerName from ledger WHERE ".$this->companyCondition." AND parentGroupId IN ($parentGroupList) AND ledgerName LIKE '$groupText%'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getGroupLedgers($parentGroupId, $groupText = '') {
		if (empty($parentGroupId)) {
			$parentGroupId = 0;
		}
		$query = "SELECT ledgerId, ledgerName from ledger WHERE ".$this->companyCondition." AND parentGroupId IN ($parentGroupId) AND ledgerName LIKE '$groupText%'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMaxVoucherNo($voucherType) {
		$query = "
				SELECT 
							IF(MAX(CONVERT(voucherNo,UNSIGNED)) IS NULL, 1, MAX(CONVERT(voucherNo,UNSIGNED)) + 1) AS `maxVoucherNo` 
				FROM		voucher_master WHERE ".$this->companyCondition." 
				AND			voucherTypeId = $voucherType";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getBalanceLedgers($array1, $array2) {
		$returnArray = array();
		foreach($array1 as $key1 => $value1) {
			if (!in_array($value1, $array2)) {
				$returnArray[] = $value1;
			}
		}
		return $returnArray;
	}

	public function mergeArrays($array1, $array2) {
		foreach($array2 as $array2Record) {
			$array1[] = $array2Record;
		}
		return $array1;
	}

	public function getGroupTreeLedgers($groupId) {
		$parentGroupsArray = $this->getChildGroups($groupId);
		$parentLedgerArray = $this->getGroupLedgers($groupId);
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$ledgerArray = $this->getLedgers($parentGroupTree);
		$ledgerArray = $this->mergeArrays($ledgerArray, $parentLedgerArray);
		return $ledgerArray;
	}

	public function getLedgersRDPC($groupText = '') {
		$cahBankGroupId = "6";
		$parentGroupsArray = $this->getChildGroups($cahBankGroupId);
		$parentLedgerArray = $this->getGroupLedgers($cahBankGroupId, $groupText);
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$ledgerArray = $this->getLedgers($parentGroupTree, $groupText);
		$ledgerArray = $this->mergeArrays($ledgerArray, $parentLedgerArray);
		return $ledgerArray;
	}

	public function getLedgersRCPDJDJC($groupText = '') {

		$this->treeArray = Array();
		$parentLedgerArray = Array();
		$nonCahBankGroupId = "1,2,3,4";
		$parentGroupsArray = $this->getChildGroups($nonCahBankGroupId);
		$parentLedgerArray = $this->getGroupLedgers($nonCahBankGroupId, $groupText);
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$nonCashLedgerArray = $this->getLedgers($parentGroupTree, $groupText);
		$nonCashLedgerArray = $this->mergeArrays($nonCashLedgerArray, $parentLedgerArray);
		$nonCashLedgerList = UtilityManager::makeCSList($nonCashLedgerArray, 'ledgerName');
		$nonCashLedgerArray = explode(',', $nonCashLedgerList);
		$parentLedgerArray = Array();
		$cahBankGroupId = "6,7";
		$this->treeArray = Array();
		$parentGroupsArray = $this->getChildGroups($cahBankGroupId);
		$parentLedgerArray = $this->getGroupLedgers($cahBankGroupId, $groupText);
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$cashLedgerArray = $this->getLedgers($parentGroupTree, $groupText);
		$cashLedgerArray = $this->mergeArrays($cashLedgerArray, $parentLedgerArray);
		$cashLedgerList = UtilityManager::makeCSList($cashLedgerArray, 'ledgerName');
		$cashLedgerArray = explode(',', $cashLedgerList);
		$balanceLedgersArray = $this->getBalanceLedgers($nonCashLedgerArray, $cashLedgerArray);

		$ledgerArray = Array();
		foreach($balanceLedgersArray as $balanceRecord) {
			$ledgerArray[]['ledgerName'] = $balanceRecord;
		}
		return $ledgerArray;
	}

	public function getLedgersCDCC($groupText = '') {
		$parentLedgerArray = Array();
		$cahBankGroupId = "7,8";
		$this->treeArray = Array();
		$parentGroupsArray = $this->getChildGroups($cahBankGroupId);
		$parentLedgerArray = $this->getGroupLedgers($cahBankGroupId, $groupText);
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$cashLedgerArray = $this->getLedgers($parentGroupTree, $groupText);
		$cashLedgerArray = $this->mergeArrays($cashLedgerArray, $parentLedgerArray);
		return $cashLedgerArray;
	}

	public function getLedgerId($ledgerName = '') {
		$query = "SELECT ledgerId from ledger WHERE ".$this->companyCondition." AND ledgerName IN ($ledgerName)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addVoucherMasterRecordInTransaction($voucherDate,$voucherTypeId, $voucherNo, $narration='') {
		$query = "INSERT INTO voucher_master SET ".$this->companyCondition.", voucherDate = '$voucherDate', voucherTypeId = $voucherTypeId, voucherNo= '$voucherNo', narration = '$narration'";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addVoucherTransRecordInTransaction($transStr) {
		$query = "INSERT INTO voucher_trans (voucherId, ledgerId, drAmount, crAmount) VALUES $transStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getMaxVoucherId($voucherType) {
		$query = "SELECT max(voucherId) as voucherId from voucher_master WHERE ".$this->companyCondition." AND voucherTypeId = '$voucherType'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

	public function checkDateInFinancialYear($thisVoucherDate) {
		$query = "select '$thisVoucherDate' BETWEEN (SELECT fyearFrom FROM company WHERE ".$this->companyCondition.") AND (SELECT fyearTo FROM company WHERE ".$this->companyCondition.") AS cnt";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function countLedgerVouchers($ledgerId) {
		$query = "SELECT COUNT(ledgerId) AS cnt FROM voucher_trans WHERE ".$this->companyCondition." AND ledgerId = $ledgerId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getTreeSum($groupId, $tillDate) {
		$parentLedgerArray = Array();
		$parentGroupsArray = $this->getChildGroups($groupId);
		$parentLedgerArray = $this->getGroupLedgers($groupId);
		$this->treeArray = Array();
		$this->getGroupTree($parentGroupsArray);
		$parentGroupTree = $this->treeArray;
		$childLedgerArray = $this->getLedgers($parentGroupTree);
		$childLedgerArray = $this->mergeArrays($childLedgerArray, $parentLedgerArray);
		$ledgerList = '';
		$ledgerList = UtilityManager::makeCSList($childLedgerArray, 'ledgerName',',',true);
		
		if ($ledgerList == '') {
			$sumArray = Array();
			$sumArray[] = Array('totalDrAmount'=>0, 'totalCrAmount'=>0);
			return $sumArray;
		}
		$ledgerIdArray = $this->getLedgerId($ledgerList);
		$ledgerIdList = UtilityManager::makeCSList($ledgerIdArray, 'ledgerId');

		 $query = "
				SELECT 
								SUM(a.opDrAmount) + 
								(SELECT 
											IFNULL(SUM(b.drAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId in ($ledgerIdList) 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalDrAmount, 
								SUM(a.opCrAmount) + 
								(SELECT 
											IFNULL(SUM(b.crAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId IN ($ledgerIdList) 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalCrAmount 
				FROM			ledger a WHERE a.ledgerId IN ($ledgerIdList)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getLedgerSum($ledgerId, $tillDate) {

		$query = "
				SELECT 
								SUM(a.opDrAmount) + 
								(SELECT 
											IFNULL(SUM(b.drAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId in ($ledgerId) 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalDrAmount, 
								SUM(a.opCrAmount) + 
								(SELECT 
											IFNULL(SUM(b.crAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId IN ($ledgerId) 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalCrAmount 
				FROM			ledger a WHERE a.ledgerId IN ($ledgerId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getOpeningBalances() {
		$query = "SELECT SUM(opDrAmount) AS opDrAmount, SUM(opCrAmount) AS opCrAmount FROM ledger WHERE ".$this->companyCondition;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMainChildrenGroups() {
		$query = "SELECT groupId,groupName FROM groups WHERE ".$this->companyCondition." AND parentGroupId IN (1,2,3,4) ORDER BY groupName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getOneLevelTree($groupId, $tillDate) {
		$parentGroupsArray = $this->getChildGroups($groupId);
		$parentLedgerArray = $this->getGroupLedgers($groupId);
		return Array('parentGroupsArray' => $parentGroupsArray, 'parentLedgerArray' => $parentLedgerArray);
	}

	public function getLedgerOpeningBalance($ledgerId) {
		$query = "SELECT ledgerName, opDrAmount, opCrAmount FROM ledger WHERE ".$this->companyCondition." AND ledgerId IN ($ledgerId)";;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getMultiLedgerOpeningBalance($ledgerId) {
		$query = "SELECT SUM(opDrAmount) AS opDrAmount, SUM(opCrAmount) AS opCrAmount FROM ledger WHERE ".$this->companyCondition." AND ledgerId IN ($ledgerId)";;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getLedgerVouchers($ledgerId, $tillDate) {
		$query = "SELECT a.voucherId, a.voucherDate, a.voucherTypeId, a.voucherNo, b.ledgerId, if(count(c.ledgerName)=1,c.ledgerName,'as per details') as ledgerName, SUM(b.drAmount) as crAmount, SUM(b.crAmount) as drAmount from voucher_master a, voucher_trans b, ledger c WHERE a.".$this->companyCondition." AND a.voucherId = b.voucherId AND a.voucherDate <= '$tillDate'  AND b.ledgerId = c.ledgerId AND b.voucherId IN (SELECT voucherId from voucher_trans WHERE ledgerId IN ($ledgerId)) AND b.ledgerId NOT IN ($ledgerId) GROUP BY a.voucherDate, a.voucherTypeId, a.voucherNo ORDER BY a.voucherId, a.voucherNo, b.transId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getDayVouchers($tillDate) {
		$query = "SELECT a.voucherId, a.voucherDate, a.voucherTypeId, a.voucherNo, b.ledgerId, c.ledgerName,b.drAmount, b.crAmount FROM voucher_master a, voucher_trans b, ledger c WHERE a.".$this->companyCondition." AND a.voucherId = b.voucherId AND b.ledgerId = c.ledgerId AND a.voucherDate = '$tillDate' GROUP BY a.voucherDate, a.voucherTypeId, a.voucherNo ORDER BY a.voucherId, a.voucherNo, b.transId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVoucherMasterDetails($voucherId) {
		$query = "SELECT voucherDate, voucherTypeId, voucherNo, narration FROM voucher_master WHERE ".$this->companyCondition." AND voucherId = $voucherId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVoucherTransDetails($voucherId) {
		$query = "SELECT a.ledgerId, b.ledgerName, a.drAmount,a.crAmount FROM voucher_trans a, ledger b WHERE a.voucherId = $voucherId AND a.ledgerId = b.ledgerId ORDER BY transId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function isVoucherValid($voucherId) {
		$query = "SELECT COUNT(b.voucherId) AS cnt FROM voucher_master a, voucher_trans b WHERE a.".$this->companyCondition." AND a.voucherId = b.voucherId AND a.voucherId = $voucherId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getVoucherType($voucherId) {
		$query = "SELECT voucherTypeId FROM voucher_master where ".$this->companyCondition." AND voucherId = $voucherId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function updateVoucherMasterInTransaction($editVoucherId, $thisVoucherDate, $editVoucherType, $voucherNo, $narration='') {
		$query = "UPDATE voucher_master SET voucherDate = '$thisVoucherDate', voucherTypeId = $editVoucherType, voucherNo= '$voucherNo', narration = '$narration' WHERE ".$this->companyCondition." AND voucherId = $editVoucherId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function updateSameTypeVoucherMasterInTransaction($editVoucherId, $thisVoucherDate, $voucherNo, $narration='') {
		$query = "UPDATE voucher_master SET voucherDate = '$thisVoucherDate', voucherNo= '$voucherNo', narration = '$narration' WHERE ".$this->companyCondition." AND voucherId = $editVoucherId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteVoucherTransInTransaction($editVoucherId) {
		$query = "DELETE FROM voucher_trans WHERE voucherId = $editVoucherId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getTypeVouchers($voucherTypeId, $tillDate) {
		$query = "SELECT a.voucherId, a.voucherDate, a.voucherTypeId, a.voucherNo, b.ledgerId, c.ledgerName,b.drAmount, b.crAmount FROM voucher_master a, voucher_trans b, ledger c WHERE a.".$this->companyCondition." AND a.voucherId = b.voucherId AND b.ledgerId = c.ledgerId AND a.voucherTypeId = $voucherTypeId AND a.voucherDate <= '$tillDate' GROUP BY a.voucherDate, a.voucherTypeId, a.voucherNo ORDER BY a.voucherId, a.voucherNo, b.transId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getEachLedgerSum($tillDate) {

		$query = "
				SELECT 
								a.ledgerId, a.ledgerName, 
								SUM(a.opDrAmount) + 
								(SELECT 
											IFNULL(SUM(b.drAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId = a.ledgerId 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalDrAmount, 
								SUM(a.opCrAmount) + 
								(SELECT 
											IFNULL(SUM(b.crAmount),0) 
								FROM		voucher_trans b, voucher_master c 
								WHERE		b.ledgerId = a.ledgerId 
								AND			b.voucherId = c.voucherId 
								AND			c.".$this->companyCondition." 
								AND			c.voucherDate <= '$tillDate'
								) AS totalCrAmount 
				FROM			ledger a 
				GROUP BY		a.ledgerId ORDER BY a.ledgerName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getLedgerVoucherDetails($ledgerId, $tillDate) {
		$query = "SELECT a.voucherId, a.voucherDate, a.voucherTypeId, a.voucherNo, b.ledgerId, c.ledgerName, SUM(b.drAmount) as crAmount, SUM(b.crAmount) as drAmount, a.narration from voucher_master a, voucher_trans b, ledger c WHERE a.".$this->companyCondition." AND a.voucherId = b.voucherId AND a.voucherDate <= '$tillDate'  AND b.ledgerId = c.ledgerId AND b.voucherId IN (SELECT voucherId from voucher_trans WHERE ledgerId IN ($ledgerId)) AND b.ledgerId NOT IN ($ledgerId) GROUP BY a.voucherDate, a.voucherTypeId, a.voucherNo ORDER BY a.voucherId, a.voucherNo, b.transId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

}//end of class


// $History: VoucherManager.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:27p
//Created in $/LeapCC/Model/Accounts
//file added
//



?>