<?php
/*
  This File calls addFunction used in adding Config Records

 Author :Rajeev Aggarwal
 Created on : 02-03-2009
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ConfigMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();

require_once(MODEL_PATH . "/ConfigsManager.inc.php");
$configsManager = ConfigsManager::getInstance();
$configsRecordArray = $configsManager->getConfigList();
$errorMessage = '';
//print_r($_REQUEST);
//		die();
foreach($_REQUEST as $configRecord=>$val) {

	$idArr = explode("_", $configRecord);

	
	/*$configId = $configRecord['configId'];
	$fetchedConfigId = 'config_'.$configId;
	$pattern = '/[^\sa-zA-Z0-9.,-_]/';
	$value = trim($REQUEST_DATA[$fetchedConfigId]);
	preg_match($pattern, $value, $matches);
	if (count($matches) and isset($matches[0])) {
		
		$errorMessage =  ALLOWED_SPECIAL_CHARS;
		break;
	}
	elseif(strpos($value,'@') !== false) {
		
		$errorMessage =  ALLOWED_SPECIAL_CHARS;
		break;
	}*/
	if($idArr[1]!=''){
        if($idArr[1]!='ai') {
            $returnStatus = $configsManager->editConfig($idArr[1], trim($REQUEST_DATA[$configRecord]));
		    if($returnStatus === false) {
			    $errorMessage = FAILURE;
			    break;
		    }
        }
	}
 	 
}
//FETCH VALUES FROM CONFIG TABLE AND STORE INTO SESSION
$configArray = $loginManager->getConfigSettings();
if (is_array($configArray) && count($configArray)) {

	foreach($configArray as $configRecord) {

		$sessionHandler->setSessionVariable($configRecord['param'],$configRecord['value']);
	}
}
 
/*
 
$configsReminderArray = $configsManager->getReminderConfigList();
$errorMessage1 = '';
 
foreach($configsReminderArray as $configRecord){

	$configId = $configRecord['reminderId'];
	$fetchedConfigId = 'reminderConfig_'.$configId;
	//$REQUEST_DATA[$fetchedConfigId];
	$returnReminderStatus = $configsManager->editReminderConfig($configId,trim($REQUEST_DATA[$fetchedConfigId]));
	if($returnReminderStatus === false) {
		$errorMessage1 = FAILURE;
		break;
	}
}
*/
$sessionHandler->setSessionVariable('IdToStudentFileUpload',1);  
//$sessionHandler->setSessionVariable('IdToEmployeeFileUpload',14);

if ($errorMessage != '') {

	echo $errorMessage;
}
else {
	echo SUCCESS;
}
// $History: ajaxInitSave.php $
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-09-01   Time: 3:25p
//Updated in $/LeapCC/Library/Configs
//commented config reminder parameter till the time functionality is
//built
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-08-24   Time: 10:28a
//Updated in $/LeapCC/Library/Configs
//Updated access level permission from "add" to "edit"
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/10/09    Time: 5:40p
//Updated in $/LeapCC/Library/Configs
//Added InstituteId in Config Table and Updated code 
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:49p
//Updated in $/LeapCC/Library/Configs
//added reminder and other Bus Pass config parameter
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/25/09    Time: 1:18p
//Updated in $/LeapCC/Library/Configs
//Updated session variable at the same time when config parameter are
//changed
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 3/18/09    Time: 12:17p
//Updated in $/LeapCC/Library/Configs
//Updated as per new formatting
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 3/18/09    Time: 11:27a
//Updated in $/Leap/Source/Library/Configs
//Updated as per new format
?>