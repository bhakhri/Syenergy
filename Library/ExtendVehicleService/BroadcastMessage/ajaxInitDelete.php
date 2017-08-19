<?php
//-------------------------------------------------------
// Purpose: To delete city detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BroadcastMessage');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['msgId']) || trim($REQUEST_DATA['msgId']) == '') {
        $errorMessage = MESSAGE_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BroadcastMessageManager.inc.php");
        $messageManager =  BroadcastMessageManager::getInstance();
        if($messageManager->deleteMessage($REQUEST_DATA['msgId']) ) {
             echo DELETE;
        }
        else {
                echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
?>