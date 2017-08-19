<?php
//-------------------------------------------------------
// Purpose: To delete event detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AddEvent');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['eventId']) || trim($REQUEST_DATA['eventId']) == '') {
        $errorMessage = 'Invalid event';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CalendarManager.inc.php");
        $calendarManager =  CalendarManager::getInstance();
        //no dependency for event table
         if($calendarManager->deleteEvent($REQUEST_DATA['eventId']) ) { 
                echo DELETE;
          }
         else {
                echo DEPENDENCY_CONSTRAINT;
         }
   
        
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Library/Calendar
//access permission updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Calendar
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:24p
//Updated in $/Leap/Source/Library/Calendar
//Added access rules
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Calendar
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:25p
//Updated in $/Leap/Source/Library/Calendar
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Calendar
//Created Calendar(event) module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:34p
//Created in $/Leap/Source/Library/Calendar
//Initial Checkin
?>

