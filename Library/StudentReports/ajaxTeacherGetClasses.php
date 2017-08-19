<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE classes in time table label Id
//
//
// Author : Parveen Sharma
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");         
                                           
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']);
     
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }

    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses(' AND ttl.timeTableLabelId="'.$timeTabelId.'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
    
// $History: ajaxTeacherGetClasses.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 5:42p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//


?>