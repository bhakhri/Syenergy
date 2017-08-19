<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Nishu Bindal
// Created on : (1.Mar.2012 )
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");   
$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','FeeHeadValuesNew');
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
    $foundArray = $feeHeadValuesManager->getFeeCycleHeadList($condition); 
    if(count($foundArray)==0) { 
      $errorMessage = FEE_HEAD_VALUE_NO_SEATS_COPY; 
    } 
    
    $condition = " AND fh.classId IN ($copyClassId) ";
    $foundArray = $feeHeadValuesManager->getFeeCycleClassList($condition); 
    $msg = FEE_HEAD_VALUE_CANNOT_COPY."\n\r";
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
               $foundArray = $feeHeadValuesManager->getFeeCycleHeadList($condition);  
              
               if(count($foundArray)>0) {
                 echo FEE_HEAD_VALUE_CANNOT_COPY." ".$foundArray[$i]['className'];  
                 die; 
               }
              
               $condition ='';
               $returnStatus = $feeHeadValuesManager->addCopyFeeHeadValue($mainClassId,$tcopyClassId[$i]);
               if($returnStatus === false) {
                 $errorMessage = FAILURE; 
                 die;
               } 
           } 
           //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             $errorMessage = FEE_HEAD_VALUE_COPY;
           }
           else {
             $errorMessage =  FAILURE;
           }    
        }
    }
    echo $errorMessage;

?>
