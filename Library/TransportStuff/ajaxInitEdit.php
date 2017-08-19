<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A BUSSTOP
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStuffMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['stuffName']) || trim($REQUEST_DATA['stuffName']) == '') {
        $errorMessage .= ENTER_STUFF_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stuffCode']) || trim($REQUEST_DATA['stuffCode']) == '')) {
        $errorMessage .= ENTER_STUFF_CODE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['dlNo']) || trim($REQUEST_DATA['dlNo']) == '')) {
        $errorMessage .= ENTER_DRIVING_LICENSE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stuffType']) || trim($REQUEST_DATA['stuffType']) == '')) {
        $errorMessage .= SELECT_STUFF_TYPE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['dlAuthority']) || trim($REQUEST_DATA['dlAuthority']) == '')) {
        $errorMessage .= ENTER_DRIVING_LICENSE_AUTHORITY."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TransportStuffManager.inc.php");
        $foundArray = TransportStuffManager::getInstance()->getTransportStuff(' WHERE UCASE(stuffCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['stuffCode']))).'" AND stuffId!='.$REQUEST_DATA['stuffId']);
        if(trim($foundArray[0]['stuffCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = TransportStuffManager::getInstance()->editTransportStuff($REQUEST_DATA['stuffId']);
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }

        }
        else {
            echo STUFF_CODE_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:37
//Created in $/LeapCC/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>
