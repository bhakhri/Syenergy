<?php

// This file is used to send the sms alert to students for change in there time table
// Created on : 08-Mar-2011
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
	UtilityManager::ifNotLoggedIn(true);
}

 //-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
/*function sendSMS($mobileNo,$message){
   return (UtilityManager::sendSMS($mobileNo, $message));
}*/

//$classId=10;
if($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENTS_FOR_CHANGED_TIME_TABLE') != ''){
	if($sessionHandler->getSessionVariable('SMS_ALERT_TO_STUDENTS_FOR_CHANGE_IN_TIME_TABLE') == 1){
		if($classId != '') {
			require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
			$studentInformationManager = StudentInformationManager::getInstance();
			$checkInTimeTableArray = $studentInformationManager->checkForChangeInTimeTable($classId);
			if($checkInTimeTableArray[0]['cnt'] >0){
				$studentMobileNoArray = $studentInformationManager->getStudentMobileNumbers($classId);
				$cnt = count($studentMobileNoArray);
				if($cnt > 0 and is_array($studentMobileNoArray)) {
					for($i=0; $i < $cnt ; $i++){
						if(trim($studentMobileNoArray[$i]['studentMobileNo']) != "" and trim($studentMobileNoArray[$i]['studentMobileNo'])!='NA' and strlen(trim($studentMobileNoArray[$i]['studentMobileNo']))>=10) {
							$ret = sendSMS($studentMobileNoArray[$i]['studentMobileNo'],strip_tags($sessionHandler->getSessionVariable('MESSAGE_TO_STUDENTS_FOR_CHANGED_TIME_TABLE')));
							if($ret) {
								$sms=1;
							}
							else {
								$sms=0;
								$smsNotSentStudentArray[]=$studentMobileNoArray[$i]['studentMobileNo'];
							}
						}
						else {
							$smsNotSentStudentArray[]=$studentMobileNoArray[$i]['studentMobileNo']; // this array contain all the mobile numbers which do not have receve sms this array can be used for making sms report in future
						}
					}
				}
			}
		}
	}
}
?>