<?php
//-------------------------------------------------------
// Purpose: To delete Notice detail
//
// Author : Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','EventMaster');       
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 
    if (!isset($REQUEST_DATA['userWishEventId']) || trim($REQUEST_DATA['userWishEventId']) == '') {
        $errorMessage = 'Invalid Event';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EventManager.inc.php");
        $eventManager =  EventManager::getInstance();
        
        // Checks dependency constraint
        $ret=$eventManager->deleteEvent($REQUEST_DATA['userWishEventId']);

        if($ret===true ) {
              echo DELETE;
         }
        else{
            echo "Event Could not be Deleted ";
        }
       
    }
   else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Notice
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/27/08   Time: 6:23p
//Updated in $/Leap/Source/Library/Notice
//Added code for restriction of deletion of old notices
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Notice
//Define Module, Access  Added
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Notice
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:42a
//Updated in $/Leap/Source/Library/Notice
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/07/08    Time: 4:51p
//Created in $/Leap/Source/Library/Notice
//Added a new module   "Notice" files

?>

