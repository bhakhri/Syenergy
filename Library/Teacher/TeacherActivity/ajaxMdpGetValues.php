<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Gagan Gill
// Created on : (28.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
//if(trim($REQUEST_DATA['mdpId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $empManager = EmployeeManager::getInstance();
        
    $mdpArray = $empManager->getMdp(' AND mdpId ='.add_slashes($REQUEST_DATA['mdpId']));
    if(is_array($mdpArray) && count($mdpArray)>0 ) {  
        echo json_encode($mdpArray[0]);
		//die();
    }
    else {
        echo 0;
    }
    ?>