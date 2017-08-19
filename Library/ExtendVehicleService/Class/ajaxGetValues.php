<?php
//-------------------------------------------------------
// Purpose: To get values of class from the database
//
// Author : Rajeev Aggarwal
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/ClassManager.inc.php");
    $foundArray = ClassManager::getInstance()->getClass(' WHERE classId="'.$REQUEST_DATA['classId'].'"');
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
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Class
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:59a
//Created in $/Leap/Source/Library/Class
//intial checkin
?>

