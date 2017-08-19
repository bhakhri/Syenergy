<?php
////  This File gets  record from the config Form Table
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['configId'] ) != '') {
    require_once(MODEL_PATH . "/ConfigManager.inc.php");
    $foundArray = ConfigManager::getInstance()->getConfig(' WHERE configId='.$REQUEST_DATA['configId']);
	
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
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Config
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/05/08    Time: 5:41p
//Created in $/Leap/Source/Library/Config
//file added for config masters
//
?>