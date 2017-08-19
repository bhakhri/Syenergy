<?php 
////  Get the data from notice table 
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
global $sessionHandler;
//$classId= $sessionHandler->getSessionVariable('ClassId');
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
  UtilityManager::ifManagementNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/NoticeManager.inc.php");
$noticeManager = NoticeManager::getInstance();
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['noticeId'] ) != '') {
	
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeSubject';
    $orderBy = " $sortField $sortOrderBy";

    $foundArray = $noticeManager->getAllInstituteNotices(' AND n.noticeId="'.$REQUEST_DATA['noticeId'].'"','',$orderBy);
	$cnt = count($foundArray);
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = trim($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
    if(is_array($foundArray) && count($foundArray)>0 ) { 
		$updateReadStatus= StudentInformationManager::getInstance()->noticeStatus($REQUEST_DATA['noticeId']);
		echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxNoticesGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/12/09    Time: 4:49p
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0000969,0000965, 0000962, 0000963
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