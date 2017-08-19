<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE UNIVERSITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
  
if(trim($REQUEST_DATA['placementDriveId'] ) != '') {
    require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
    //fetch main data
    $foundArray = DriveManager::getInstance()->getLastSemMarks(' WHERE placementDriveId="'.trim($REQUEST_DATA['placementDriveId']).'"');
	$status =1;

//	print_r($foundArray);
   if($foundArray[0]['cutOffMarksLastSem'] == "") {

     DriveManager::getInstance()->getGraduationMarks(' WHERE placementDriveId="'.trim($REQUEST_DATA['placementDriveId']).'"');
		$status =2;
   }
 
       
       echo $status;
    }
    else {
        echo 0;
    }
    

// $History: ajaxGetValues.php $
?>