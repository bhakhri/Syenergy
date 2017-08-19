<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Role List
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);    
UtilityManager::headerNoCache();

    $roleId = trim($REQUEST_DATA['roleId']);
    
    if($roleId=='') {
      $roleId=0;  
    }

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $foundArray = CommonQueryManager::getInstance()->getStudentTeacher(''," AND r.roleId = $roleId AND emp.visibleToParent=1");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }

