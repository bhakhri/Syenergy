<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Thoughts LIST
//
// Author : Parveen Sharma
// Created on : (19.3.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ThoughtsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['thoughtId'] ) != '') {
    require_once(MODEL_PATH . "/ThoughtsManager.inc.php");
    $foundArray = ThoughtsManager::getInstance()->getThoughts(' WHERE thoughtId="'.$REQUEST_DATA['thoughtId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
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