<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEM CATEGORY
//
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PartyMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       if ($errorMessage == '' && (!isset($REQUEST_DATA['partyName']) || trim($REQUEST_DATA['partyName']) == '')) {
        $errorMessage .= ENTER_PARTY_NAME."\n";    
    }
      if ($errorMessage == '' && (!isset($REQUEST_DATA['partyCode']) || trim($REQUEST_DATA['partyCode']) == '')) {
        $errorMessage .= ENTER_PARTY_CODE."\n";    
    }
	  if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
		$partyManager = PartyManager::getInstance();
        $foundArray = $partyManager->getParty(' WHERE (LCASE(partyName) = "'.add_slashes(trim(strtolower($REQUEST_DATA['partyName']))).'" OR LCASE(partyCode)="'.add_slashes(strtolower($REQUEST_DATA['partyCode'])).'") AND partyId!='.$REQUEST_DATA['partyId']);
                 if(trim($foundArray[0]['partyCode'])=='') {  //DUPLICATE CHECK  
                   $returnStatus = $partyManager->editParty($REQUEST_DATA['partyId']);
                        if($returnStatus === false) {
                            echo FAILURE;
                        }
                        else {
                            echo SUCCESS;
                        }
                    }
                    else {
                       if(trim(strtolower($foundArray[0]['partyCode']))==trim(strtolower($REQUEST_DATA['partyCode']))){ 
                           echo PARTY_CODE_ALREADY_EXIST;
                         die;
                       }
                       elseif(trim(strtolower($foundArray[0]['partyName'])) == trim(strtolower($REQUEST_DATA['partyName']))) { 
                           echo PARTY_NAME_ALREADY_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }
?>