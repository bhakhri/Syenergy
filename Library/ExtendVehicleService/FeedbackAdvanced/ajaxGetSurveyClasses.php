<?php
//-------------------------------------------------------
//  This File is used for fetching time table label for a survey and classes
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    $feedBackTeacherMappingManager =FeedBackTeacherMappingManager::getInstance();
    
    require_once(MODEL_PATH . "/FeedBackAssignSurveyAdvancedManager.inc.php");
    $feedBackAssignSurveyAdvancedManager =FeedBackAssignSurveyAdvancedManager::getInstance();
    
    
    $timeTableLabelId = add_slashes(trim($REQUEST_DATA['timeTableLabelId']));
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    // Fetch Survey
    $surveyArray = $feedBackAssignSurveyAdvancedManager->getSurveyLabel(' AND fs.timeTableLabelId="'.$timeTableLabelId.'"');
   
    // Fetch Classes
    $classArray=$feedBackTeacherMappingManager->getTimeTableClass(' AND ttc.timeTableLabelId="'.$timeTableLabelId.'"');
    
   
    echo json_encode($surveyArray).'!~!~!'.json_encode($classArray); 

?>