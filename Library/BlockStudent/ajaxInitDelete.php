<?php
//-------------------------------------------------------
// Purpose: To delete country detail
//
// Author : Arvind Singh Rawat
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BlockStudent');
    define('ACCESS','delete');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/BlockStudentManager.inc.php");
    $blockStudentManager =BlockStudentManager::getInstance();
    
    $blockId = trim($REQUEST_DATA['blockId']);
    
    if($blockId=='') {
      $blockId=0;  
    }
  
    $returnStatus = $blockStudentManager->deleteStudent($blockId);
    if($returnStatus === false) {
       echo FAILURE;
    }
    else {
       echo DELETE;
    }
?>

