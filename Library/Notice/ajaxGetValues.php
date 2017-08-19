
<?php 

////  This File checks  whether record exists in Notice Form Table
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from Notice table
 
if(trim($REQUEST_DATA['noticeId'] ) != '') {
    require_once(MODEL_PATH . "/NoticeManager.inc.php");
    $foundArray = NoticeManager::getInstance()->getNotice(' AND n.noticeId="'.$REQUEST_DATA['noticeId'].'"');
	$cnt = count($foundArray);
	
	for($i=0;$i<$cnt;$i++){
		$foundArray[$i][noticeSubject] = html_entity_decode($foundArray[$i][noticeSubject]);
		$foundArray[$i][noticeText] = html_entity_decode($foundArray[$i][noticeText]);
	}
	//echo $foundArray['noticeSubject'];
   //print_r($foundArray);
	if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Notice
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Notice
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Notice
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/07/08    Time: 4:51p
//Created in $/Leap/Source/Library/Notice
//Added a new module   "Notice" files

?>
