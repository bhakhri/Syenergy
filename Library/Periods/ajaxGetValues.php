<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE PERIOD LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['periodId'] ) != '') {
    require_once(MODEL_PATH . "/PeriodsManager.inc.php");
    $foundArray = PeriodsManager::getInstance()->getPeriods(' AND pr.periodId='.$REQUEST_DATA['periodId']);
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
		}
		else {
			echo 'Period does not exist.';
		}
 }
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:37p
//Created in $/LeapCC/Library/Periods
//get existing period files in leap
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:10p
//Updated in $/Leap/Source/Library/Periods
//add define access in module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:18p
//Updated in $/Leap/Source/Library/Periods
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:17p
//Updated in $/Leap/Source/Library/Periods
?>

