<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE class LIST
// Author : Dipanjan Bhattacharjee
// Created on : (14.04.2009 )
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
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses(' AND ttl.timeTableLabelId="'.$REQUEST_DATA['timeTableLabelId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetClasses.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/09/09    Time: 13:24
//Updated in $/LeapCC/Library/AdminTasks
//Corrected define code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Library/AdminTasks
//Created Attendance Delete Module
?>