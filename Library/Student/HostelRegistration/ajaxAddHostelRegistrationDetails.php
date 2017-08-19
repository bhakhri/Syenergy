<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    

 require_once(BL_PATH.'/HtmlFunctions.inc.php');
 
  
require_once(MODEL_PATH . "/Student/HostelRegistrationManager.inc.php");
   $hostelRegistrationManager = HostelRegistrationManager::getInstance();  

    
    
    global $sessionHandler;
    $studentId= $sessionHandler->getSessionVariable('StudentId');
	$classId= $sessionHandler->getSessionVariable('ClassId');
	
	//$classId= $REQUEST_DATA['classId'];
	$isCancel = $REQUEST_DATA['isCancel'];
	
	$roomTypeId = $REQUEST_DATA['roomTypeId'];

	if($studentId==''){
	 $studentId=0;
	}
	
	if($isCancel=='1'){
	 $updateDetailArray = $hostelRegistrationManager->deletePreviousHostelRegistration($studentId,$classId);
	  if($updateDetailArray===true){
		echo "Your request for hostel registration is cancelled succesfully";
		die;
	  }	
		}else{
			$registrationPrevDetailArray = $hostelRegistrationManager->getPreviousHostelRegistration($studentId,$classId);
			if($registrationPrevDetailArray[0]!=''){
				echo "You are already registered";	
			 die;		
				
			}
		
			
		$registrationDetailArray = $hostelRegistrationManager->getStudentHostelRegistration($studentId,$classId,$roomTypeId);
	 
		 if($registrationDetailArray===true){	 	
			echo "Your application is confirmed for registration ";	
			 die;	
		 }else{	 	
			echo "Some thing went wrong";
			die;
		 }		
		
	}
	 
  
 
?>
