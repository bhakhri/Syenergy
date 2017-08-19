<?php
////  This File gets  record from the session Form Table
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SessionMaster');  
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['sessionId'] ) != '') {
    require_once(MODEL_PATH . "/SessionsManager.inc.php");
    $foundArray = SessionsManager::getInstance()->getSession(' WHERE sessionId='.$REQUEST_DATA['sessionId']);
	
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
//Created in $/LeapCC/Library/Session
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/15/08   Time: 11:39a
//Updated in $/Leap/Source/Library/Session
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/15/08   Time: 10:50a
//Updated in $/Leap/Source/Library/Session
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:51a
//Updated in $/Leap/Source/Library/Session
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/21/08    Time: 10:20a
//Updated in $/Leap/Source/Library/Session
//Added last line comment for VSS

?>