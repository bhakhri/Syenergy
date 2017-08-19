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

class CompanyManager {

	private static $instance = null;
	
	private function __construct() {
		global $sessionHandler;
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function getTotalCompanies($conditions = '') {
		$query = "SELECT count(companyId) as totalRecords  from company $conditions ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getCompaniesList($conditions = '', $limit = '',$orderBy = 'companyName') {
		$query = "SELECT companyId, companyName, address, email, phone, fyearFrom, concat(date_format(fyearFrom,'%d-%b-%Y'),' to ',date_format(fyearTo,'%d-%b-%Y')) as financialYear from company $conditions ORDER BY $orderBy $limit ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addCompany($companyName, $address, $email, $phone, $fyearFrom) {
		$query = "INSERT INTO company(companyName, fyearFrom, fyearTo, address, email, phone) VALUES ('$companyName', '$fyearFrom', DATE_ADD('$fyearFrom',INTERVAL 364 DAY), '$address', '$email', '$phone')";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function getLatestCompanyId() {
		$query = "SELECT MAX(companyId) AS companyId FROM company";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function addGroupsToCompany($companyId) {
		$groupsArray = Array();
		$groupsArray[] = Array(1,'Assets', 0, 0);
		$groupsArray[] = Array(2,'Liabilities', 0, 0);
		$groupsArray[] = Array(3,'Income', 0, 0);
		$groupsArray[] = Array(4,'Expenditure', 0, 0);
		$groupsArray[] = Array(5,'Profit / Loss Account', 0, 0);
		$groupsArray[] = Array(6,'Current Assets', 1, 6);
		$groupsArray[] = Array(7,'Cash-in-hand', 6, 0);
		$groupsArray[] = Array(8,'Bank', 6, 0);
		$groupsArray[] = Array(9,'Current Liabilities', 2, 3);
		$groupsArray[] = Array(10,'Capital Account', 2, 1);
		$groupsArray[] = Array(11,'Loans (Liability)', 2, 2);
		$groupsArray[] = Array(12,'Direct Expenses', 4, 13);
		$groupsArray[] = Array(13,'Indirect Incomes', 3, 14);
		$groupsArray[] = Array(14,'Direct Incomes', 3, 12);
		$groupsArray[] = Array(15,'Indirect Expenses', 4, 15);
		$groupsArray[] = Array(16,'Fixed Assets', 1, 4);
		$groupsArray[] = Array(17,'Investments', 1, 5);
		$groupsArray[] = Array(18,'Branch / Divisions', 2, 7);
		$groupsArray[] = Array(19,'Misc. Expenses (Assets)', 1, 8);
		$groupsArray[] = Array(20,'Reserves And Surplus', 10, 0);
		$groupsArray[] = Array(21,'Bank OD Account', 11, 0);
		$groupsArray[] = Array(22,'Secured Loans', 11, 0);
		$groupsArray[] = Array(23,'Unsecured Loans', 11, 0);
		$groupsArray[] = Array(24,'Duties And Taxes', 9, 0);
		$groupsArray[] = Array(25,'Provisions', 9, 0);
		$groupsArray[] = Array(26,'Sundry Creditors', 9, 0);
		$groupsArray[] = Array(27,'Deposits (Assets)', 6, 0);
		$groupsArray[] = Array(28,'Loans And Advances', 6, 0);
		$groupsArray[] = Array(29,'Sundry Debtors', 6, 0);

		$insertStr = '';
		foreach($groupsArray as $groupRecordArray) {
			list($groupId, $groupName, $parentGroupId, $showOrder) = $groupRecordArray;
			if (!empty($insertStr)) {
				$insertStr .= ', ';
			}
			$insertStr .= "($companyId, $groupId, '$groupName', $parentGroupId, $showOrder)";
		}

		$query = "INSERT INTO groups(companyId, groupId, groupName, parentGroupId, showOrder) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function addLedgersToCompany($companyId) {
		$ledgersArray = Array();
		$ledgersArray[] = Array('Cash', 7);
		$ledgersArray[] = Array('Profit / Loss Account', 5);
		$insertStr = '';
		foreach($ledgersArray as $ledgerRecordArray) {
			list($ledgerName, $parentGroupId) = $ledgerRecordArray;
			if (!empty($insertStr)) {
				$insertStr .= ', ';
			}
			$insertStr .= "($companyId, '$ledgerName', $parentGroupId, 0, 0)";
		}

		$query = "INSERT INTO ledger(companyId, ledgerName, parentGroupId, opDrAmount, opCrAmount) VALUES $insertStr";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function checkVouchersOutOfFYearDate($companyId, $fyearFrom) {
		$query = "SELECT COUNT(voucherId) AS cnt FROM voucher_master WHERE companyId = $companyId AND (voucherDate NOT BETWEEN '$fyearFrom' AND DATE_ADD('$fyearFrom',INTERVAL 364 DAY))";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function deleteCompanyVouchersTrans($companyId) {
		$query = "DELETE FROM voucher_trans WHERE voucherId IN (SELECT voucherId FROM voucher_master WHERE companyId = $companyId)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteCompanyVouchersMaster($companyId) {
		$query = "DELETE FROM voucher_master WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteCompanyLedgers($companyId) {
		$query = "DELETE FROM ledger WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteCompanyGroups($companyId) {
		$query = "DELETE FROM groups WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function deleteCompany($companyId) {
		$query = "DELETE FROM company WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

	public function editCompany($companyId, $companyName, $address, $email, $phone, $fyearFrom) {
		$query = "UPDATE company SET companyName = '$companyName', address = '$address', email = '$email', phone = '$phone', fyearFrom = '$fyearFrom',  fyearTo = DATE_ADD('$fyearFrom',INTERVAL 364 DAY) WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


	public function checkNameYear($companyName, $fyearFrom) {
		$query = "SELECT COUNT(companyId) AS cnt FROM company WHERE companyName = '$companyName' AND fyearTo = '$fyearFrom'";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function isCompanyValid($companyId) {
		$query = "SELECT COUNT(companyId) AS cnt FROM company WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getCompanyFYearFrom($companyId) {
		$query = "SELECT fyearFrom FROM company WHERE companyId = $companyId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


}//end of class

// $History: CompanyManager.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:27p
//Created in $/LeapCC/Model/Accounts
//file added
//




?>