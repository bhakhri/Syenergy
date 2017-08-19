<?php
//-------------------------------------------------------
// Purpose: To delete UNIVERSITY detail
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementDriveMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    if($placementDriveId==''){
       die(PLACEMENT_DRIVE_NOT_EXIST);
    }
   
   //check for usage
   require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
   $foundArr=DriveManager::getInstance()->checkInPlacementResult($placementDriveId);
   if($foundArr[0]['found']!=0){
       die(DEPENDENCY_CONSTRAINT);
   }
   $cntArray = DriveManager::getInstance()->getPlacementDriveCount($placementDriveId);
	if ($cntArray[0]['cnt'] > 0) {
		echo DEPENDENCY_CONSTRAINT;
		die;
	}
        
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
      //delete placement test subjects
      $returnStatus = DriveManager::getInstance()->deletePlacementDriveTests($placementDriveId);
      if($returnStatus == false) {
        die(FAILURE);
      }

     
      //delete placement criteria
      $returnStatus = DriveManager::getInstance()->deletePlacementDriveCriteria($placementDriveId);
      if($returnStatus == false) {
        die(FAILURE);
      }
      
      //now delete placement drive 
      $returnStatus = DriveManager::getInstance()->deletePlacementDrive($placementDriveId);
      if($returnStatus == false) {
        die(FAILURE);
      }
 
      if(SystemDatabaseManager::getInstance()->commitTransaction()) {
         die(DELETE);
      }
   else {
        die(FAILURE);
    }
  }
  else {
     die(FAILURE);
  }
// $History: ajaxInitDelete.php $    
?>