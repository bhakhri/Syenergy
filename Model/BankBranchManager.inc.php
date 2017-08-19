<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "BankBranch" Module
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

class BankBranchManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BankBranch" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    
    private function __construct() {
    }
    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BankBranch" CLASS
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
// THIS FUNCTION IS USED FOR ADDING A Bank Branch
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    public function addBankBranch() {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('bank_branch', array('bankId', 'branchName','branchAbbr', 'accountType', 'accountNumber', 'operator'), array($REQUEST_DATA['bankId'], $REQUEST_DATA['branchName'], $REQUEST_DATA['branchAbbr'], $REQUEST_DATA['accountType'], $REQUEST_DATA['accountNumber'], $REQUEST_DATA['operator']));
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Bank Branch
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    /*public function editBankBranch($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('bank_branch', array('bankId', 'branchName', 'branchAbbr', 'accountType', 'accountNumber', 'operator'), array($REQUEST_DATA['bankId'], $REQUEST_DATA['branchName'], $REQUEST_DATA['branchAbbr'], $REQUEST_DATA['accountType'], $REQUEST_DATA['accountNumber'], $REQUEST_DATA['operator']), "bankBranchId='$id'" );
    } */  
    
      public function editBankBranch1($fieldName,$condition) {
        global $sessionHandler; 
        
        $query = "UPDATE bank_branch SET ".$fieldName." WHERE ".$condition;
        
        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");                                               }   
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Branch Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    
    public function getBankBranchName($conditions='') {
        $query = "SELECT bankId, branchName,branchAbbr, accountType, accountNumber, operator 
        FROM bank_branch 
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
     public function getBank($id) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT bankId,bankName,bankAbbr FROM bank
        WHERE bankId=$id
        ORDER BY bankName";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }    
   
    
    
   
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Branch Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    public function getBankBranchAbbr($conditions='') {
        $query = "SELECT bankBranchId, branchAbbr 
        FROM bank_branch  
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Branch Account Number
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    
    public function getBankBranchAccountNumber($conditions='') {
        $query = "SELECT bankBranchId, accountNumber 
        FROM bank_branch  
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Bank Branch" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteBankBranch($bankBranchId) {
     
        $query = "DELETE 
        FROM bank_branch 
        WHERE bankBranchId=$bankBranchId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Branch LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getBankBranch($conditions='') {
     
        $query = "SELECT bankId, bankBranchId, branchName, branchAbbr, accountType, accountNumber, operator  
        FROM bank_branch $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank Branch LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function getBankBranchList($conditions='',$orderBy=' a.branchName') {
        $query = "    SELECT 
                            a.bankId, 
                            a.bankBranchId, 
                            a.branchName, 
                            a.branchAbbr, 
                            a.accountType, 
                            a.accountNumber, 
                            a.operator,
                            CONCAT(b.bankName,' (',b.bankAbbr,')') AS bankName   
                    FROM    bank_branch a, 
                            bank b 
                    WHERE    a.bankId = b.bankId 
                            $conditions 
                            ORDER BY $orderBy";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank Branch" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    public function getTotalBankBranch($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM bank_branch a, bank b WHERE a.bankId = b.bankId ";
        if ($conditions != '') {
            $query .= " $conditions ";
        }
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank Branch" TABLE
//
// Author :Jaineesh 
// Created on : 20-Aug-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    public function checkInBankBranch($bankBranchId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM fee_receipt
        WHERE favouringBankBranchId=$bankBranchId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>