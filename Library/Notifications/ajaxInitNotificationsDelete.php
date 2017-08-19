<?php
//------------------------------------------------------------------------------------------------
//Purpose: This file deletes the notifications from the database which are more than 10 days older
//Author: Kavish Manjkhola
//Created On: 31/04/2011
//Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Notifications');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/NoticeManager.inc.php");
$noticeManager = NoticeManager::getInstance();
$timeLimit = $REQUEST_DATA['timeLimit'];   // this variable denotes the no of days the notice can be viewed after it was viewed first.

if(SystemDatabaseManager::getInstance()->startTransaction()) {
	$returnValue = $noticeManager->updateNotificationViewDateTime();
	if($returnValue = false) {
		echo FAILURE;
		die;
	}
	$returnStatus = $noticeManager->deleteInsuranceNoticePassTime($timeLimit);
	if($returnStatus = false) {
		echo FAILURE;
		die;
	}
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		echo DELETE;
		die;
	}
	else {
		echo FAILURE;
		die;
	}
}