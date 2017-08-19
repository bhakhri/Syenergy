<?php 
////  Get the data from notice table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineList');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['fineStudentId'] ) != '') {
    require_once(MODEL_PATH . "/FineManager.inc.php");
	$fineManager = FineManager::getInstance();
    $foundArray = $fineManager->getReason(' WHERE fineStudentId="'.$REQUEST_DATA['fineStudentId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
		echo json_encode($foundArray[0]);
    }
}


//$History: ajaxGetReason.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Library/Fine
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 11:51a
//Updated in $/LeapCC/Library/Fine
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/06/09    Time: 1:18p
//Created in $/LeapCC/Library/Fine
//put new ajax files to show student fine report
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
//User: Jaineesh     Date: 9/04/08    Time: 11:06a
//Created in $/Leap/Source/Library/Student
//get ajax function to show notices 
//
?>