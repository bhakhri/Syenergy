<?php
//-------------------------------------------------------
// Purpose: To delete item category detail
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PartyMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['partyId']) || trim($REQUEST_DATA['partyId']) == '') {
        $errorMessage = 'Invalid Party';
    }

    if (trim($errorMessage) == '') {
		require_once(INVENTORY_MODEL_PATH . "/PartyManager.inc.php");
		$partyManager = PartyManager::getInstance();
		if($partyManager->deleteParty($REQUEST_DATA['partyId'])) {
			echo DELETE;
		}
	}
    else {
        echo $errorMessage;
    }
?>