<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DEPARTMENT LIST
//
//
// Author : Jaineesh
// Created on : (20.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DepartmentMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['departmentId'] ) != '') {
    require_once(MODEL_PATH . "/DepartmentManager.inc.php");
    $foundArray = DepartmentManager::getInstance()->getDepartment(' WHERE departmentId="'.$REQUEST_DATA['departmentId'].'"');
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
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Department
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Department
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:48p
//Created in $/Leap/Source/Library/Department
//used for getting the data of department
//

?>