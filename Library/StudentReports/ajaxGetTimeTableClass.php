<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AdminTasksManager.inc.php");  

define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    
    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses(' AND ttl.timeTableLabelId="'.$REQUEST_DATA['timeTableLabelId'].'"',"className");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}

// $History: ajaxGetTimeTableClass.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/29/10    Time: 4:18p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 12:01p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//



?>