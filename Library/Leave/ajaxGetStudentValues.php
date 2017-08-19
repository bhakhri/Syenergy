<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['rollNo'] ) != '') {
    require_once(MODEL_PATH . "/LeaveManager.inc.php");
    $foundArray = LeaveManager::getInstance()->getLeave(' WHERE leaveTypeName="'.trim(add_slashes($REQUEST_DATA['leaveName'])).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetStudentValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Library/Leave
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:04
//Created in $/LeapCC/Library/Leave
//Created 'Leave' Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Library/Leave
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Library/Leave
//Created module 'Leave'
?>