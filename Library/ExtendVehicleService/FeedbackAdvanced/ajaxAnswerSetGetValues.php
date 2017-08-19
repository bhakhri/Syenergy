<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE COMPLAINT CATEGORY LIST
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
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['answerSetId'] ) != '') {
    require_once(MODEL_PATH . "/FeedbackAnswerSetManager.inc.php");
    $foundArray = FeedbackAnswerSetManager::getInstance()->getAnswerSet(' WHERE AnswerSetId="'.$REQUEST_DATA['answerSetId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>