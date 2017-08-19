<?php 
//  This File calls changes the password used in CHANGE PASSWORD Records
//
// Author :Jaineesh
// Created on : 09-Sept-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentChangePassword');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn(true);                
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
//$History: ajaxInitAddPassword.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:25p
//Created in $/Leap/Source/Library/Student
//to get password
//

?>