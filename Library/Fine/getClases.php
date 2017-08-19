<?php
//-------------------------------------------------------
//  This File is used for fetching Batches
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
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
	
    $mode=$REQUEST_DATA['mode']; 
	$validInstitute=$REQUEST_DATA['validInstitute'];

    if($validInstitute=='') {
      $validInstitute='0';  
    }
    if($mode=='') {
      $mode='A';  
    }
    
    if($mode=='A') {
      $isActive = '1';
    }
    else {
      $isActive = '1,3';  
    }
    
    $condition = " AND c.instituteId IN (".$validInstitute.") AND c.isActive IN ($isActive) ";
	$classArray = $fineManager->fetchClases($condition);
	if(count($classArray) > 0 && is_array($classArray)) {
	  echo json_encode($classArray);
	}
	else {
	  echo 0;
	}
?>
