<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE group list
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $foundArray = SendMessageManager::getInstance()->getGroup(' AND universityId="'.$REQUEST_DATA['universityId'].'" AND degreeId="'.$REQUEST_DATA['degreeId'].'" AND branchId="'.$REQUEST_DATA['branchId'].'" AND batchId="'.$REQUEST_DATA['batchId'].'" AND studyPeriodId="'.$REQUEST_DATA['studyPeriodId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else{
        echo 0;
    }

// $History: ajaxGetGroups.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SendMessage
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Library/SendMessage
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:49p
//Updated in $/Leap/Source/Library/SendMessage
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/07/08    Time: 2:52p
//Created in $/Leap/Source/Library/SendMessage
//Initial Checkin
?>