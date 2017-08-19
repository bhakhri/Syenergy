
<?php 

////  Get the data from event table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInstituteEvents');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['eventId'] ) != '') {
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    
    $orderBy = " $sortField $sortOrderBy";
    $foundArray = StudentInformationManager::getInstance()->getEventList(' AND e.eventId="'.$REQUEST_DATA['eventId'].'"',$orderBy,'');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
		echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxEventGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
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