<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Thoughts
//
//
// Author : Parveen Sharma
// Created on : (18.3.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ThoughtsMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['thought']) || trim($REQUEST_DATA['thought']) == '') {
        $errorMessage .= ENTER_THOUGHTS."\n";   
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ThoughtsManager.inc.php");
        $foundArray = ThoughtsManager::getInstance()->getThoughts(' WHERE UCASE(thought)="'.add_slashes(strtoupper($REQUEST_DATA['thought'])).'"');
        if(trim($foundArray[0]['thought'])=='') {  //DUPLICATE CHECK
            $returnStatus = ThoughtsManager::getInstance()->addThoughts();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo THOUGHTS_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:09a
//Created in $/LeapCC/Library/Thoughts
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 6:31p
//Created in $/Leap/Source/Library/Thoughts
//file added
//
?>