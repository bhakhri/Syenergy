<?php
//-------------------------------------------------------
// Purpose: To delete thoughts detail
//
// Author : Parveen Sharma
// Created on : (18.03.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ThoughtsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['thoughtId']) || trim($REQUEST_DATA['thoughtId']) == '') {
        $errorMessage = THOUGHTS_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ThoughtsManager.inc.php");
        $thoughtsManager =  ThoughtsManager::getInstance();
        if($thoughtsManager->deleteThoughts($REQUEST_DATA['thoughtId']) ) {
            echo DELETE;
        } 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
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

