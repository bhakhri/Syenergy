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
define('MODULE','AppraisalTitle');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['titleId']) || trim($REQUEST_DATA['titleId']) == '') {
        $errorMessage = APPRAISAL_TITLE_NOT_EXIST;
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/TitleManager.inc.php");
        
        $titleManager =  TitleManager::getInstance();
        $recordArray  =  $titleManager->checkInAppraisalData(trim($REQUEST_DATA['titleId']));
        
        if($recordArray[0]['found']==0) {
            if($titleManager->deleteTitle(trim($REQUEST_DATA['titleId']))) {
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