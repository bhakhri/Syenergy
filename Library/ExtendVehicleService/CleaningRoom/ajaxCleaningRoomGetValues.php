<?php
//-------------------------------------------------------
// Purpose: To get values of Cleaning Room from the database
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CleaningMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['cleanId'] ) != '') {
    require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
    $foundArray = CleaningRoomManager::getInstance()->getCleaningRoom(' AND hcr.cleanId="'.$REQUEST_DATA['cleanId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxCleaningRoomGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Library/CleaningRoom
//fixed bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:27p
//Created in $/LeapCC/Library/CleaningRoom
//all ajax files for cleaning room
//
//
?>