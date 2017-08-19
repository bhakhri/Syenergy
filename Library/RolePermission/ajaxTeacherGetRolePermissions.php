<?php
//-------------------------------------------------------
//  This File fetches teacher role menu permissions
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherRolePermissions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();
$roleId = $REQUEST_DATA['roleId'];
$userPermissionArray = $loginManager->getAccessArray($roleId); 

//print_r($userPermissionArray);
$json_val = json_encode($userPermissionArray);
$json_dashboard = json_encode($dashboardPermissionArray);
 
echo '{"userPermissionArray" : ['.$json_val.'],"info" : ['.$json_dashboard.']}'; 

//$History: ajaxTeacherGetRolePermissions.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:04a
//Created in $/LeapCC/Library/RolePermission
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>