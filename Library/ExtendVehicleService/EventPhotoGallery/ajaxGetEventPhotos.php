<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : DB
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
        define('MANAGEMENT_ACCESS',1);
	global $sessionHandler; 
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if($roleId=='2') { 
	  UtilityManager::ifTeacherNotLoggedIn(true);    
	}
	else if($roleId=='3') { 
	  UtilityManager::ifParentNotLoggedIn(true);  
	}
	else if($roleId=='4') { 
	  UtilityManager::ifStudentNotLoggedIn(true);
	}
	else if($roleId=='5') { 
	  UtilityManager::ifManagementNotLoggedIn(true);
	}
	else {
	  UtilityManager::ifNotLoggedIn(true); 
	}
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/PhotoManager.inc.php");
    $photoGalleryId = $REQUEST_DATA['photoGalleryId'];

      
    $photoGalleryId=add_slashes(trim($REQUEST_DATA['photoGalleryId']));   
    if($photoGalleryId=='') {
      $photoGalleryId=0;   
    }
   
    
    $condition = " AND pm.photoGalleryId= '$photoGalleryId' ";
    $orderBy = "photoGalleryId ASC";
    $foundArray = PhotoManager::getInstance()->getPhoto($condition,$orderBy);
    if(is_array($foundArray) && count($foundArray)>0 ) {
	   echo json_encode($foundArray);
	}
	else {
	   echo 0 ;
	}
?>
