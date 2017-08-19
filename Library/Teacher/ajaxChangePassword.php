<?php 
//  This File calls changes the password used in CHANGE PASSWORD Records
//
// Author :Arvind Singh Rawat
// Created on : 09-Sept-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherChangePassword');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);                
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['currentPassword']) || trim($REQUEST_DATA['currentPassword']) == '') {
        $errorMessage .= ENTER_CURRENT_PASSWORD."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['userPassword']) || trim($REQUEST_DATA['userPassword']) == '')) {
        $errorMessage .= ENTER_NEW_PASSWORD."\n";
    }
       
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ChangePasswordManager.inc.php");
        $foundArray = ChangePasswordManager::getInstance()->getOldPassword(" AND userPassword='".md5($REQUEST_DATA['currentPassword'])."'");
        if(trim($foundArray[0]['userPassword'])=='') {  //DUPLICATE CHECK
			echo OLD_PASSWORD_CHECK;
        }
        else {            
			$returnStatus = ChangePasswordManager::getInstance()->addNewPassword();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
    }
    else {
        echo $errorMessage;
    }
//$History: ajaxChangePassword.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:15p
//Updated in $/LeapCC/Library/Teacher
//added access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/16/08    Time: 4:22p
//Created in $/Leap/Source/Library/Teacher
?>