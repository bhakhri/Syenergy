<?php 
//  This File checks  whether record exists in Periodicity Form Table
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodicityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['periodicityId'] ) != '') {
    require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
    $foundArray = PeriodicityManager::getInstance()->getPeriodicity(' WHERE periodicityId="'.$REQUEST_DATA['periodicityId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}

////$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Periodicity
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/05/08   Time: 5:16p
//Updated in $/Leap/Source/Library/Periodicity
//added module,access 
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/30/08    Time: 4:41p
//Updated in $/Leap/Source/Library/Periodicity
//modified echo function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/14/08    Time: 7:16p
//Updated in $/Leap/Source/Library/Periodicity
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Periodicity
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Periodicity
//NEw Files Added in Periodicity Folder

?>
