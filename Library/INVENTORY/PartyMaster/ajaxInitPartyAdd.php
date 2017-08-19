<?php
//-------------------------------------------------------
// Purpose: To add items
//
// Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','PartyMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    if ($errorMessage == '' && !isset($REQUEST_DATA['partyName']) || trim($REQUEST_DATA['partyName']) == '') {
        $errorMessage .= ENTER_PARTY_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['partyCode']) || trim($REQUEST_DATA['partyCode']) == '')) {
        $errorMessage .= ENTER_PARTY_CODE. "\n";
    }
    
	if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
		$partyManager = PartyManager::getInstance();
		$foundArray=$partyManager->getParty(' WHERE (LCASE(partyName) ="'.add_slashes(trim(strtolower($REQUEST_DATA['partyName']))).'" OR LCASE(partyCode) ="'.add_slashes(trim(strtolower($REQUEST_DATA['partyCode']))).'")');
       //we will calculate new item code in server side also
       if(SystemDatabaseManager::getInstance()->startTransaction()) { 
		 if(trim($foundArray[0]['partyCode'])=='') {  //DUPLICATE CHECK  
			$returnStatus = $partyManager->addParty();
			if($returnStatus === false) {
				echo FAILURE;
				die;
			}
			//****AS WE MOVED THE MAPPING PORTION TO THE NEW MODULE****
			if(SystemDatabaseManager::getInstance()->commitTransaction()){
				echo SUCCESS;
				die;
			}
			else {
				echo FAILURE;
				die;
			}
		  }
			else {
				 if(strtolower($foundArray[0]['partyName'])==trim(strtolower($REQUEST_DATA['partyName']))) {
					echo PARTY_NAME_ALREADY_EXIST;
					die;
				}
				else if(strtolower($foundArray[0]['partyCode'])==trim(strtolower($REQUEST_DATA['partyCode']))) {
					echo PARTY_CODE_ALREADY_EXIST;
					die;
				}
			}
	   }
	   else{
		   echo FAILURE;
		   die;
	   }
 	 }
    else {
        echo $errorMessage;
    }
// $History: $    
//
?>