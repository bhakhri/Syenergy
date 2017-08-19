<?php 


//  This File calls to fetch student records
//
// Author :Harpreet
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	 
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();  

    
     $studentId= $sessionHandler->getSessionVariable('StudentId');
	  $classId= $sessionHandler->getSessionVariable('ClassId');
	
	
    if($studentId==''){
     $studentId=0;
    }
     
     $studentDetailArray = $studentInformationManager->getStudentHostelDetailsCheck($studentId,$classId); 	 
	 
     if($studentDetailArray !=''){
       echo json_encode($studentDetailArray); 
     }        
      else{       
         echo 0;          
     }
     
      
	
?>
