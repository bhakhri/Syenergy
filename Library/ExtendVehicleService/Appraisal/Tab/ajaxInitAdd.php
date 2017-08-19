<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalTab');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     
     if (!isset($REQUEST_DATA['tabName']) || trim($REQUEST_DATA['tabName']) == '') {
        $errorMessage .=  ENTER_APPRAISAL_TAB_NAME."\n"; 
     }
     
     if (!isset($REQUEST_DATA['tabProofText']) || trim($REQUEST_DATA['tabProofText']) == '') {
        $errorMessage .=  "Enter proof text\n"; 
     }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/TabManager.inc.php");
        $foundArray = TabManager::getInstance()->getTab(' WHERE ( UCASE(appraisalTabName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['tabName']))).'" )');
        if(trim($foundArray[0]['appraisalTabName'])=='') {  //DUPLICATE CHECK
            $returnStatus = TabManager::getInstance()->addTab();
            if($returnStatus == false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo APPRAISAL_TAB_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>