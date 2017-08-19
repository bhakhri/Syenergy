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
define('MODULE','ADVFB_AssignSurveyMasterReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['id']) != '' ) {
    require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
    $fbManager = FeedBackAssignSurveyAdvancedManager::getInstance();
    $foundArray=$fbManager->getStudentInformation(' WHERE userId='.trim($REQUEST_DATA['id']));
    if(is_array($foundArray) and count($foundArray)>0){
        echo json_encode($foundArray[0]);
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
// $History: ajaxGetStudentValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:05
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created the repoort for showing student status for feedbacks
?>