<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
    
  UtilityManager::ifNotLoggedIn(true);
}
else if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}

UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['timeTabelId'])!= '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
    $employeeId=0;
    /*
    if(trim($REQUEST_DATA['callingModule'])==2){ //if called from "Teacher End"
        $employeeId  = $sessionHandler->getSessionVariable('EmployeeId');
    }
    */
    if($sessionHandler->getSessionVariable('RoleId')==2){
        $employeeId  = $sessionHandler->getSessionVariable('EmployeeId');
    }
    //$conditions =' AND ttl.timeTableLabelId='.trim($REQUEST_DATA['timeTabelId']);
    
    if($employeeId!=0 and $employeeId!=''){
      //$conditions .=' AND t.employeeId='.$employeeId;
      $foundArray   = $teacherManager->getLabelMarksTransferredClassTeacher(trim($REQUEST_DATA['timeTabelId']),$employeeId);
    }
    else{
      $foundArray   = $teacherManager->getLabelMarksTransferredClass(trim($REQUEST_DATA['timeTabelId']));  
    }
    
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
}
// $History: ajaxGetTestWiseClasses.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/12/09    Time: 13:32
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected coding for "Role" based query execution in "Subject Wise
//Performance Report"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:10
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//created "Subject Wise Performance" report
?>