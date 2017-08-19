<?php
//-------------------------------------------------------
// Purpose: To delete city detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalTab');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['tabId']) || trim($REQUEST_DATA['tabId']) == '') {
        $errorMessage = APPRAISAL_TAB_NOT_EXIST;
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/TabManager.inc.php");
        $tabManager =  TabManager::getInstance();
        $recordArray = $tabManager->checkInAppraisalData(trim($REQUEST_DATA['tabId']));
        
        if($recordArray[0]['found']==0) {
            if($tabManager->deleteTab(trim($REQUEST_DATA['tabId']))) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
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