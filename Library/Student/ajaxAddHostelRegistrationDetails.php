<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();  
 require_once(BL_PATH.'/HtmlFunctions.inc.php');
    
    global $sessionHandler;
    $studentId= $sessionHandler->getSessionVariable('StudentId');
	$classId= $sessionHandler->getSessionVariable('ClassId');
	
	//$classId= $REQUEST_DATA['classId'];
	$isCancel = $REQUEST_DATA['isCancel'];
	
	
	if($studentId==''){
	 $studentId=0;
	}
	
	if($isCancel=='1'){
	 $updateDetailArray = $studentInformationManager->deletePreviousHostelRegistration($studentId,$classId);
	  if($updateDetailArray===true){
		echo "Your request for hostel registration is cancelled succesfully";
		die;
	  }	
		}else{
		$registrationDetailArray = $studentInformationManager->getStudentHostelRegistration($studentId,$classId);
	 
		 if($registrationDetailArray===true){	 	
			echo "Your application is confirmed for registration ";	
			 die;	
		 }else{	 	
			echo "Some thing went wrong";
		 }		
		
	}
	 
  
 
?>
