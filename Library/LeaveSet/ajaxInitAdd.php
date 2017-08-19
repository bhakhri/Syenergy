<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Time Table
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveSetMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['leaveSetName']) || trim($REQUEST_DATA['leaveSetName']) == '') {
        $errorMessage .= ENTER_LEAVE_SET_NAME."\n";    
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LeaveSetManager.inc.php");
        $foundArray = LeaveSetManager::getInstance()->getLeaveSet(' WHERE UCASE(leaveSetName)="'.trim(add_slashes(strtoupper($REQUEST_DATA['leaveSetName']))).'"');
        if(trim($foundArray[0]['leaveSetName'])=='') {  //DUPLICATE CHECK
                $returnStatus = LeaveSetManager::getInstance()->addLeaveSet();
                if($returnStatus == false) {
                    echo FAILURE;
					die;
                }
                echo SUCCESS;
				die;
        }
      else {
            echo LEAVE_SET_ALREADY_EXIST;
			die;
      }
   }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>