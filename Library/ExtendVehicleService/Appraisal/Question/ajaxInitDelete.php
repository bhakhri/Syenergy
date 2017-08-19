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
define('MODULE','AppraisalQuestionMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if(trim($REQUEST_DATA['appraisalId'])==''){
        echo APPRAISAL_QUESTION_NOT_EXIST;
        die; 
    }

    require_once(MODEL_PATH . "/Appraisal/QuestionManager.inc.php");
    $questionManager =  QuestionManager::getInstance();

    //check for usage in data table
    $foundArray=$questionManager->checkInAppraisalData(trim($REQUEST_DATA['appraisalId']));
    if($foundArray[0]['found']!=0){
        echo DEPENDENCY_CONSTRAINT;
        die;
    }
    
    if($questionManager->deleteQuestion(trim($REQUEST_DATA['appraisalId']))) {
        echo DELETE;
    }
   else {
        echo DEPENDENCY_CONSTRAINT;
    }
   
// $History: ajaxInitDelete.php $    
?>