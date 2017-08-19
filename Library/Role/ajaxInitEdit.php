<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A Role
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
define('MODULE','RoleMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['roleName']) || trim($REQUEST_DATA['roleName']) == '') {
        $errorMessage .= ENTER_ROLE_NAME."\n";   
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoleManager.inc.php");
        $foundArray = UserRoleManager::getInstance()->getRole(' WHERE UCASE(roleName)="'.add_slashes(strtoupper($REQUEST_DATA['roleName'])).'" AND roleId!='.$REQUEST_DATA['roleId']);
        if(trim($foundArray[0]['roleName'])=='') {  //DUPLICATE CHECK
            $returnStatus = UserRoleManager::getInstance()->editRole($REQUEST_DATA['roleId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo ROLE_ALREADY_EXIST;    
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Role
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:32a
//Updated in $/Leap/Source/Library/Role
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/08    Time: 12:28p
//Updated in $/Leap/Source/Library/Role
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Library/Role
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:58p
//Created in $/Leap/Source/Library/Role
//Initial checkin
?>
