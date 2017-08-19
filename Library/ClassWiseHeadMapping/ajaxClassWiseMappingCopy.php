<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/ClassWiseHeadMappingManager.inc.php");   
$classWiseHeadValuesManager = ClassWiseHeadValuesManager::getInstance();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
 
define('MODULE','ClassWiseHeadMapping'); 
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
	global $sessionHandler;
	$queryDescription =''; 
	 
    //$feeCycleId = $REQUEST_DATA['copyFeeCycleId']; 
    
    $mainClassId = $REQUEST_DATA['souClassId']; 
    $copyClassId = $REQUEST_DATA['copyClassId'];   
    
    $errorMessage ='';
    if(trim($mainClassId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
    $condition = " fh.classId = $mainClassId ";
    $foundArray = $classWiseHeadValuesManager->getFeeConcessionHeadList($condition); 
    if(count($foundArray)==0) { 
      $errorMessage = FEE_CONCESSION_NO_SEATS_COPY; 
    } 
    
    $condition = " AND fh.classId IN ($copyClassId) ";
    $foundArray = $classWiseHeadValuesManager->getFeeConcessionClassList($condition); 
    $msg = FEE_CONCESSION_VALUE_CANNOT_COPY."\n\r";
    if(count($foundArray)>0) {
      for($i=0; $i < count($foundArray); $i++) {  
        if($i!=0) {
          $msg .=", ";  
        }  
        $msg .= $foundArray[$i]['className'];  
      }
      echo $msg;
      die; 
    }
    
    if (trim($errorMessage) == '') {
        //****************************************************************************************************************    
        //***********************************************STRAT TRANSCATION************************************************
        //****************************************************************************************************************
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
           $tcopyClassId = explode(',',$copyClassId); 
           $find=0;
           for($i=0; $i<count($tcopyClassId); $i++) {
               $condition = " fh.classId = ".$tcopyClassId[$i];
               $foundArray = $classWiseHeadValuesManager->getFeeConcessionHeadList($condition);  
              
               if(count($foundArray)>0) {
                 echo FEE_CONCESSION_VALUE_CANNOT_COPY." ".$foundArray[$i]['className'];  
                 die; 
               }
              
               $condition ='';
               $returnStatus = $classWiseHeadValuesManager->addCopyFeeConcessionValue($mainClassId,$tcopyClassId[$i]);
               if($returnStatus === false) {
                 $errorMessage = FAILURE; 
                 die;
               } 
		
           } 
           //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
		  $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
		  $type =FEES_CONCESSION_VALUE_COPIED; 
		  $auditTrialDescription = "Fee Concession Value has been copied to class ";
		  $classNameArray = $commonQueryManager->getClassName($copyClassId);
	          $classNameList = UtilityManager::makeCSList($classNameArray,'className');
		  $auditTrialDescription .= $classNameList;
		  $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		  if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		  } 
		  ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
             $errorMessage = FEE_CONCESSION_VALUE_COPY;
           }
           else {
             $errorMessage =  FAILURE;
           }    
        }
    }
    echo $errorMessage;

?>
