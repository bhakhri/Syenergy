<?php 
//  This File calls addFunction used in adding Periodicity Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodicityMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);               
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['periodicityCode']) || trim($REQUEST_DATA['periodicityCode']) == '') {
        $errorMessage .= ENTER_PERIODICITY_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['periodicityName']) || trim($REQUEST_DATA['periodicityName']) == '')) {
        $errorMessage .= ENTER_PERIODICITY_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['periodicityFrequency']) || trim($REQUEST_DATA['periodicityFrequency']) == '')) {
        $errorMessage .= ENTER_PERIODICITY_FREQUENCY."\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
        $foundArray = PeriodicityManager::getInstance()->getPeriodicity(' WHERE UCASE(periodicityName)="'.add_slashes(strtoupper($REQUEST_DATA['periodicityName'])).'"');
        if(trim($foundArray[0]['periodicityName'])=='') {  //DUPLICATE CHECK
            $foundArray = PeriodicityManager::getInstance()->getPeriodicity(' WHERE UCASE(periodicityCode)="'.add_slashes(strtoupper($REQUEST_DATA['periodicityCode'])).'"');
            if(trim($foundArray[0]['periodicityName'])=='') {  //DUPLICATE CHECK
                $returnStatus = PeriodicityManager::getInstance()->addPeriodicity();
                if($returnStatus === false) {
                    $errorMessage = FAILURE;
                }
                else {
                    echo SUCCESS;           
                }
            }
            else {
                echo PERIODICITY_ABBR_EXIST;    
            }
        }
        else {
            echo PERIODICITY_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/21/09    Time: 4:03p
//Updated in $/LeapCC/Library/Periodicity
//issue fix (557, 559-564) format & validation checks updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/09/09    Time: 12:25p
//Updated in $/LeapCC/Library/Periodicity
//dupliate record validation added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Periodicity
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/05/08   Time: 5:16p
//Updated in $/Leap/Source/Library/Periodicity
//added module,access 
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/09/08    Time: 7:22p
//Updated in $/Leap/Source/Library/Periodicity
//added common messages
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/17/08    Time: 3:17p
//Updated in $/Leap/Source/Library/Periodicity
//modification
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/14/08    Time: 7:16p
//Updated in $/Leap/Source/Library/Periodicity
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Periodicity
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Periodicity
//NEw Files Added in Periodicity Folder

?>


