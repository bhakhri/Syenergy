<?php
//-------------------------------------------------------
// Purpose: To get values of coursewise timetable from the database
//
// Author : Parveen Sharma
// Created on : 28.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");    
define('MODULE','AssignFinalGrade');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 

    $assignFinalGrade = AssignFinalGradeManager::getInstance();   
    
    $foundArray = $assignFinalGrade->getAssignFinalGradeList();
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
       $jsonTimeTableArray  = array();
       for($s = 0; $s<$resultsCount; $s++) {
         $jsonTimeTableArray[] = $foundArray[$s];         
       }
    }
    
    $resultArray = array('finalGradeArr' => $jsonTimeTableArray);
    echo json_encode($resultArray);
?>