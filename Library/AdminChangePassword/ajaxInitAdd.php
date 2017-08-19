<?php 
//  This File calls changes the password used in CHANGE PASSWORD Records
//
// Author :Rajeev Aggarwal
// Created on : 22-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangePassword');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1); 
UtilityManager::ifNotLoggedIn(true);               
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
//$History: ajaxInitAdd.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/07/10    Time: 3:17p
//Updated in $/LeapCC/Library/AdminChangePassword
//management access right added
//
//*****************  Version 2  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Library/AdminChangePassword
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:40p
//Created in $/LeapCC/Library/AdminChangePassword
//Intial Checkin
?>