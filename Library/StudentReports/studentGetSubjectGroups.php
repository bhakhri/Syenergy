<?php
//-------------------------------------------------------
//  This File is used for fetching classes for a subject
//
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();


    $conditionEmployee = '';     
    if($roleId==2) {    
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      $conditionEmployee = " AND tt.employeeId = '$employeeId' ";
    }
    

   require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance(); 
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $timeTableLabelId = trim(add_slashes($REQUEST_DATA['timeTableLabelId'])); 
    $classId = trim(add_slashes($REQUEST_DATA['classId']));
    $subjectId = trim(add_slashes($REQUEST_DATA['subjectId']));
  
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
    
    if($classId=='') {
      $classId = 0;  
    }
 
    $subCondition ='';
    if($subjectId!='') {  
       if($subjectId!='all') {
         $subCondition = ' AND s.subjectId = '.$subjectId;   
       }
    }
 
    $condition  = " AND c.classId = $classId AND tt.timeTableLabelId = $timeTableLabelId ".$subCondition;   
    $condition .= $conditionEmployee;
    
    $groupArray = $employeeReportsManager->getTimeTableGroup($condition);
    
	echo json_encode($groupArray);

// $History: studentGetSubjectGroups.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/22/10    Time: 2:22p
//Updated in $/LeapCC/Library/StudentReports
//time table Label Id base check updated
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/13/09   Time: 9:54a
//Updated in $/LeapCC/Library/StudentReports
//format updated all subjects view 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/08/08   Time: 11:45a
//Created in $/LeapCC/Library/StudentReports
//student percentagewise report files added
//
//

?>