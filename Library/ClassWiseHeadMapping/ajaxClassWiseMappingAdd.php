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
	$editFeeHeadId  =   $REQUEST_DATA['editFeeHeadId']; 
    $idNos  =   $REQUEST_DATA['idNos']; 

    $classId  =   $REQUEST_DATA['classId'];
	$categoryId = $REQUEST_DATA['categoryId']; 
    $feeHeadId  =  $REQUEST_DATA['feeHeadId']; 
    $isLeet  =  $REQUEST_DATA['isLeet']; 
	$concessionType  =  $REQUEST_DATA['isConcessionType']; 
	$concessionAmount = $REQUEST_DATA['totalAmount']; 
    

    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$auditTrialDescription = "Fee Concession Category has been created for following class:";
	
	
	$classNameArray = $commonQueryManager->getClassName($classId);
	$classNameList = UtilityManager::makeCSList($classNameArray,'className');
	$auditTrialDescription .= $classNameList;
	
    
    $errorMessage ='';

    if(trim($classId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
   
    $str = '';
   
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
               
               $str = '';
               $returnStatus = $classWiseHeadValuesManager->deleteFeeCylceHeadValue($classId);
               if($returnStatus === false) {
                  $errorMessage = FAILURE; 
                  die;
               } $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
               
			   $totalValues = count($idNos);
			 
               $str='';
               for($i = 0; $i < $totalValues; $i++) {
                  $tfeeHeadId = $feeHeadId[$i];
                  $tcategoryId = $categoryId[$i];
                  $tisLeet = $isLeet[$i];
                  $tconcessionAmount = $concessionAmount[$i];   
				  $tconcessionType = $concessionType[$i];
                  if(strtolower($tfeeHeadId)=='all') {
                    $tfeeHeadId = NULL; 
                  }
                  if($str!='') {
                    $str .=",";  
                  }
                  $str .= " ($classId,$tcategoryId,$tfeeHeadId,$tisLeet,$tconcessionType,$tconcessionAmount)";
               }
               
			   if($str!='') {
                  $returnStatus = $classWiseHeadValuesManager->addFeeCylceHeadValue($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription'); 
			     
			   }

              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		 ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
				  $type = FEES_CONCESSION_CATAGORY_ADDED; //Fee Head value is Created
				  $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
				  if($returnStatus == false) {
					echo  "Error while saving data for audit trail";
					die;
				  } 
				  ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                 $errorMessage = DEFINE_CONCESSION_VALUE_ADDED;
                 if($totalValues==0) {
                   $errorMessage = DEFINE_CONCESSION_VALUE_DELETE;  
                 }
                 else if($editFeeHeadId==1) { 
                   $errorMessage = DEFINE_CONCESSION_VALUE_UPDATED;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
        }
    }
 
    echo $errorMessage;
 ?>
