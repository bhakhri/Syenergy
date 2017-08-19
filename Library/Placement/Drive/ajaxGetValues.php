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
    $foundArray = DriveManager::getInstance()->getPlacementDrives(' WHERE placementDriveId="'.trim($REQUEST_DATA['placementDriveId']).'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        
        $time=explode(':',$foundArray[0]['startTime']);
        $foundArray[0]['startTime']=$time[0].':'.$time[1];
        
        $time=explode(':',$foundArray[0]['endTime']);
        $foundArray[0]['endTime']=$time[0].':'.$time[1];
        
        
       //fetch drive's test information
       $foundArray2 = DriveManager::getInstance()->getPlacementDrivesTest(' WHERE placementDriveId="'.trim($REQUEST_DATA['placementDriveId']).'"');   
       
       //fetch drive's criteria information
       $foundArray3 = DriveManager::getInstance()->getPlacementDrivesCriteria(' WHERE placementDriveId="'.trim($REQUEST_DATA['placementDriveId']).'"');
       if($foundArray3[0]['cutOffMarksLastSem']==''){
          $foundArray3[0]['cutOffMarksLastSem']=''; 
       }
       if($foundArray3[0]['cutOffMarksGraduation']==''){
          $foundArray3[0]['cutOffMarksGraduation']=''; 
       }
       
       echo '{"edit":['.json_encode($foundArray[0]).'],"criteria":['.json_encode($foundArray3[0]).'],"tests":'.json_encode($foundArray2).'}';
    }
    else {
        echo 0;
    }
    
}
// $History: ajaxGetValues.php $
?>