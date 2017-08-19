<?php
//-------------------------------------------------------
// Purpose: To delete stydy period
//
// Author : Dipanjan Bhattacharjee
// Created on : (2.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudyPeriodMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['studyPeriodId']) || trim($REQUEST_DATA['studyPeriodId']) == '') {
        $errorMessage = 'Invalid Study Period';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StudyPeriodManager.inc.php");
        $studyPeriodManager =  StudyPeriodManager::getInstance();
        $recordArray = $studyPeriodManager->checkInClass($REQUEST_DATA['studyPeriodId']);
        if($recordArray[0]['found']==0) {
            if($studyPeriodManager->deleteStudyPeriod($REQUEST_DATA['studyPeriodId']) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
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
//Created in $/LeapCC/Library/StudyPeriod
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:58a
//Updated in $/Leap/Source/Library/StudyPeriod
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:27p
//Updated in $/Leap/Source/Library/StudyPeriod
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:48p
//Updated in $/Leap/Source/Library/StudyPeriod
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 4:00p
//Created in $/Leap/Source/Library/StudyPeriod
//Initial Checkin
?>

