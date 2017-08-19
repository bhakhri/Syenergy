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
require_once(MODEL_PATH . "/QuotaManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

  
	global $sessionHandler;
	 
    $classId = $REQUEST_DATA['classId']; 
    $classSeatId = $REQUEST_DATA['classSeatId'];   
    $idNos  =   $REQUEST_DATA['idNos'];  
    
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
    $errorMessage ='';
    if(trim($classId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
    $str = '';
    if (trim($errorMessage) == '') {
       $totalValues = count($REQUEST_DATA['quotaId']);     
       $foundArray = QuotaManager::getInstance()->getSeatIntakeList(" AND qs.classId = '$classId' ");  
       
       for($i=0; $i<$totalValues; $i++) { 
          $tquotaId = $REQUEST_DATA['quotaId'][$i];
          $tseats = trim($REQUEST_DATA['totalSeat'][$i]); 
          $idNo = $REQUEST_DATA['idNos'][$i]; 
          for($j=0; $j<count($foundArray); $j++) {   
             if($foundArray[$j]['quotaId']==$tquotaId) {
               if(intval($foundArray[$j]['seatsAllocated'])!=-1 || intval($foundArray[$j]['seatsAllocated'])!=0) {
                 if(intval($foundArray[$j]['seatsAllocated']) > intval($tseats) ) {
                   //$errorMessage= LESS_THEN_SEATS."!~~!".($i+1);
                   $errorMessage= LESS_THEN_SEATS."!~~!".($idNo);
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
 
    
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              $totalValues = count($REQUEST_DATA['quotaId']);     
              
              $str = " AND classId= '$classId' ";
              $returnStatus =  QuotaManager::getInstance()->deleteSeatIntakes($str);
              if($returnStatus === false) {
                $errorMessage = FAILURE; 
                die;
              }
              if($totalValues == 0 ) {
                 $returnStatus =  QuotaManager::getInstance()->updateClassSeatIntakes('0',$classId);
                 if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                 }
              }
              else {
                  $str = '';
                  $totalSeats =0;
                  for($i = 0; $i < $totalValues; $i++) {
                    $quotaId = $REQUEST_DATA['quotaId'][$i];
                    $seats = $REQUEST_DATA['totalSeat'][$i];
                    if(!empty($str)) {
                      $str .= ',';
                    }                                            
                    $str .= "($classId, $quotaId, $seats,$instituteId,$sessionId)";
                    $totalSeats +=$seats; 
                  }
                  $returnStatus = QuotaManager::getInstance()->addSeatIntakes($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
                  $returnStatus =  QuotaManager::getInstance()->updateClassSeatIntakes($totalSeats,$classId);  
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
              }
                
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 $errorMessage = SUCCESS;
                 if($totalValues==0) {
                   $errorMessage = QUOTA_SLAB_DELETE_SUCCESSFULLY;  
                 }
                 else if($classSeatId==1) { 
                   $errorMessage = QUOTA_SLAB_UPDATE_SUCCESSFULLY;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
        }
    }
 
    echo $errorMessage;
 
// $History: ajaxQuotaSeatsAdd.php $
//

?>