<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['labelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
   
    $classId=trim($REQUEST_DATA['classId']);
    $classCondition='';
    if($classId!='' && $classId!='all'){
        $classCondition=' AND classId='.$classId;
    }
    $foundArray=FeedBackReportAdvancedManager::getInstance()->getEmployeesFromAnswerTable(' WHERE feedbackSurveyId='.trim($REQUEST_DATA['labelId']),'',$classCondition );
    if(is_array($foundArray) and count($foundArray)>0){
        echo json_encode($foundArray);
        die;
    }
    else{
        echo 0;
        die;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxInitGetEmployees.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:54
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Created "Class Final Report"  for advanced feedback modules.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/02/10   Time: 15:24
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Teacher GPA Report"
?>