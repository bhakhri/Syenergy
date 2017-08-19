<?php
//-------------------------------------------------------
// Purpose: To delete period detail
//
// Author : Jaineesh
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['periodId']) || trim($REQUEST_DATA['periodId']) == '') {
        $errorMessage = INVALID_PERIOD;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PeriodsManager.inc.php");
        $periodsManager =  PeriodsManager::getInstance();
			$periodTimeTableArray = $periodsManager->checkPeriodWithTimeTable($REQUEST_DATA['periodId']);
			if($periodTimeTableArray[0]['totalRecords'] > 0 ) {
				echo DEPENDENCY_CONSTRAINT;
				die;
			}
			else {
				if($periodsManager->deletePeriods($REQUEST_DATA['periodId']) ) {
					echo DELETE;
				}
				else {
					echo DEPENDENCY_CONSTRAINT;
				}
			}
    }
    else {
        echo $errorMessage;
    }
   
// $History: ajaxInitDelete.php $    
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
//User: Jaineesh     Date: 8/26/08    Time: 5:27p
//Updated in $/Leap/Source/Library/Periods
//modified message
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:59p
//Created in $/Leap/Source/Library/Periods
//function is used for delete period
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>