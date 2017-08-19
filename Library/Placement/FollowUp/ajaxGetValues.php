<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE UNIVERSITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['followUpId'] ) != '') {
    require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");
    $foundArray = FollowUpManager::getInstance()->getFollowUps(' WHERE followUpId="'.trim($REQUEST_DATA['followUpId']).'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
       echo (json_encode($foundArray[0])); 
    }
    else {
        echo 0;
    }
    
}
// $History: ajaxGetValues.php $
?>