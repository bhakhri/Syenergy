<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A StudyPeriod
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudyPeriodMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['periodName']) || trim($REQUEST_DATA['periodName']) == '') {
        $errorMessage .= ENTER_PERIOD_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['periodValue']) || trim($REQUEST_DATA['periodValue']) == '')) {
        $errorMessage .= ENTER_PERIOD_VALUE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['periodicityId']) || trim($REQUEST_DATA['periodicityId']) == '')) {
        $errorMessage .= SELECT_PERIODICITY."\n";    
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StudyPeriodManager.inc.php");
        $foundArray = StudyPeriodManager::getInstance()->getStudyPeriod(' WHERE UCASE(periodValue)="'.add_slashes(strtoupper($REQUEST_DATA['periodValue'])).'" AND periodicityId="'.add_slashes($REQUEST_DATA['periodicityId']).'"');
        if(trim($foundArray[0]['periodValue'])=='') {  //DUPLICATE CHECK
            $returnStatus = StudyPeriodManager::getInstance()->addStudyPeriod();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo STUDY_PERIOD_ALREADY_EXIST; 
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudyPeriod
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:58a
//Updated in $/Leap/Source/Library/StudyPeriod
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/20/08    Time: 3:03p
//Updated in $/Leap/Source/Library/StudyPeriod
//Added Standard Messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/08    Time: 6:23p
//Updated in $/Leap/Source/Library/StudyPeriod
//Added javascript validations and restructure add and edit divs to 
//have period value->periodicity->period name
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