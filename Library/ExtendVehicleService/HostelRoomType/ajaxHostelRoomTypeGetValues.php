<?php
//-------------------------------------------------------
// Purpose: To get values of hostel room from the database
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomType');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['hostelRoomTypeId'] ) != '') {
    require_once(MODEL_PATH . "/HostelRoomTypeManager.inc.php");
    $foundArray = HostelRoomTypeManager::getInstance()->getHostelRoomType(' WHERE hostelRoomTypeId="'.$REQUEST_DATA['hostelRoomTypeId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxHostelRoomTypeGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:48a
//Created in $/LeapCC/Library/HostelRoomType
//

?>