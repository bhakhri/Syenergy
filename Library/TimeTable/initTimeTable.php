<?php
//It contains the time table
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   require_once(MODEL_PATH . "/TimeTableManager.inc.php");
   define('MODULE','COMMON');
   define('ACCESS','view');
        
   $timeTableManager = TimeTableManager::getInstance();
    
   $teacherRecordArray = $timeTableManager->getTeacherTimeTable();   

//$History: initTimeTable.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 6:32p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
 
?>
