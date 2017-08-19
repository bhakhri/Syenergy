<?php
//-------------------------------------------------------
// Purpose: To get values of group from the database
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['groupId'] ) != '') {
    require_once(MODEL_PATH . "/GroupManager.inc.php");
    $foundArray = GroupManager::getInstance()->getGroup(' WHERE groupId="'.$REQUEST_DATA['groupId'].'"');
    //$classArr   = GroupManager::getInstance()->editClass($foundArray[0]['classId']);
	//print_r($foundArray);
    $valuesArr  = array_merge($foundArray[0]);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($valuesArr);
    }
    else {
        echo 0; // no record found
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Library/Group
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:56a
//Updated in $/LeapCC/Library/Group
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 1:10p
//Updated in $/Leap/Source/Library/Group
//modified in indentation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:06p
//Updated in $/Leap/Source/Library/Group
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 11:06a
//Updated in $/Leap/Source/Library/Group
//modified in functions for edit, add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search
?>