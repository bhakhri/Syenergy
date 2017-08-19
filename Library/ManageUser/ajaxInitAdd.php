<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD AN USER
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManageUsers');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	//print_r($REQUEST_DATA);
	//die();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['userName']) || trim($REQUEST_DATA['userName']) == '') {
        $errorMessage .= ENTER_USER_NAME."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['userPwd']) || trim($REQUEST_DATA['userPwd']) == '')) {
        $errorMessage .= ENTER_USER_PASSWORD."\n";   
    }
    /*if ($errorMessage == '' && (!isset($REQUEST_DATA['roleId']) || trim($REQUEST_DATA['roleId']) == '')) {
        $errorMessage .= SELECT_ROLE."\n";   
    }*/
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ManageUserManager.inc.php");
        $foundArray = ManageUserManager::getInstance()->getUser(' WHERE UCASE(userName)="'.add_slashes(strtoupper($REQUEST_DATA['userName'])).'"');
        if(trim($foundArray[0]['userName'])=='') {  //DUPLICATE CHECK
            $returnStatus = ManageUserManager::getInstance()->addUser();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo USER_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Library/ManageUser
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/ManageUser
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:45p
//Updated in $/Leap/Source/Library/ManageUser
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/08    Time: 1:37p
//Updated in $/Leap/Source/Library/ManageUser
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Library/ManageUser
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:08p
//Created in $/Leap/Source/Library/ManageUser
//Initial Checkin
?>