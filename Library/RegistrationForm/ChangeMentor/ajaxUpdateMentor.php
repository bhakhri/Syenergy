<?php
//----------------------------------------------------------------
// THIS FILE IS USED TO copy groups from one class to another class
// Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ChangeMentor');
	define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
   // require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
   
    require_once(MODEL_PATH . "/RegistrationForm/ChangeMentorManager.inc.php");
    $changeMentorManager = ChangeMentorManager::getInstance();
    
	$currentMentorId = trim($REQUEST_DATA['currentMentorId']);
	$newMentorId = trim($REQUEST_DATA['newMentorId']);
    $studentCheck = trim($REQUEST_DATA['studentCheck']);
	
    if($currentMentorId=='') {
      $currentMentorId='0';  
    }
    
    if($newMentorId=='') {
      $newMentorId=0;  
    }
    
    if($studentCheck=='') {
      $studentCheck=0;  
    }
    

	 //starting transaction
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		
        $return = $changeMentorManager->updateMentor($currentMentorId,$newMentorId,$studentCheck);
		if($return == false) {
		  echo FAILURE;
		  die;
		}
        
        /*  $return = $teacherManager->updateUserId($currentMentorId, $newMentorId,$studentCheck);
		    if($return == false) {
		      echo FAILURE;
		      die;
		    }
        */
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		  echo SUCCESS;
		  die;                                                                                                                       
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else {
		echo FAILURE;
		die;
	}

?>
