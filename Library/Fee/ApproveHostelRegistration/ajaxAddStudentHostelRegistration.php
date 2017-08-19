<?php
//-------------------------------------------------------
// Purpose: To show online payment records
// Author :harpreet kaur
// Created on : 13-may-2013
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;      
    $roleId = $sessionHandler->getSessionVariable('RoleId');     
   
      UtilityManager::ifNotLoggedIn(true);
      UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Fee/ApproveHostelRegistrationManager.inc.php");   
    $approveHostelRegistrationManager = ApproveHostelRegistrationManager::getInstance();
 
 $commonRegistrationStatusArray = $REQUEST_DATA['commonRegistrationStatus'];
 $reasonArray = $REQUEST_DATA['reason'];
 $chbArray = $REQUEST_DATA['chb'];
  $userId = $sessionHandler->getSessionVariable('UserId');
  
  
 if(SystemDatabaseManager::getInstance()->startTransaction()) {
                
	for($i=0;$i<=count($commonRegistrationStatusArray);$i++){
		$chbIdsArray  = explode('~',$chbArray[$i]);
		$studentId = $chbIdsArray[0];
		$classId = $chbIdsArray[1];
		
	
		if($studentId=='' || $classId=='' ||$reasonArray[$i] == ''){		
			break;
		}else{
			//add registration of student
			$strQuerry =  " registrationStatus = '$commonRegistrationStatusArray[$i]',  
			     			 wardenComments ='$reasonArray[$i]', 
			     			 wardenCommentDate =now(),
			     			 userId = '$userId'";
							 
			$hostelCondition ="studentId = '$studentId' AND classId = '$classId'";
	
		  $hostelRecordArray = $approveHostelRegistrationManager->getAddStudentHostelRegistration($strQuerry,$hostelCondition);
		   if($hostelRecordArray===false) {
	        echo FAILURE;
     		  die; 
	       }
		   
		}		
	
	 }
	 if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	 	 echo SUCCESS ;
			                
	     }  
 }	
	  die;        			          
     
 
  ?>
