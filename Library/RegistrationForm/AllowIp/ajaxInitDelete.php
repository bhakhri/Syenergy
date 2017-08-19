<?php
//-------------------------------------------------------
// Purpose: To delete blockstudent detail
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AllowIp');
    define('ACCESS','delete');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/RegistrationForm/AllowIpManager.inc.php");
    $allowIpManager =AllowIpManager::getInstance();
    
    $deleteIp = trim($REQUEST_DATA['deleteIp']);
    
    if($deleteIp=='') {
      $deleteIp=0;  
    }
  
    $returnStatus = $allowIpManager->deleteIp($deleteIp);
    if($returnStatus === false) {
       echo FAILURE;
    }
    else {
       echo DELETE;
    }
?>

