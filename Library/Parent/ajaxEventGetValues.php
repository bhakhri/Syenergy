
<?php 

////  Get the data from event table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn(); 
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['eventId'] ) != '') {
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $foundArray = StudentInformationManager::getInstance()->getEventList(' AND e.eventId="'.$REQUEST_DATA['eventId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
		echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxEventGetValues.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/24/09    Time: 12:29p
//Updated in $/LeapCC/Library/Parent
//study period added (student fee)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:24p
//Created in $/LeapCC/Library/Parent
//file added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:05a
//Created in $/Leap/Source/Library/Student
//ajax file to show institute events through studentinformationmanager 
//
?>