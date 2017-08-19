<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SuperLogin');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$superUserId=$sessionHandler->getSessionVariable('SuperUserId');
$superLoginSearchString=$sessionHandler->getSessionVariable('SuperLoginSearchString');

if($roleId==3){
  //UtilityManager::ifParentNotLoggedIn(true);
}
else if($roleId==4){
//  UtilityManager::ifStudentNotLoggedIn(true);  
}
else{
  echo FAILURE;
  die;
}
UtilityManager::headerNoCache();

if($superUserId==''){
  echo FAILURE;
  die;  
}

    require_once(MODEL_PATH . "/LoginManager.inc.php");
    $loginManager = LoginManager::getInstance();
    
    //get user credentials
    $returnStatus=$loginManager->getUserCredentials($superUserId);
    if($returnStatus[0]['roleId']!=1 ){
      //  echo FAILURE;
      //  die;
    }
    $roleId=$returnStatus[0]['roleId'];
    //***********store super user id and search string empty**********************
    $sessionHandler->setSessionVariable('SuperUserId','');
    $sessionHandler->setSessionVariable('SuperLoginSearchString','');
    //***********store super user id**********************
    
    //now change session variables
	//$sessionHandler->setSessionVariable('staticfooter','on');   //session variable for the still footer
    $sessionHandler->setSessionVariable('UserId',$returnStatus[0]['userId']);
    $sessionHandler->setSessionVariable('RoleId',$returnStatus[0]['roleId']);
    $sessionHandler->setSessionVariable('UserName',$returnStatus[0]['userName']);
    $sessionHandler->setSessionVariable('UserThemeId',$returnStatus[0]['themeId']);
    $sessionHandler->setSessionVariable('UserExpandCollapseGrouping',$returnStatus[0]['grouping']);
    $sessionHandler->setSessionVariable('RoleName',strip_slashes($returnStatus[0]['roleName']));
    $sessionHandler->setSessionVariable('RemoteIp',$_SERVER['REMOTE_ADDR']);
    $sessionHandler->setSessionVariable('ApplicationPath',HTTP_PATH);
    
    //update access rights
    //FETCH VALUES FROM CONFIG TABLE AND STORE INTO SESSION
    $configArray = $loginManager->getConfigSettings();
    if (is_array($configArray) && count($configArray)) {
        foreach($configArray as $configRecord) {
            $sessionHandler->setSessionVariable($configRecord['param'],$configRecord['value']);
        }
    }

    $accessArray = $loginManager->getAccessArray();
    foreach($accessArray as $accessRecord) {
        $sessionHandler->setSessionVariable($accessRecord['moduleName'], 
            Array (
            'view'    =>    $accessRecord['viewPermission'],
            'add'    =>    $accessRecord['addPermission'], 
            'edit'    =>    $accessRecord['editPermission'], 
            'delete'=>    $accessRecord['deletePermission']
            )
        );
    }
    
    $dashboardAccessArray = $loginManager->getDashboardAccessArray();
    foreach($dashboardAccessArray as $dashboardAccessRecord) {
        $sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],$dashboardAccessRecord['frameId'] );
    }

    //making student and parent session variable empty    
    $sessionHandler->setSessionVariable('StudentId','');
    $sessionHandler->setSessionVariable('StudentName','');
    $sessionHandler->setSessionVariable('ClassId','');
    $sessionHandler->setSessionVariable('LoggedName','');
    $sessionHandler->setSessionVariable('ParentId','');
    $sessionHandler->setSessionVariable('ParentName','');
    $sessionHandler->setSessionVariable('StudentArray','');
    $sessionHandler->setSessionVariable('userPhoto','');
    
    //we will not update login time as "Administrator" has not logged out yet
   
    echo $superLoginSearchString;
    die;
    
// $History: makeSuperUser.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/10    Time: 19:10
//Created in $/Leap/Source/Library/SuperLogin
//Created module "Super Login"
?>
