<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fine Category LIST
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");  
    $fineManager = FineManager::getInstance(); 
    
    $roleFineId = trim($REQUEST_DATA['roleFineId']);
    if($roleFineId=='') {
      $roleFineId='0';  
    }
    
    $condition = ' AND rf.roleFineId="'.$roleFineId.'"';
    $foundArray = $fineManager->getMappedFineCategories($condition);
    
    $validInstitute = $foundArray[0]['fineInstituteId'];
    if($validInstitute=='') {
      $validInstitute='0';   
    }
    $condition = " AND c.instituteId IN (".$validInstitute.") AND c.isActive IN (1,3) ";
    $classFoundArray = $fineManager->fetchClases($condition);
    echo json_encode($foundArray[0])."!!~~!!".json_encode($classFoundArray);
    
?>