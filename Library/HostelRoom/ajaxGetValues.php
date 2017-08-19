<?php
//-------------------------------------------------------
// Purpose: To get values of hostel room from the database
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['hostelRoomId'] ) != '') {
    require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
    $foundArray = HostelRoomManager::getInstance()->getHostelRoom(' AND hostelRoomId="'.$REQUEST_DATA['hostelRoomId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Library/HostelRoom
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HostelRoom
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:46p
//Updated in $/Leap/Source/Library/HostelRoom
//add define access in module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:08p
//Updated in $/Leap/Source/Library/HostelRoom
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/22/08    Time: 11:05a
//Updated in $/Leap/Source/Library/HostelRoom
//modified in messages
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
