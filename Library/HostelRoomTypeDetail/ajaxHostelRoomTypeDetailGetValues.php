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
define('MODULE','HostelRoomTypeDetail');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['roomTypeInfoId'] ) != '') {
    require_once(MODEL_PATH . "/HostelRoomTypeDetailManager.inc.php");
    $foundArray = HostelRoomTypeDetailManager::getInstance()->getHostelRoomTypeDetail(' AND hrtd.roomTypeInfoId="'.$REQUEST_DATA['roomTypeInfoId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0;
		}
}
// $History: ajaxHostelRoomTypeDetailGetValues.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//

?>