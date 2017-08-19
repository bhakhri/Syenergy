<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DESIGNATION LIST
//
//
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DesignationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['designationId'] ) != '') {
    require_once(MODEL_PATH . "/DesignationManager.inc.php");
    $foundArray = DesignationManager::getInstance()->getDesignation(' WHERE designationId="'.$REQUEST_DATA['designationId'].'"');
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 0; //no record found
		}
}

// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Designation
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:33p
//Updated in $/Leap/Source/Library/Designation
//define access values
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:16p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/20/08    Time: 2:32p
//Updated in $/Leap/Source/Library/Designation
//modified for error message
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:24p
//Updated in $/Leap/Source/Library/Designation
?>