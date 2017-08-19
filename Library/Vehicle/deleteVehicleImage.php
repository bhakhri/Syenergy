<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','Vehicle');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['busId']) || trim($REQUEST_DATA['busId']) == '') {
        $errorMessage = 'Invalid Bus Image';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleManager.inc.php");
        //fetch image info
        $imageArray = VehicleManager::getInstance()->getVehicleImage(trim($REQUEST_DATA['busId']));
        
        //delete the image
        if(UtilityManager::notEmpty($imageArray[0]['busImage'])) {
            if(file_exists(IMG_PATH.'/Bus/'.$imageArray[0]['busImage'])) {
                @unlink(IMG_PATH.'/Bus/'.$imageArray[0]['busImage']);
              }
        }
        $returnStatus=VehicleManager::getInstance()->updateVehicleImage($REQUEST_DATA['busId'],'');
        if($returnStatus === false) {
                echo FAILURE;
                die;
        }
        else {
               echo DELETE;
               die;
        }
        
   }
   else {
        echo $errorMessage;
        die;
  }
    
// $History: deleteVehicleImage.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/12/09    Time: 10:14
//Created in $/Leap/Source/Library/Vehicle
//check in files
?>