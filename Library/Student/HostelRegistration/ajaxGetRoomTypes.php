<?php
//-------------------------------------------------------
// Purpose: To get room type detail
// Author : Dipanjan Bhattacharjee
// Created on : (23.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    if (trim($errorMessage) == '') {
       
require_once(MODEL_PATH . "/Student/HostelRegistrationManager.inc.php");
    $hostelRegistrationManager = HostelRegistrationManager::getInstance();  

        $foundArray = $hostelRegistrationManager->getRoomTypeData();
        if(is_array($foundArray) && count($foundArray)>0 ) {  
            echo json_encode($foundArray);
            }
        else {
         echo 0;
         }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxGetRoomTypes.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:34
//Created in $/LeapCC/Library/Room
//Added files for "Room Allocation Master"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/07/09   Time: 15:20
//Updated in $/Leap/Source/Library/Room
//changed access array
//
//*****************  Version 1  *****************
//User: Administrator Date: 14/07/09   Time: 11:59
//Created in $/Leap/Source/Library/Room
//Added "Room Type" and "Room Type Facilities" in "Room Allocation
//Master"
?>