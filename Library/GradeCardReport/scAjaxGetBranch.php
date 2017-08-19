<?php
//-------------------------------------------------------------------------------------
//  This File contains Validation and ajax function used in all degree.
//
// Author :PArveen Sharma
// Created on : 21-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
	
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();  
    
    require_once(MODEL_PATH . "/StudentManager.inc.php"); 
    
    $studentManager = StudentManager::getInstance();
    
    $degreeId = $REQUEST_DATA['degreeId'];     
    $batchId = $REQUEST_DATA['batchId'];  
    
    if($degreeId=='') {
      $degreeId = 0;
    }    
 
    if($batchId=='') {
      $batchId = 0;
    }    
       
    global $sessionHandler;    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
    // Findout branch Name
   $field      = " branchId, branchName, branchCode ";
   $table      = " branch ";
   $condition  = " WHERE 
                        branchId IN (SELECT 
                                            DISTINCT branchId FROM class 
                                     WHERE 
                                            batchId  = $batchId AND 
                                            degreeId = $degreeId AND 
                                            instituteId = $instituteId) 
                  ORDER BY 
                        branchCode ASC";                  
                        
   $degreeList = $studentManager->getSingleField($table, $field, $condition);
    echo json_encode($degreeList);          
    
?>