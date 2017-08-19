<?php
//-------------------------------------------------------
// Purpose: To delete busstop detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BuildingMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['buildingId']) || trim($REQUEST_DATA['buildingId']) == '') {
        $errorMessage = 'Invalid Building';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BuildingManager.inc.php");
        $buildingManager =  BuildingManager::getInstance();
        
        /*buildingId is removed from "room" table
        //as "room" table depends on "building" table
        $recordArray = $buildingManager->checkInRoom($REQUEST_DATA['buildingId']);
        if($recordArray[0]['found']!=0) {
          echo DEPENDENCY_CONSTRAINT;
          die;   
        }
        */
        
        //as "block" table depends on "building" table
        $recordArray = $buildingManager->checkInBlock($REQUEST_DATA['buildingId']);
        if($recordArray[0]['found']==0) {
            if($buildingManager->deleteBuilding($REQUEST_DATA['buildingId']) ) {
                echo DELETE;
            }
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/09/09    Time: 12:22
//Updated in $/LeapCC/Library/Building
//Commented out checking with room's buildingId as buildingId is removed
//from "room" table
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 13/08/09   Time: 12:00
//Updated in $/LeapCC/Library/Building
//Added the check in during building deletion---If this building is used
//in "room" table then it should not be deleted
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Building
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:57p
//Updated in $/Leap/Source/Library/Building
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Library/Building
//Created Building Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:28p
//Created in $/Leap/Source/Library/Building
//Initial Checkin
?>

