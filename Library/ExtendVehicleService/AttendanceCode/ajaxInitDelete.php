<?php
//-------------------------------------------------------
// Purpose: To delete attendance detail
//
// Author : Arvind  Singh Rawat
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['attendanceCodeId']) || trim($REQUEST_DATA['attendanceCodeId']) == '') {
        $errorMessage = 'Invalid Attendance';
    }
    if (trim($errorMessage) == '') {     
        require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");
        $attendanceManager =  AttendanceCodeManager::getInstance();
        
        $foundArray = $attendanceManager->getCheckAttendance(" WHERE  attendanceCodeId = '".$REQUEST_DATA['attendanceCodeId']."'");
        if(trim($foundArray[0]['cnt'])==0) {  //DUPLICATE CHECK   
            if($attendanceManager->deleteAttendanceCode($REQUEST_DATA['attendanceCodeId']) ) {
                echo DELETE;
            }
        	else {
            	echo DEPENDENCY_CONSTRAINT;
       		 }   
        }
        else {
           echo DEPENDENCY_CONSTRAINT;
        } 
    }     
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/05/10    Time: 5:12p
//Updated in $/LeapCC/Library/AttendanceCode
//function name updated (getCheckAttendance)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/AttendanceCode
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AttendanceCode
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 10:19a
//Updated in $/Leap/Source/Library/AttendanceCode
//Added Module, Access
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:32a
//Updated in $/Leap/Source/Library/AttendanceCode
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:03p
//Created in $/Leap/Source/Library/AttendanceCode
//added a new file for ajax delete
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>

