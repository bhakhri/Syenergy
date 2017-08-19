<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE TimeTable Label LIST
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['leaveSetId'] ) != '') {
    require_once(MODEL_PATH . "/LeaveSetManager.inc.php");
    $foundArray = LeaveSetManager::getInstance()->getLeaveSet(' WHERE leaveSetId="'.$REQUEST_DATA['leaveSetId'].'"');
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
//Created in $/LeapCC/Library/TimeTableLabel
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:12a
//Updated in $/Leap/Source/Library/TimeTableLabel
//Added access rules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:34p
//Created in $/Leap/Source/Library/TimeTableLabel
//Created TimeTable Labels
?>