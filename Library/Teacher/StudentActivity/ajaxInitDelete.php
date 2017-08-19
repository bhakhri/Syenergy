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
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['cityId']) || trim($REQUEST_DATA['cityId']) == '') {
        $errorMessage = 'Invalid City';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CityManager.inc.php");
        $cityManager =  CityManager::getInstance();
        $recordArray = $cityManager->checkInInstitute($REQUEST_DATA['cityId']);
        if($recordArray[0]['found']==0) {
            if($cityManager->deleteCity($REQUEST_DATA['cityId']) ) {
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
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:19p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan   Date: 6/25/08    Time: 11:31 a
//Updated in $/Leap/Source/Library/City
//added code to delete city
//
?>

