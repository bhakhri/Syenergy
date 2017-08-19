<?php
//-------------------------------------------------------
// THIS FILE IS USED TO fetch previous class of a class
// Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn();
    }
    else{
      UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache(); 
   

    require_once(MODEL_PATH . "/RegistrationForm/ChangeMentorManager.inc.php");
    $changeMentorManager = ChangeMentorManager::getInstance();
    
    
    $foundArray = $changeMentorManager->getCurrentMentorNew();
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>
