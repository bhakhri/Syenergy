<?php
//-----------------------------------------------------------------------------------------------
// Purpose: To display student fine along with add,edit,delete,sorting and paging facility
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
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
    $userId = $sessionHandler->getSessionVariable('UserId');

    
	// Fetch Fine Role 
	$roleFineArray = $fineStudentManager->getSearchFineRole($roleId);
	$roleFineId = '0';
	if(is_array($roleFineArray) && count($roleFineArray)>0) {
	  $roleFineId = $roleFineArray[0]['roleFineId'];   
	}  
        
	// Fine Category
	$roleCategoryArray = $fineStudentManager->getSearchFineCategory($roleFineId);
         
	// Fine Class
	$condition = " AND c.isActive = 1";
	$roleClassArray = $fineStudentManager->getSearchFineClass($roleFineId,$condition);
         
	// Fine Institute
	$roleInstituteArray = $fineStudentManager->getSearchFineInstitute($roleFineId);

	echo json_encode($roleCategoryArray).'!~~!!~~!'.json_encode($roleClassArray).'!~~!!~~!'.json_encode($roleInstituteArray);
?>
