<?php
//-------------------------------------------------------
// Purpose: To get values of coursewise timetable from the database
//
// Author : Parveen Sharma
// Created on : 28.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");    
    define('MODULE','AttendanceIncentiveDetails');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
 
    $assignStudentIncentive = AssignFinalGradeManager::getInstance();    
    
    $foundArray =   $assignStudentIncentive->getIncentiveDetailList();

    echo json_encode($foundArray);

?>
