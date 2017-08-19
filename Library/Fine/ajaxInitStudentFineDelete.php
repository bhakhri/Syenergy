<?php
//-------------------------------------------------------
// Purpose: To delete fine category  detail
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineStudentMaster');
    define('ACCESS','delete');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();
    
    if(!isset($REQUEST_DATA['fineStudentId']) || trim($REQUEST_DATA['fineStudentId']) == '') {
       $errorMessage = 'Invalid Student Fine';
       die;
    }
    
    $fineStudentId = trim($REQUEST_DATA['fineStudentId']);
    if($fineStudentId=='') {
      $fineStudentId= 0; 
    }
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {   
       $returnStatus = $fineStudentManager->deleteFineStudent($fineStudentId); 
       if($returnStatus === false) {
         echo FAILURE;
         die;
       }    
       if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
         echo DELETE;     
       }  
    } 
?>