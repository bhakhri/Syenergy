<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO all options(class,subject,group) corresponding to a particular date for a teacher 
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");                                                                
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    
    if(trim($REQUEST_DATA['classId'])!= '' ){
     $startDate=trim($REQUEST_DATA['startDate']);
     $endDate=trim($REQUEST_DATA['endDate']);
     $date=date('Y-m-d');
     if($startDate==''){
       $startDate=$date;
     }
     if($endDate==''){
       $endDate=$date;  
     }
     //*************Get adjusted data for groups*************
     $timeTableLabelTypeConditions='';
     if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
        $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';  
     }
     $foundArray = TeacherManager::getInstance()->getAdjustedSubjectGroupForComments($REQUEST_DATA['classId'],$startDate,$endDate,' ',$timeTableLabelTypeConditions);
    }
    else{
        echo 0;
        die;
    }

    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetAdjustedGroupForComments.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/10/09   Time: 17:56
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added code for "Time table adjustment"
?>