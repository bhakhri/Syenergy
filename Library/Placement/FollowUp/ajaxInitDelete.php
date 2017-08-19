<?php
//-------------------------------------------------------
// Purpose: To delete UNIBERSITY detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (!isset($REQUEST_DATA['followUpId']) || trim($REQUEST_DATA['followUpId']) == '') {
        $errorMessage = FOLLOWUP_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");
        $followUpManager =  FollowUpManager::getInstance();
        
        // delete record
        if($followUpManager->deleteFollowUp(trim($REQUEST_DATA['followUpId']))) {
            echo DELETE;
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
?>