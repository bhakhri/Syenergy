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
require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");   
$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeadValues');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
global $sessionHandler;
$queryDescription ='';

    $classId  =   $REQUEST_DATA['classId'];
	$editFeeHeadId  =   $REQUEST_DATA['editFeeHeadId']; 
    $feeHeadId  =  $REQUEST_DATA['feeHeadId']; 
    $quotaId  =  $REQUEST_DATA['quotaId']; 
    $isLeet  =  $REQUEST_DATA['isLeet']; 
    $idNos  =   $REQUEST_DATA['idNos']; 
    $totalAmount  =  $REQUEST_DATA['totalAmount']; 
    
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$auditTrialDescription = "Fee head value has been created for following class:";
	
	
	$classNameArray = $commonQueryManager->getClassName($classId);
	$classNameList = UtilityManager::makeCSList($classNameArray,'className');
	$auditTrialDescription .= $classNameList;
	
    
    $errorMessage ='';

    if(trim($classId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
   
    $str = '';
    // Validation message added
   /*
    if (trim($errorMessage) == '') {
       $totalValues = count($REQUEST_DATA['quotaId']);     
       $foundArray = QuotaManager::getInstance()->getSeatIntakeList(" AND qs.classId = '$classId' ");  
       
       for($i=0; $i<$totalValues; $i++) { 
          $tquotaId = $REQUEST_DATA['quotaId'][$i];
          $tseats = trim($REQUEST_DATA['totalSeat'][$i]); 
          for($j=0; $j<count($foundArray); $j++) {   
             if($foundArray[$j]['quotaId']==$tquotaId) {
               if(intval($foundArray[$j]['seatsAllocated'])!=-1 || intval($foundArray[$j]['seatsAllocated'])!=0) {
                 if(intval($foundArray[$j]['seatsAllocated']) > intval($tseats) ) {
                   $errorMessage= LESS_THEN_SEATS."!~~!".($i+1);
                   break;
                 }
               }
             }
          }
          if($errorMessage!='') {
            break; 
          }
       }
    }
   */
   
    $msg='';
    $totalValues = count($REQUEST_DATA['quotaId']);  
    for($i = 0; $i < $totalValues; $i++) {  
        $quotaId = $REQUEST_DATA['quotaId'][$i];
        $feeHeadId = $REQUEST_DATA['feeHeadId'][$i];
        $isLeet = $REQUEST_DATA['isLeet'][$i];
        $idNo = $REQUEST_DATA['idNos'][$i];
        $c=0; 
        $c1=0;
        $idBoth ='';
        for($j=0; $j<$totalValues; $j++) {   
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==1) {
             $c++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==2) {
             $c++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])=='all' && $REQUEST_DATA['isLeet'][$j]==3) {
             $c++;
             $idBoth=$REQUEST_DATA['idNos'][$j]; 
           }
           if($c==3) {
             break;  
           }
           
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])==$quotaId && ($REQUEST_DATA['isLeet'][$j]==1 || $REQUEST_DATA['isLeet'][$j]==2) ) {
             $c1++;
           }
           if($REQUEST_DATA['feeHeadId'][$j]==$feeHeadId && strtolower($REQUEST_DATA['quotaId'][$j])==$quotaId && $REQUEST_DATA['isLeet'][$j]==3) {
             $c1++;
             $idBoth=$REQUEST_DATA['idNos'][$j];
           }
           if($c1==2) {
             break;  
           }
        }
        if($c==3 || $c1==2) {
          if($msg!='') {
            $msg .="!~!";  
          }  
          $msg .= $idBoth;
        }
    }
    
    if($msg!='') {
      echo FEE_HEAD_VALUE_NOT_APPLICABLE_TO_BOTH.'!~!'.$msg;
      die;
    }
   
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
               
               $str = '';
               $returnStatus = $feeHeadValuesManager->deleteFeeCylceHeadValue($classId);
               if($returnStatus === false) {
                  $errorMessage = FAILURE; 
                  die;
               } 
	       $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
               
               $str='';
               for($i = 0; $i < $totalValues; $i++) {
                  $quotaId = $REQUEST_DATA['quotaId'][$i];
                  $feeHeadId = $REQUEST_DATA['feeHeadId'][$i];
                  $isLeet = $REQUEST_DATA['isLeet'][$i];
                  $feeHeadAmount = $REQUEST_DATA['totalAmount'][$i];   
                  if(strtolower($quotaId)=='all') {
                    $quotaId = 'null'; 
                  }
                  if($str!='') {
                    $str .=",";  
                  }
                  $str .= "($classId,$feeHeadId,$quotaId,$isLeet,$feeHeadAmount)";
               }
               
               if($totalValues!=0) {
                  $returnStatus = $feeHeadValuesManager->addFeeCylceHeadValue($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
 	          $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
               }

              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		 ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
  		   $type = FEES_HEAD_VALUES_ARE_CREATED; //Fee Head value is Created
		   $returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
		   if($returnStatus == false) {
		      echo  "Error while saving data for audit trail";
		      die;
		   } 
		 ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                 $errorMessage = FEE_HEAD_VALUE_ADDED_SUCCESSFULLY;
                 if($totalValues==0) {
                   $errorMessage = FEE_HEAD_VALUE_DELETE_SUCCESSFULLY;  
                 }
                 else if($editFeeHeadId==1) { 
                   $errorMessage = FEE_HEAD_VALUE_UPDATED_SUCCESSFULLY;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
        }
    }
 
    echo $errorMessage;
 ?>
