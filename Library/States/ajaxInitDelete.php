<?php
//-------------------------------------------------------
// Purpose: To delete state detail
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
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['stateId']) || trim($REQUEST_DATA['stateId']) == '') {
        $errorMessage = 'Invalid State';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StatesManager.inc.php");
        $stateManager =  StatesManager::getInstance();
        $recordArray = $stateManager->checkInCity($REQUEST_DATA['stateId']);
        if($recordArray[0]['found']==0) {
            if($stateManager->deleteState($REQUEST_DATA['stateId']) ) {
                echo DELETE;
            }
            else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
    
// $History: ajaxInitDelete.php $    
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
//User: Pushpender   Date: 8/27/08    Time: 3:45p
//Updated in $/Leap/Source/Library/States
//optimized code and  removed trailing spaces
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:11p
//Updated in $/Leap/Source/Library/States
//added DEPENDENCY_CONSTRAINT in else condition of deleteState if
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>