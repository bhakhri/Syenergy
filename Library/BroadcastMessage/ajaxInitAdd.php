<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BroadcastMessage');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['msgText']) || trim($REQUEST_DATA['msgText']) == '') {
        $errorMessage .=  ENTER_MESSAGE_TEXT."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['msgText']) || trim($REQUEST_DATA['msgText']) == '')) {
        $errorMessage .= ENTER_MESSAGE_DATE."\n";  
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BroadcastMessageManager.inc.php");
        $foundArray = BroadcastMessageManager::getInstance()->getMessage(' WHERE messageDate="'.trim($REQUEST_DATA['msgDate']).'"');
        if(trim($foundArray[0]['messageText'])=='') {  //DUPLICATE CHECK
            $returnStatus = BroadcastMessageManager::getInstance()->addMessage();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
           die('Message found for this day');
        }
    }
    else {
        echo $errorMessage;
    }
?>