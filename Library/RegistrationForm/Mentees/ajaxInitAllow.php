<?php 
//--------------------------------------------------------------------------
//  This File calls addFunction used in adding FEE HEAD VALUES Records
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');  
    define('ACCESS','edit');
    define("MANAGEMENT_ACCESS",1);
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache(); 
  
   require_once(MODEL_PATH . "/RegistrationForm/MenteesManager.inc.php");
    $menteesManager  = MenteesManager::getInstance();

  
    $isAllowRegistration = trim($REQUEST_DATA['isAllowRegistration']);
    $studentChk  = trim($REQUEST_DATA['studentChk']);
    
    if($isAllowRegistration=='') {
      $isAllowRegistration='0';  
    }
    
    if($studentChk=='') {
      $studentChk='0';  
    }
    $condition = " mentorshipId IN (".$studentChk.")";
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {             
	    $returnStatus = $menteesManager->addMentorStudentStatus($isAllowRegistration,$condition);
        if($returnStatus === false) {
          echo FAILURE;
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
          echo SUCCESS;         
        }
    }
     
?>
