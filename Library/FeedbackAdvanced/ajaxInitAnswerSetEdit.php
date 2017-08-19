<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AnswerSet');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       if ($errorMessage == '' && (!isset($REQUEST_DATA['answerSetName']) || trim($REQUEST_DATA['answerSetName']) == '')) {
        $errorMessage .= ENTER_ANSWERSET_NAME."\n";    
    }
     if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeedbackAnswerSetManager.inc.php");
        $foundArray = FeedbackAnswerSetManager::getInstance()->getAnswerSet(' WHERE UCASE(answerSetName)="'.add_slashes(strtoupper($REQUEST_DATA['answerSetName'])).'" AND answerSetId!='.$REQUEST_DATA['answerSetId']);
        if(trim($foundArray[0]['answerSetName'])=='') {  //DUPLICATE CHECK
            $returnStatus = FeedbackAnswerSetManager::getInstance()->editAnswerSet($REQUEST_DATA['answerSetId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

?>
