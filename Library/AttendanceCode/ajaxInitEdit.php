<?php   
 //  This File calls Edit Function used in adding AttendanceCode Records
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();    
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['attendanceCodeName']) || trim($REQUEST_DATA['attendanceCodeName']) == '')) {
        $errorMessage .= ENTER_ATTENDANCE_NAME."\n";
    }
  
    if (!isset($REQUEST_DATA['attendanceCode']) || trim($REQUEST_DATA['attendanceCode']) == '') {
        $errorMessage .= ENTER_ATTENDANCE_CODE."\n";
    }
  
    if ($errorMessage == '' && (!isset($REQUEST_DATA['attendanceCodePercentage']) || trim($REQUEST_DATA['attendanceCodePercentage']) == '')) {
        $errorMessage .= ENTER_ATTENDANCE_PERCENTAGE."\n";
    }
      
      
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");    
        $foundArray = AttendanceCodeManager::getInstance()->getAttendanceCode(' WHERE UCASE(attendanceCodeName)="'.add_slashes(strtoupper($REQUEST_DATA['attendanceCodeName'])).'" AND attendanceCodeId!='.$REQUEST_DATA['attendanceCodeId']);
         if(trim($foundArray[0]['attendanceCode'])=='') {  //DUPLICATE CHECK     
          $foundArray = AttendanceCodeManager::getInstance()->getAttendanceCode(' WHERE UCASE(attendanceCode)="'.add_slashes(strtoupper($REQUEST_DATA['attendanceCode'])).'" AND attendanceCodeId!='.$REQUEST_DATA['attendanceCodeId']);
            if(trim($foundArray[0]['attendanceCode'])=='') {  //DUPLICATE CHECK     
                $returnStatus = AttendanceCodeManager::getInstance()->editAttendanceCode($REQUEST_DATA['attendanceCodeId']);            
                 if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
                else {
                    echo SUCCESS;           
                }
           }
           else {
             echo 'The Attendance code already exists.'; 
           }
        }
        else {
            echo 'The Attendance name already exists.';
        }
    }
    else {
        echo $errorMessage;
    }      

//$History: ajaxInitEdit.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/21/09   Time: 10:03a
//Updated in $/LeapCC/Library/AttendanceCode
//message updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Library/AttendanceCode
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/11/09    Time: 5:23p
//Updated in $/LeapCC/Library/AttendanceCode
//conditions, validation & formatting updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AttendanceCode
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 10:19a
//Updated in $/Leap/Source/Library/AttendanceCode
//Added Module, Access
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/09/08    Time: 6:41p
//Updated in $/Leap/Source/Library/AttendanceCode
//added common messages
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/21/08    Time: 4:05p
//Updated in $/Leap/Source/Library/AttendanceCode
//removed  attendanceCodePercentage 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 4:52p
//Updated in $/Leap/Source/Library/AttendanceCode
//modified the existing alert
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/17/08    Time: 4:14p
//Created in $/Leap/Source/Library/AttendanceCode
//added new files

?>


