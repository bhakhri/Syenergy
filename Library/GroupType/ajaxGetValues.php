<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE GROUP TYPE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['groupTypeId'] ) != '') {
    require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
    $foundArray = GroupTypeManager::getInstance()->getGroupType(' WHERE groupTypeId="'.$REQUEST_DATA['groupTypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 1:09p
//Updated in $/LeapCC/Library/GroupType
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/GroupType
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 2:58p
//Updated in $/Leap/Source/Library/GroupType
//modification in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:25p
//Updated in $/Leap/Source/Library/GroupType
//modified in messages
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:37p
//Updated in $/Leap/Source/Library/GroupType
?>