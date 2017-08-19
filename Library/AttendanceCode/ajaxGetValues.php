<?php 
//  This File checks  whether record exists in AttendanceCode Form Table
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 
    
if(trim($REQUEST_DATA['attendanceCodeId'] ) != '') {
    require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");
    $foundArray = AttendanceCodeManager::getInstance()->getAttendanceCode(' WHERE attendanceCodeId="'.$REQUEST_DATA['attendanceCodeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

//$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AttendanceCode
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 10:19a
//Updated in $/Leap/Source/Library/AttendanceCode
//Added Module, Access
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/30/08    Time: 4:05p
//Updated in $/Leap/Source/Library/AttendanceCode
//modified echo function to 0
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/17/08    Time: 4:14p
//Created in $/Leap/Source/Library/AttendanceCode
//added new files
?>
