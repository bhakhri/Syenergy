<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A TimeTable Label
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LeaveSetManager.inc.php");
define('MODULE','LeaveSetMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
  
    if (!isset($REQUEST_DATA['leaveSetName']) || trim($REQUEST_DATA['leaveSetName']) == '') {
        $errorMessage .= ENTER_LEAVE_SET_NAME."\n";    
    }
      
    if (trim($errorMessage) == '') {
          //check for its usage in leave set mapping
          $usageArray=LeaveSetManager::getInstance()->checkLeaveSetUsage(trim($REQUEST_DATA['leaveSetId']));
          if($usageArray[0]['cnt']!=0){
                echo DEPENDENCY_CONSTRAINT_EDIT;
                die;
          }
          $foundArray = LeaveSetManager::getInstance()->getLeaveSet(' WHERE UCASE(leaveSetName)="'.add_slashes(strtoupper($REQUEST_DATA['leaveSetName'])).'" AND leaveSetId!='.$REQUEST_DATA['leaveSetId']);
          if(trim($foundArray[0]['leaveSetName'])=='') {  //DUPLICATE CHECK
				$returnStatus = LeaveSetManager::getInstance()->editLeaveSet($REQUEST_DATA['leaveSetId']);
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
// $History: ajaxInitEdit.php $
?>