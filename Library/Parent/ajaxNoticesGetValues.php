
<?php 

////  This File  Get the Record Data Form Table in "Notice" form
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentInstituteNotices');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['noticeId'] ) != '') {
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $foundArray = ParentManager::getInstance()->getNoticesList(' AND n.noticeId="'.$REQUEST_DATA['noticeId'].'"');
	$cnt = count($foundArray);
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = trim($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxNoticesGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/11/09    Time: 5:10p
//Updated in $/LeapCC/Library/Parent
//issue fix 997, 1002, 354, 1012, 1011, 985, 987, 989 
//validation, formatting update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/17/08    Time: 3:38p
//Updated in $/Leap/Source/Library/Parent
//changed the "ifNotLoggedIn()" function to "ifParentNotLoggedIn()"
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/16/08    Time: 10:55a
//Updated in $/Leap/Source/Library/Parent
//added comments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:03p
//Created in $/Leap/Source/Library/Parent
//added new files for Parent Module


?>
