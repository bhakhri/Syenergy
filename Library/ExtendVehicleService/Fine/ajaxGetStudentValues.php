<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : SAURABH THUKRAL
// Created on : (20.08.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
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
    $fineStudentManager = FineManager::getInstance();
    
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    
    $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
    
    // Fetch Fine Role 
    $roleFineArray = $fineStudentManager->getSearchFineRole($roleId);
    $roleFineId = '0';
    if(is_array($roleFineArray) && count($roleFineArray)>0) {
      $roleFineId = $roleFineArray[0]['roleFineId'];   
    }  
    
    // Fine Category
    $roleCategoryArray = $fineStudentManager->getSearchFineCategory($roleFineId);
     
    // Fine Class
    $roleClassArray = $fineStudentManager->getSearchFineClass($roleFineId);
     
    // Fine Institute
    $roleInstituteArray = $fineStudentManager->getSearchFineInstitute($roleFineId);
    
     
    //FETCH THE STUDENT BASIC DETAILS
    $foundArray = FineManager::getInstance()->getStudentDetail(" AND st.rollNo='".$rollNo."'");
	

	echo json_encode($foundArray)."!!~~!!".json_encode($roleCategoryArray)."!!~~!!".json_encode($roleClassArray);
    die;
?>