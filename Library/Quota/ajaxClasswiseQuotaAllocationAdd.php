<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
     
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	 
   
    $classId = $REQUEST_DATA['classId']; 
    $roundId = $REQUEST_DATA['roundId']; 
    $allocationDate = $REQUEST_DATA['allocationDate'];    
    
    $classAllocationId = $REQUEST_DATA['classAllocationId'];    
    
    $classAllocationId  = $REQUEST_DATA['classAllocationId'];  
    
    $quotaId = $REQUEST_DATA['quotaId'];
    $newAllocatedSeats = $REQUEST_DATA['newAllocatedSeats'];    
    
    $errorMessage ='';

    if($quotaId=='') {
      $quotaId = -1;  
    }
    
    if($classAllocationId=='') {
      $classAllocationId=-1;  
    }
    
    if(trim($classId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
    if(trim($roundId) == '') {  
      $errorMessage = SELECT_ROUND;
    }
    
    if(trim($quotaId) == '') {  
      $errorMessage = SELECT_QUOTA;
    }

    
    if (trim($errorMessage) == '') {
        
           //****************************************************************************************************************    
           //***********************************************STRAT TOTAL SEAT CHHECK******************************************
           //****************************************************************************************************************
                $tquotaId = implode(',',$quotaId);
                if($tquotaId=='') {
                  $tquotaId=-1; 
                }
                $foundArray = QuotaManager::getInstance()->getSeatIntakeList(" AND qs.classId = '$classId' AND qs.quotaId IN ($tquotaId)");
                for($i=0;$i<count($foundArray);$i++) {
                  for($j=0;$j<count($quotaId);$j++) {  
                     if( $quotaId[$j]==$foundArray[$i]['quotaId'] && $newAllocatedSeats[$j] > $foundArray[$i]['seats']) {
                       echo "Current allocation of seats cannot be greater than Total Seats!~~!".$foundArray[$i]['quotaId'];
                       die;
                     }                                   
                  }                       
                }
                
                $condition =  " AND cc1.classId = '$classId' AND cc1.allocationDate < '$allocationDate'";
                $condition1 =  " AND cc2.quotaId IN ($tquotaId) ";
                $foundArray = QuotaManager::getInstance()->getClassQuotaAllocation($condition,$condition1);
                for($i=0;$i<count($foundArray);$i++) {
                  for($j=0;$j<count($quotaId);$j++) {  
                     if($quotaId[$j]==$foundArray[$i]['quotaId']) {
                       $chk=0;  
                       //if(intval(trim($newAllocatedSeats[$j])) > intval(trim($foundArray[$i]['seatsAllocated']))) {
                       //   echo "New allocation seats not greater than allocated seats!~~!".$foundArray[$i]['quotaId']; 
                       //   $chk=1;
                       //}
                       if(intval(trim($newAllocatedSeats[$j])) < intval(trim($foundArray[$i]['seatsAllocated']))) {
                          echo "Current allocation of seats cannot be less than previous allocation of seats!~~!".$foundArray[$i]['quotaId'];  
                          $chk=1;
                       }
                       if($chk==1) {
                         die;  
                       }
                       //echo " $i == $j  ===== ".$newAllocatedSeats[$j]."  ".$foundArray[$i]['seatsAllocated']."  $chk\n";
                     }                                   
                  }
                }
                
                
                $condition =  " AND cc1.classId = '$classId'  AND cc1.allocationDate > '$allocationDate'";
                $condition1 =  " AND cc2.quotaId IN ($tquotaId) ";
                $foundArray = QuotaManager::getInstance()->getClassQuotaAllocation($condition,$condition1);
                for($i=0;$i<count($foundArray);$i++) {
                  for($j=0;$j<count($quotaId);$j++) {  
                     if( $quotaId[$j]==$foundArray[$i]['quotaId']) {
                       $chk=0;  
                       if($foundArray[$i]['seatsAllocated']!=0) {
                           if(intval(trim($newAllocatedSeats[$j])) > intval(trim($foundArray[$i]['seatsAllocated']))) {
                              $chk=1;
                           }
                           if($chk==1) {
                             echo "Current allocation of seats cannot be More than future allocation of seats!~~!".$foundArray[$i]['quotaId'];   
                             die;  
                           }
                       }
                       //echo " $i == $j  ===== ".$newAllocatedSeats[$j]."  ".$foundArray[$i]['seatsAllocated']."  $chk\n";  
                     }                                   
                  }
                }   
                
           //****************************************************************************************************************    
           //***********************************************END TOTAL SEAT CHHECK************************************************
           //****************************************************************************************************************
        
        
        
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {

              $tableName =" class_quota_allocation_details";
              $condition =" classAllocationId = $classAllocationId"; 
              $returnStatus = QuotaManager::getInstance()->deleteQuotaAllocation($tableName, $condition);
              if($returnStatus === false) {
                $errorMessage = FAILURE;
                die;
              }
              
              $tableName="class_quota_allocation";
              $condition =" sessionId = $sessionId AND instituteId = $instituteId AND classAllocationId = $classAllocationId";
              $returnStatus = QuotaManager::getInstance()->deleteQuotaAllocation($tableName, $condition);
              if($returnStatus === false) {
                $errorMessage = FAILURE;
                die;
              }
              
              $totalValues = count($quotaId); 
              if($totalValues>0) {
                $returnStatus = QuotaManager::getInstance()->addClassQuotaAllocation($classId, $roundId, $allocationDate) ;
                if($returnStatus === false) {
                   $errorMessage = FAILURE;
                   die;
                }    
                $id=SystemDatabaseManager::getInstance()->lastInsertId();  
                for($i=0; $i<$totalValues; $i++) {
                   $returnStatus = QuotaManager::getInstance()->addClassQuotaAllocationDetails($id, $quotaId[$i], $newAllocatedSeats[$i]);
                   if($returnStatus === false) {
                     $errorMessage = FAILURE;
                     die;
                   }
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