<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId']) !='' and trim($REQUEST_DATA['classId'])!='') {
    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $foundArray = SendMessageManager::getInstance()->getGroups(' AND ttl.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'" AND c.classId="'.trim($REQUEST_DATA['classId']).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetGroups.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:50
//Created in $/LeapCC/Library/AdminMessage
//Modified search filter in "Send student performance message to parents"
//module
?>