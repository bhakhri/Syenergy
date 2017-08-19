<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");  
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);  
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $timeTableLabelId = trim($REQUEST_DATA['timeTableLabelId']);
    
    if($timeTableLabelId=='') {
      $timeTableLabelId =-1;  
    }
    
    $condition = " AND ttl.timeTableLabelId= '$timeTableLabelId' ";
    $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses($condition," cls.degreeId,cls.branchId,cls.studyPeriodId");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>