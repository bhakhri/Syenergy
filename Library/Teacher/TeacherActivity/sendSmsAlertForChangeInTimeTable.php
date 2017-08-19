<?php

// This file is used to send the sms alert to teachers for change in there time table
// Created on : 08-Mar-2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}
//UtilityManager::ifTeacherNotLoggedIn(true);
//UtilityManager::headerNoCache();
	 //-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}

//$classId=10 ;
if($sessionHandler->getSessionVariable('MESSAGE_TO_TEACHERS_FOR_CHANGED_TIME_TABLE') != ''){
	if($sessionHandler->getSessionVariable('SMS_ALERT_TO_TEACHERS_FOR_CHNAGE_IN_TIME_TABLE') == 1){
		if($classId != '') {
			require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
			$teacherManager = TeacherManager::getInstance();
			$checkInTimeTableArray = $teacherManager->checkForChangeInTimeTable($classId);
			if(count($checkInTimeTableArray) > 0 and is_array($checkInTimeTableArray)){
				$employeeIdList = UtilityManager::makeCSList($checkInTimeTableArray,'employeeId');
				$employeeMobileArray = $teacherManager->getTeachertMobileNumbers($employeeIdList);
				$cnt = count($employeeMobileArray);
				if($cnt > 0 and is_array($employeeMobileArray)){
					   for($i=0; $i < $cnt ; $i++){
							if(trim($employeeMobileArray[$i]['mobileNumber'])!="" and trim($employeeMobileArray[$i]['mobileNumber'])!='NA' and strlen(trim($employeeMobileArray[$i]['mobileNumber']))>=10){
								copyHODSendSMS($sessionHandler->getSessionVariable('MESSAGE_TO_TEACHERS_FOR_CHANGED_TIME_TABLE'));
								$ret=sendSMS($employeeMobileArray[$i]['mobileNumber'],strip_tags($sessionHandler->getSessionVariable('MESSAGE_TO_TEACHERS_FOR_CHANGED_TIME_TABLE')));
								if($ret){
									$sms=1;
								}
								else{
									$sms=0;$smsNotSentArray[]=$employeeMobileArray[$i]['mobileNumber'];
								}
						   }
						  else{
							 $smsNotSentArray[]=$employeeMobileArray[$i]['mobileNumber']; // this array contain all the mobile numbers which do not have receve sms this array can be used for making sms report in future
						  }
						}
					}
			}
		}
	}
}
?>