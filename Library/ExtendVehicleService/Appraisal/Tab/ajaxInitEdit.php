<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','AppraisalTab');
define('ACCESS','edit');

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    $errorMessage ='';
    
    if(!isset($REQUEST_DATA['tabName']) || trim($REQUEST_DATA['tabName']) == '') {
        $errorMessage .=  ENTER_APPRAISAL_TAB_NAME."\n"; 
    }
    if (!isset($REQUEST_DATA['tabProofText']) || trim($REQUEST_DATA['tabProofText']) == '') {
        $errorMessage .=  "Enter proof text\n"; 
    }
    
    if(trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Appraisal/TabManager.inc.php");
        $foundArray = TabManager::getInstance()->getTab(' WHERE  ( UCASE(appraisalTabName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['tabName']))).'" ) AND appraisalTabId!='.trim($REQUEST_DATA['tabId']));
        if(trim($foundArray[0]['appraisalTabName'])=='') {  //DUPLICATE CHECK
            $returnStatus = TabManager::getInstance()->editTab(trim($REQUEST_DATA['tabId']));
            if($returnStatus === false) {
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

// $History: ajaxInitEdit.php $
?>