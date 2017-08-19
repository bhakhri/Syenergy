<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    
    if(trim($REQUEST_DATA['timeTableLabelId'])!='' and isset($REQUEST_DATA['timeTableLabelId'])){
        //this condition has been added for test marks summary module
        //where teacher can view their test marks summary across different timetable labels
        $extC2=' AND t.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']); 
    }
    if(trim($REQUEST_DATA['classId'])!=0){
        $extC1=' AND c.classId='.trim($REQUEST_DATA['classId']);
    }
    $foundArray = TeacherManager::getInstance()->getTeacherSubject($extC1.$extC);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetAllSubjects.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 16:13
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Test Summary" module in teacher login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:58
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Duty Leave" module
?>