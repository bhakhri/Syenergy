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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['labelId'] ) != '' and trim($REQUEST_DATA['timeTableLabelId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
    $foundArray = FeedBackAssignSurveyAdvancedManager::getInstance()->getMappedSurveyCategories(' AND fs.timeTableLabelId="'.add_slashes(trim($REQUEST_DATA['timeTableLabelId'])).'" AND fs.feedbackSurveyId="'.add_slashes(trim($REQUEST_DATA['labelId'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {
        if($foundArray[0]['roleId']==2){
            $foundArray[0]['roleId']='Teacher';
        }
        else if($foundArray[0]['roleId']==3){
            $foundArray[0]['roleId']='Parent';
        }
        else if($foundArray[0]['roleId']==4){
            $foundArray[0]['roleId']='Student';
        }
        else{
            $foundArray[0]['roleId']=NOT_APPLICABLE_STRING;
        }
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetMappedSurveyCategories.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>