<?php
//-------------------------------------------------------
//  This File saves role menu permissions
//
// Author :Rajeev Aggarwal
// Created on : 30-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . '/menuItems.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RolePermissions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();
$instituteId = $sessionHandler->getSessionVariable('InstituteId');

$dashboardFrameArray = $loginManager->getDashboardFrameList(" WHERE isActive =1"); 

//$History: scInitList.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/RolePermission
//added role permission module for user other than admin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:35p
//Updated in $/LeapCC/Library/RolePermission
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:30p
//Created in $/LeapCC/Library/RolePermission
//Intial checkin
?>
