<?php
//-------------------------------------------------------
// Purpose: To delete role detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['roleId']) || trim($REQUEST_DATA['roleId']) == '') {
        $errorMessage = 'Invalid Role';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoleManager.inc.php");
        $roleManager =  UserRoleManager::getInstance();
        //check whether logged in user(of that type of role tries to delete himself/herself
        if($sessionHandler->getSessionVariable('RoleId')!=$REQUEST_DATA['roleId']) {
            //check in role_permission
            $foundArray1=$roleManager->checkInRolePermission($REQUEST_DATA['roleId']);
            if($foundArray1[0]['cnt']!=0){
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
            
            //check in dashboard_permissions
            $foundArray1=$roleManager->checkInDashBoardPermission($REQUEST_DATA['roleId']);
            if($foundArray1[0]['cnt']!=0){
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
            
            
            if($roleManager->deleteRole($REQUEST_DATA['roleId']) ) {
                echo DELETE;
            } 
        }
        else {
            echo "Please LogIn As Different User(Different Role) and Then Delete This Role";
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/01/10    Time: 12:45
//Updated in $/LeapCC/Library/Role
//Corrected query error
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:32a
//Updated in $/Leap/Source/Library/Role
//Added access rules
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

