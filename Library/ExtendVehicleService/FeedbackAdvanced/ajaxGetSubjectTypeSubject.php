<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
  
$timeTableLabelId = add_slashes(trim($REQUEST_DATA['timeTableLabelId']));
$catId = add_slashes(trim($REQUEST_DATA['catId']));
$classId = add_slashes(trim($REQUEST_DATA['classId']));       
    
if($timeTableLabelId != ''  and $catId != '' and $classId != '') {
   
    $subjectTypeId=-1;
    //fetch subject type from category table
    $condition = ' WHERE fc.feedbackCategoryId="'.$catId.'"' ;
    $foundArray1 = FeedBackAssignSurveyAdvancedManager::getInstance()->getCategoryRelation($condition);
    if(is_array($foundArray1) && count($foundArray1)>0){
      $subjectTypeId=$foundArray1[0]['subjectTypeId'];
    }
    else{
        echo 0;
        die;
    }
    
    //fetch classes
    $condition = ' AND ttc.timeTableLabelId="'.$timeTableLabelId.'" AND s.subjectTypeId="'.$subjectTypeId.'" AND c.classId="'.$classId.'"';
    //$foundArray2 = FeedBackAssignSurveyAdvancedManager::getInstance()->getSubjectTypeSubjects($condition);
    $foundArray2 = FeedBackAssignSurveyAdvancedManager::getInstance()->getAllSubjectTypeSubjects($condition);
    if(is_array($foundArray2) && count($foundArray2)>0 ) {
        echo json_encode($foundArray2);
        die;
    }
    else {
        echo 0;
        die;
    }
}
// $History: ajaxGetSubjectTypeSubject.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Library/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>