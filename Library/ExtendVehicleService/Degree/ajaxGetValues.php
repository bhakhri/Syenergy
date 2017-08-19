<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DEGREE LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DegreeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['degreeId'] ) != '') {
    require_once(MODEL_PATH . "/DegreeManager.inc.php");
    $foundArray = DegreeManager::getInstance()->getDegree(' WHERE degreeId="'.$REQUEST_DATA['degreeId'].'"');
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
//Created in $/LeapCC/Library/Degree
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:15p
//Updated in $/Leap/Source/Library/Degree
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Degree
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Library/Degree
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:05a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
?>