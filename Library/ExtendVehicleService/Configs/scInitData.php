<?php
/*
 This File calls addFunction used to list Config Records

 Author :Rajeev Aggarwal
 Created on : 28-02-2009
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ConfigMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/ConfigsManager.inc.php");
	$configsManager = ConfigsManager::getInstance();
	$configsRecordArray = $configsManager->getConfigList();

	
//$reminderArray = $configsManager->getReminderConfigList();
 
// $History: scInitData.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-03   Time: 4:18p
//Updated in $/LeapCC/Library/Configs
//Added Time table conflict parameters
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:50p
//Updated in $/LeapCC/Library/Configs
//Updated with birthday reminder parameters
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 3/18/09    Time: 12:17p
//Created in $/LeapCC/Library/Configs
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 3/18/09    Time: 11:27a
//Created in $/Leap/Source/Library/Configs
//Intial checkin
?>