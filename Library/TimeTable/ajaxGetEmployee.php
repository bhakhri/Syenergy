<?php
//-------------------------------------------------------
// Purpose: To get sections
// Author : Pushpender Kumar Chauhan
// Created on : (20.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/TimeTableManager.inc.php");
   
    if(trim($REQUEST_DATA['branchId']) != '') {
       $foundArray = TimeTableManager::getInstance()->getBranchTeacher(' AND e.branchId='.$REQUEST_DATA['branchId']);
    }
    else
    {
       $foundArray = TimeTableManager::getInstance()->getBranchTeacher('');
    }
    
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonSectionsArray  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSectionsArray[] = $foundArray[$s];         
        }
    }
	echo json_encode($jsonSectionsArray);

// $History: ajaxGetEmployee.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/25/09    Time: 11:49a
//Created in $/LeapCC/Library/TimeTable
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:01p
//Created in $/LeapCC/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:32p
//Created in $/SnS/Library/TimeTable
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:43a
//Created in $/Leap/Source/Library/ScTimeTable
//initial checkin
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:48p
//Updated in $/Leap/Source/Library/ScTimeTable
//applied role level access
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 9/20/08    Time: 9:32p
//Created in $/Leap/Source/Library/ScTimeTable

?>
