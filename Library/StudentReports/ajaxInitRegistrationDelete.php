<?php
//-------------------------------------------------------
// Purpose: To delete Registration Delete
//
// Author : Parveen Sharma
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    
    $registrationId = $REQUEST_DATA['id'];
    
    if($registrationId=='') {
      $registrationId=-1;  
    }
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");  
    $studentManager = StudentManager::getInstance();
        
        
    if(SystemDatabaseManager::getInstance()->startTransaction()) {           
        
        $returnStatus = $studentManager->deleteStudentRegistration("student_registration_detail"," WHERE registrationId=$registrationId");
        if($returnStatus===false) {
           echo FAILURE;
           die;
        }
        $returnStatus = $studentManager->deleteStudentRegistration("student_registration_master"," WHERE registrationId=$registrationId");  
        if($returnStatus===false) {
           echo FAILURE;
           die;
        }
          
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
           $errorMessage = DELETE;
        }
        else {
           echo FAILURE;
           die;
        } 
    } 
    
    echo $errorMessage;
    

// $History: ajaxInitRegistrationDelete.php $    
//
?>

