<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalTitle');
define('ACCESS','edit');

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    $errorMessage ='';
    
    if(!isset($REQUEST_DATA['titleName']) || trim($REQUEST_DATA['titleName']) == '') {
        $errorMessage .=  ENTER_APPRAISAL_TITLE_NAME."\n"; 
    }
    
    if(trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/TitleManager.inc.php");
        $foundArray = TitleManager::getInstance()->getTitle(' WHERE  ( UCASE(appraisalTitle)="'.add_slashes(strtoupper(trim($REQUEST_DATA['titleName']))).'" ) AND appraisalTitleId!='.trim($REQUEST_DATA['titleId']));
        if(trim($foundArray[0]['appraisalTitle'])=='') {  //DUPLICATE CHECK
            $returnStatus = TitleManager::getInstance()->editTitle(trim($REQUEST_DATA['titleId']));
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo APPRAISAL_TITLE_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
?>