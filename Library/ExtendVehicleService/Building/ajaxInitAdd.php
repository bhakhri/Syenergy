<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A building
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BuildingMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['buildingName']) || trim($REQUEST_DATA['buildingName']) == '') {
        $errorMessage .= ENTER_BUILDING_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['abbreviation']) || trim($REQUEST_DATA['abbreviation']) == '')) {
        $errorMessage .= ENTER_BUILDING_ABBR."\n";       
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BuildingManager.inc.php");
        $foundArray = BuildingManager::getInstance()->getBuilding(' WHERE UCASE(buildingName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['buildingName']))).'" OR UCASE(abbreviation)="'.add_slashes(strtoupper(trim($REQUEST_DATA['abbreviation']))).'"' );
        if(trim($foundArray[0]['buildingName'])=='') {  //DUPLICATE CHECK
            $returnStatus = BuildingManager::getInstance()->addBuilding();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
           if(trim(strtoupper($foundArray[0]['buildingName']))==trim(strtoupper($REQUEST_DATA['buildingName']))){
            echo BUILDING_ALREADY_EXIST;
            die;
           }
           if(trim(strtoupper($foundArray[0]['abbreviation']))==trim(strtoupper($REQUEST_DATA['abbreviation']))){
            echo BUILDING_ABBR_ALREADY_EXIST;
            die;
           }
           echo BUILDING_ALREADY_EXIST;
           die;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Library/Building
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Building
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:57p
//Updated in $/Leap/Source/Library/Building
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/20/08    Time: 6:18p
//Updated in $/Leap/Source/Library/Building
//Added Standard Messages
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