<?php
//-------------------------------------------------------
// Purpose: To get values of fee cycle from the database
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleMaster');
define('ACCESS','view');       
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['feeCycleId'] ) != '') {
    require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
    $foundArray = FeeCycleManager::getInstance()->getFeeCycle(' WHERE feeCycleId="'.$REQUEST_DATA['feeCycleId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 'Cycle Name does not exist.';
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycle
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycle
//Define Module, Access  Added
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:34p
//Created in $/Leap/Source/Library/HostelRoom
//ajax functions of add, delete, edit & search
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:50p
//Updated in $/Leap/Source/Library/States
//Added comments header and history tag
?>

