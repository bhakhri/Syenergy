<?php
//-------------------------------------------------------
// Purpose: To change theme upon selection by the user
// Author : Dipanjan Bhattacharjee
// Created on : (17.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
$userId=$sessionHandler->getSessionVariable('UserId');
if(!isset($userId) or trim($userId)==0 or trim($userId)==''){
    redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
    $errorMessage='';
    
    if (!isset($REQUEST_DATA['helpId']) || trim($REQUEST_DATA['helpId']) == '') {
        $errorMessage = 'Invalid Help Option';
    }
    if (trim($errorMessage) == '') {
        $sessionHandler->setSessionVariable('HELP_PERMISSION',trim($REQUEST_DATA['helpId']));
        echo HELP_CHANGE_OK;
        die;
    }
    else {
        echo $errorMessage;
        die;
    }
// $History: changeHelpPreference.php $    
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/11/09    Time: 15:19
//Created in $/LeapCC/Library/ChangePreference
//Added new file for making "Help Facility Toggling" persistent
?>