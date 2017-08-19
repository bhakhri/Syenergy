<?php
//-------------------------------------------------------
//  This File contains logic for groups
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Groups');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::ifCompanyNotSelected();
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['groupId'] ) != '') {

	require_once(MODEL_PATH . '/Accounts/GroupsManager.inc.php');
    $groupsManager = GroupsManager::getInstance();

	$groupArray = $groupsManager->getGroup(' AND grp.groupId ='.$REQUEST_DATA['groupId']);

	if(is_array($groupArray) && count($groupArray)>0 ) {  
		echo json_encode($groupArray[0]);
	}
	else {
		echo 0;
	}
	
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Groups
//file added
//



?>