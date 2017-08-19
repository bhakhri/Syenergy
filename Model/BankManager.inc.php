<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Bank" Module
//
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BankManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BankManager" CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	private function __construct() {
	}


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BankManager" CLASS
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Bank
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function addBank() {
		global $REQUEST_DATA;
		return SystemDatabaseManager::getInstance()->runAutoInsert('bank', array('bankName','bankAbbr','bankAddress'), 
        array($REQUEST_DATA['bankName'],$REQUEST_DATA['bankAbbr'],$REQUEST_DATA['bankAddress']));
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Bank
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function editBank($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bank', array('bankName','bankAbbr','bankAddress'), 
            array($REQUEST_DATA['bankName'],$REQUEST_DATA['bankAbbr'],$REQUEST_DATA['bankAddress']), "bankId=$id" );
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Name
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getBankName($conditions='') {
        $query = "SELECT bankId, bankName,bankAbbr , bankAddress
        FROM bank
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Name based on id

//
// Author :Gurkeerat Sidhu
// Created on : 08-Oct-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
     public function getBankName1($id) {
        $query = "SELECT bankId, bankName,bankAbbr   , bankAddress 
        FROM bank
        WHERE bankId=$id";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Name
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getBankAbbr($conditions='') {
        $query = "SELECT bankId, bankAbbr   , bankAddress 
        FROM bank
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Bank" RECORD
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteBank($bankId) {

        $query = "DELETE
        FROM bank
        WHERE bankId=$bankId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank LIST
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getBank($conditions='') {

        $query = "SELECT bankId, bankName, bankAbbr  , bankAddress 
        FROM bank
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank LIST
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getBankList($conditions='', $limit = '', $orderBy=' bankName') {
        $query = "SELECT bankId, bankName, bankAbbr  , bankAddress 
		FROM bank $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank" TABLE
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getTotalBank($conditions='') {

        $query = "SELECT COUNT(*) AS totalRecords
        FROM bank ";
		if ($conditions != '') {
			$query .= " $conditions ";
		}

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Fee Receipt" TABLE
//
// Author :Jaineesh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function checkInBank($bankId) {

        $query = "SELECT COUNT(*) AS found
        FROM fee_receipt
        WHERE issuingBankId=$bankId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank Branch" TABLE
//
// Author :Jaineesh
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function checkInBankBranch($bankId) {

        $query = "SELECT COUNT(*) AS found
        FROM bank_branch
        WHERE bankId=$bankId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 //-------------------------------------------------------------------------------
// THIS FUNCTION IS USED To Check if bank is Mapped with fee Receipt
// Author :Nishu Bindal
// Created on : 14-May-2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function checkInFeeReceiptMaster($bankId) {

        $query = "SELECT COUNT(*) AS cnt
        		FROM `fee_receipt_master`
        		WHERE bankId=$bankId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
 //-------------------------------------------------------------------------------
// THIS FUNCTION IS USED To Check if bank is Mapped with fee Receipt Details
// Author :Nishu Bindal
// Created on : 14-May-2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function checkInFeeReceiptDetails($bankId) {
        $query = "SELECT COUNT(*) AS cnt
        		FROM `fee_receipt_details`
        		WHERE bankId=$bankId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    




}
?>
