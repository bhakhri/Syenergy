<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
   
    
// $History: scAjaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
?>

