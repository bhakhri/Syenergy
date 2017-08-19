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
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifParentNotLoggedIn(true);                
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['currentPassword']) || trim($REQUEST_DATA['currentPassword']) == '') {
        $errorMessage .= ENTER_CURRENT_PASSWORD."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['userPassword']) || trim($REQUEST_DATA['userPassword']) == '')) {
        $errorMessage .= ENTER_NEW_PASSWORD."\n";
    }

	if($sessionHandler->getSessionVariable('SuperUserId')!=''){
		echo ACCESS_DENIED;
		die;
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
//$History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:46p
//Updated in $/LeapCC/Library/ChangePassword
//added access rights
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:43p
//Updated in $/LeapCC/Library/ChangePassword
//UPDATED ACCESS RIGHTS
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/ChangePassword
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/09/08    Time: 6:16p
//Created in $/Leap/Source/Library/ChangePassword
//initial checkin
?>