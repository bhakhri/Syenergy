<?php 
//  This File calls changes the password used in CHANGE PASSWORD Records
//
// Author :Jaineesh
// Created on : 09-Sept-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php"); 
    $studentManager = StudentManager::getInstance(); 
    
    $pollFind="-1"; 
    $pollFoundArray = $studentManager->getCheckPoll(); 
    if(is_array($pollFoundArray) && count($pollFoundArray)>0 ) {    
      if($pollFoundArray[0]['totalRecord'] > 0) {
         $pollFind = "1"; 
      }
    }
    echo $pollFind;
?>