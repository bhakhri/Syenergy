<?php
//-------------------------------------------------------
// Purpose: To get values of state from the database
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StateMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['stateId'] ) != '') {
    require_once(MODEL_PATH . "/StatesManager.inc.php");
    $foundArray = StatesManager::getInstance()->getState(' WHERE stateId="'.$REQUEST_DATA['stateId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0; // no record found
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/10/09    Time: 5:38p
//Created in $/LeapCC/Library/States
//copy from sc with modifications
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/03/09    Time: 2:44p
//Updated in $/Leap/Source/Library/States
//fixed bug nos.1213,1219,1220,1221
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 8/27/08    Time: 3:44p
//Updated in $/Leap/Source/Library/States
//optimized code and  removed trailing spaces
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/27/08    Time: 12:25p
//Updated in $/Leap/Source/Library/States
//replaced 'State does not exist' with  0 (false)
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:50p
//Updated in $/Leap/Source/Library/States
//Added comments header and history tag
?>