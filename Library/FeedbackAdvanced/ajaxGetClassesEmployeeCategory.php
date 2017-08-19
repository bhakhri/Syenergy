<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Gurkeerat Sidhu
// Created on : (15.02.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeedBackReportAdvancedManager.inc.php");
     
    $labelId = trim($REQUEST_DATA['labelId']);
    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    
    
    if($labelId=='') {
      $labelId=0;  
    }
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    // Class
    $classArray=FeedBackReportAdvancedManager::getInstance()->getClassFromAnswerTable($timeTableLabelId,$labelId);
    
    // Employee
    $condition = " WHERE feedbackSurveyId = '$labelId' "; 
    $employeeArray=FeedBackReportAdvancedManager::getInstance()->getEmployeesFromAnswerTable($condition);
    
    // Category
    $categoryArray=FeedBackReportAdvancedManager::getInstance()->getAllCategories($labelId);
    
    echo json_encode($classArray).'!~!~!'.json_encode($employeeArray).'!~!~!'.json_encode($categoryArray); 
    die;   
?>