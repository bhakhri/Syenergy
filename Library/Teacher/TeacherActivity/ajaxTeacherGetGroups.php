<?php
//-------------------------------------------------------
// Purpose: To get values of Groups
//
// Author : PArveen Sharma
// Created on : 02-06-09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();

    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    global $sessionHandler;  
    
    $classId = trim($REQUEST_DATA['classId']);
    $employeeId = trim($sessionHandler->getSessionVariable('EmployeeId'));   
    $timeTabelId = trim($REQUEST_DATA['timeTabelId']);
    $subjectId = trim($REQUEST_DATA['subject']);
    
    if($classId=='') {
      $classId = 0;
    }
    
    if($employeeId=='') {
      $employeeId = 0;
    }
    
    if($timeTabelId=='') {
      $timeTabelId = 0;
    }
    
    if($subjectId=='') {
      $subjectId = 0;
    }
    
    
    // Findout Current Time table Label Id
    //$timeTable = "(SELECT timeTableLabelId FROM time_table_labels WHERE sessionId=".$sessionHandler->getSessionVariable('SessionId')." AND instituteId=".$sessionHandler->getSessionVariable('InstituteId').")";
    $condition = ' AND sub.hasAttendance = 1         
                   AND gr.classId = "'.$classId.'"
                   AND tt.subjectId="'.$subjectId.'" 
                   AND tt.employeeId="'.$employeeId.'" 
                   AND tt.timeTableLabelId="'.$timeTabelId.'"  
                   AND tt.toDate IS NULL ';
                   //AND tt.timeTableLabelId IN '.$timeTable;
    $foundArray = CommonQueryManager::getInstance()->getTimeTableSubjectGroups($condition);
    
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonTimeTable  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonGroup .= json_encode($foundArray[$s]). ( $s==($resultsCount-1) ? '' : ',' );                
        }
    }  
    echo '{"groupArr":['.$jsonGroup.']}';

// $History: ajaxTeacherGetGroups.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 10:57a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/09   Time: 3:21p
//Created in $/LeapCC/Library/EmployeeReports
//initial checkin
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:15p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/09/09   Time: 5:18p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//date checks & group query updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/09/09   Time: 1:50p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//isActive check remove (timeTableLabelId)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//class added, look & feel formating 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:28p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//initial checkin
//

?>