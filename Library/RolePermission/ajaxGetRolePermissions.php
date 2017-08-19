<?php
//-------------------------------------------------------
//  This File fetches role menu permissions
//
// Author :Ajinder Singh
// Created on : 06-Nov-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RolePermissions');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/LoginManager.inc.php");

$loginManager = LoginManager::getInstance();

$isInstitue = 1;
$roleId = $REQUEST_DATA['roleId'];
if($roleId=='') {
  $roleId='0';  
}

$allInstitute ='';
if($isInstitue=='0') {
  $roleInstituteArray =  $loginManager->getAccessInstituteArray($roleId); 
  $allInstitute = $roleInstituteArray[0]['allInstitute'];  
}

$userPermissionArray = $loginManager->getAccessArray($roleId);
$dashboardPermissionArray = $loginManager->getDashboardAccessArray1($roleId);



//print_r($userPermissionArray);
$json_val = json_encode($userPermissionArray);
$json_dashboard = json_encode($dashboardPermissionArray);
 
echo '{"userPermissionArray" : ['.$json_val.'],"info" : ['.$json_dashboard.'],"allInstitute" :"'.$allInstitute.'"}'; 

//$History: ajaxGetRolePermissions.php $
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
