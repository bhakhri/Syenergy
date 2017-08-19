<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GraceMarks');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GraceMarksManager.inc.php");
    $graceMarksManager = GraceMarksManager::getInstance();

    
    $conditions = " AND ttm.classId=".$REQUEST_DATA['class1']." 
                    AND ttm.subjectId=".$REQUEST_DATA['subject'];
    
    if($REQUEST_DATA['group']!='-1') {
      $conditions .=" AND sg.groupId=".$REQUEST_DATA['group'];
    }  
    $foundArray = $graceMarksManager->getGraceMaxMarks($conditions);
    
    if(is_array($foundArray) && count($foundArray)>0 ) {
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
    
die;     
    