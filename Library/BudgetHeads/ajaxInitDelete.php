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
define('MODULE','BudgetHeads');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['budgetHeadId']) || trim($REQUEST_DATA['budgetHeadId']) == '') {
        $errorMessage = BUDGET_HEAD_NOT_EXIST;
    }
    if(trim($errorMessage) == '') {
       require_once(MODEL_PATH . "/BudgetHeadsManager.inc.php");
       $budgetManager =  BudgetHeadsManager::getInstance();
       $recordArray = $budgetManager->checkInGuestHouse(trim($REQUEST_DATA['budgetHeadId']));
       if($recordArray[0]['found']==0) {
            if($budgetManager->deleteBudgetHeads(trim($REQUEST_DATA['budgetHeadId']))) {
                echo DELETE;
                die;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
       }
       else {
            echo DEPENDENCY_CONSTRAINT;
            die;
       }
    }
    else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $    
?>