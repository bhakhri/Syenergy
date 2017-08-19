<?php
//
//  This File calls Edit Function used in adding Bank Branch Records
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/BankBranchManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

$commonQueryManager = CommonQueryManager::getInstance();    
$branchManager = BankBranchManager::getInstance();

define('MODULE','BankMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 

   $errorMessage ='';
   
   $bankId = $REQUEST_DATA['bankId']; 
   $bankBranchId = trim($REQUEST_DATA['bankBranchId']);
   $branchName = $REQUEST_DATA['branchName'];
   $branchAbbr = $REQUEST_DATA['branchAbbr'];
   $accountType = $REQUEST_DATA['accountType']; 
   $accountNumber = $REQUEST_DATA['accountNumber'];
   $operator = $REQUEST_DATA['operator'];
   $status = $REQUEST_DATA['status']; 
   $tbankBranchId = $REQUEST_DATA['tbankBranchId']; 
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) { 
       for($i=0; $i<count($tbankBranchId); $i++) {    
          if($status[$i]=='Y') { 
            $strValue = " branchName = '".$branchName[$i]."', branchAbbr = '".$branchAbbr[$i]."', 
                          accountType = '".$accountType[$i]."',accountNumber = '".$accountNumber[$i]."',operator = '".$operator[$i]."'";
            $condition = " bankBranchId = ".$tbankBranchId[$i];
            /*$returnStatus = $branchManager->editBankBranch1($strValue,$condition);
              if($returnStatus===false) {
                 echo FAILURE;
                 die; 
              }*/
              
              $foundArray = $branchManager->getBankBranch(' WHERE UCASE(branchName)="'.add_slashes(trim(strtoupper($branchName[$i]))).'" AND bankBranchId!='.$tbankBranchId[$i]);

        if(trim($foundArray[0]['branchName'])=='') {  //DUPLICATE Bank NAME CHECK
        
            $foundArray2 = $branchManager->getBankBranchAbbr(' WHERE UCASE(branchAbbr) = "'.add_slashes(trim(strtoupper($branchAbbr[$i]))).'" AND bankBranchId!='.$tbankBranchId[$i]);
            if(trim($foundArray2[0]['branchAbbr'])=='') {  //DUPLICATE Bank YEAR CHECK
                $foundArray3 = $branchManager->getBankBranchAccountNumber(' WHERE UCASE(accountNumber) = "'.add_slashes(strtoupper($accountNumber[$i])).'" AND bankBranchId!='.$tbankBranchId[$i]);
                if (trim($foundArray3[0]['accountNumber'] == '')) {
                    $returnStatus = $branchManager->editBankBranch1($strValue,$condition);
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                        die; 
                    }
                    
                }
                else {
                    echo ACCOUNT_NUMBER_ALREADY_EXIST;
                    die;
                }
            }
            else {
                echo BRANCH_ABBR_ALREADY_EXIST;
                die;
            }
        }
        else {
            echo BRANCH_NAME_ALREADY_EXIST;
            die;
        }
              
          }
       }
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         echo SUCCESS;
       }
       else {
         echo FAILURE;
       }  
   }  
  
  

   
//$History: ajaxInitBankBranchEdit.php $	
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/Bank
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 11:15a
//Created in $/LeapCC/Library/Bank
//added file for bank-branch merged module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Library/BankBranch
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Library/BankBranch
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/26/08    Time: 11:20a
//Updated in $/Leap/Source/Library/BankBranch
//done the common messaging
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master


?>