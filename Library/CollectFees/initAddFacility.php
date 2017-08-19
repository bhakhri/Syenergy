<?php
//-------------------------------------------------------
// Purpose: To store the records of fees receipt
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','CollectFees');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


$collectStudentFeeManager = CollectStudentFeeManager::getInstance();  
       global $sessionHandler;
       $queryDescription =''; 
    $studentId   = add_slashes(trim($REQUEST_DATA['studentId']));  
    $facilityType = add_slashes(trim($REQUEST_DATA['facilityType']));  
   
    $facilityClassId = $REQUEST_DATA['facilityClassId'];  
    $facilityAmt = $REQUEST_DATA['facilityAmt'];  
    $facilityConcession = $REQUEST_DATA['facilityConcession'];  
    $facilityComments = $REQUEST_DATA['facilityComments'];
    
    
   
    $str = '';
    for($i=0; $i<count($facilityClassId);$i++) {
       $classId = add_slashes(trim($facilityClassId[$i])); 
       $charges = add_slashes(trim($facilityAmt[$i]));
       $comments  = add_slashes(trim($facilityComments[$i]));
       if($charges != '') {
         if($str!='') {
           $str .=",";  
         }
         if($facilityType==3) {
            $str .= "('$classId','$studentId','$charges','$comments')"; 
         }  
         else {
           $concession = add_slashes(trim($facilityConcession[$i]));  
           $str .= "('$classId','$studentId','$charges','$concession','$facilityType','$comments')"; 
         }
       }
    }
   
    if($studentId=='' || $facilityType =='' || count($facilityClassId)==0) {
       echo 'Required parameter missing';  
       die; 
    } 
   
    //***********************************************STRAT TRANSCATION************************************************
    //****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
    
        $returnStatus = $collectStudentFeeManager->deleteFeeFacility($studentId,$facilityType);
        if($returnStatus === false) {
          echo FAILURE; 
          die;
        }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
        
        if($str!='') {
          $returnStatus = $collectStudentFeeManager->addFeeFacility($str,$facilityType);
          if($returnStatus === false) {
            echo FAILURE; 
            die;
          }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
        }
       
	    //*****************************COMMIT TRANSACTION************************* 
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
          ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			if($facilityType==1) {  
			  $auditTrialDescription = "Following Transport fees has been added:$facilityComments";
			}
	    	        else if($facilityType==2) {  
			  $auditTrialDescription = "Following Hostel fees has been added:$facilityComments";
                        }
	    	        else if($facilityType==3) {  
			  $auditTrialDescription = "Following Pending Dues has been added:$facilityComments";
                        }
			$type = FEES_FACILITY; //Fees facility added
            $queryDescription='';
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
          echo SUCCESS;  
        }
        else {
          echo FAILURE;  
        }    	
    }
?>
