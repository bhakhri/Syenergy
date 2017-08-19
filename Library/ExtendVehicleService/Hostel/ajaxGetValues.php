<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['hostelId'] ) != '') {
    require_once(MODEL_PATH . "/HostelManager.inc.php");
    $foundArray = HostelManager::getInstance()->getHostel(' WHERE hostelId="'.$REQUEST_DATA['hostelId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 'Hostel does not exist.';
    }
}
// $History: ajaxGetValues.php $ 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:10a
//Created in $/LeapCC/Library/Hostel
//ajax files include add, delete, edit & list functions made by Jaineesh
//& modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//add define access in module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:29p
//Updated in $/Leap/Source/Library/Hostel
//modification in indentation
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:30p
//Created in $/Leap/Source/Library/Hostel
//ajax functions for add, edit, delete and search
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:50p
//Updated in $/Leap/Source/Library/States
//Added comments header and history tag
?>