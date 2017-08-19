<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A UNIVERSITY
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php"); 
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

    $fileObj = FileUploadManager::getInstance('universityLogo');
    if($fileObj->upload(IMG_PATH.'/University/')) {
        echo $fileObj->message;                
    }
    else {
        logError($fileObj->message); 
    }

// $History: fileUpload.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/University
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Templates/University
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:33a
//Created in $/Leap/Source/Templates/University
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/16/08    Time: 11:40a
//Created in $/Leap/Source/Templates/University
//initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/University
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/University
//Initial Checkin
?>