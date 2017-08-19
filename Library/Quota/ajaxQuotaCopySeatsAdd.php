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
	 
    
    $mainClassId = $REQUEST_DATA['mainClassId']; 
    $copyClassId = $REQUEST_DATA['copyClassId'];   
    
    $errorMessage ='';
    if(trim($mainClassId) == '') {  
      $errorMessage = SELECT_CLASS;
    }
    
    $condition = " AND qs.classId = ".$mainClassId;
    $foundArray = QuotaManager::getInstance()->getSeatIntakeList($condition); 
    if(count($foundArray)==0) { 
      $errorMessage = NO_SEATS_COPY; 
    } 
    
    if (trim($errorMessage) == '') {
        //****************************************************************************************************************    
        //***********************************************STRAT TRANSCATION************************************************
        //****************************************************************************************************************
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
           $tcopyClassId = explode(',',$copyClassId); 
           $find=0;
           for($i=0; $i<count($tcopyClassId); $i++) {
              $condition = " AND cc1.classId = ".$tcopyClassId[$i];
              $foundArray = QuotaManager::getInstance()->getSeatList($condition);  
              
              if(count($foundArray)>0) {
                 echo CANNOT_COPY_SEATS." ".$foundArray[$i]['className'];  
                 die; 
              }
                  $tquotaId='';
                  /*
                  for($j=0; $j<count($foundArray); $j++) {
                     if($tquotaId=='') {
                       $tquotaId = $foundArray[$j]['quotaId'];    
                     }
                     else {  
                       $tquotaId .=",".$foundArray[$j]['quotaId'];  
                     }
                  }
                  */
                  $condition = " AND classId = ".$tcopyClassId[$i];
                  if($tquotaId!='') {
                     $condition .= " AND quotaId NOT IN (".$tquotaId.")";
                  }
                  
                  $returnStatus = QuotaManager::getInstance()->deleteSeatIntakes($condition);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  } 
                  $condition = '';
                  if($tquotaId!='') {
                     $condition = " AND qs.quotaId NOT IN (".$tquotaId.")";
                  }
                  $returnStatus = QuotaManager::getInstance()->addCopySeatIntakes($mainClassId,$tcopyClassId[$i],$condition);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
           } 
           //*****************************COMMIT TRANSACTION************************* 
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             $errorMessage = QUOTA_SEATS_COPY;
           }
           else {
             $errorMessage =  FAILURE;
           }    
        }
    }
    echo $errorMessage;

?>