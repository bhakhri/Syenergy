<?php
//-------------------------------------------------------------------------------------
//  This File contains Validation and ajax function used in all degree.
//
// Author :PArveen Sharma
// Created on : 21-May-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    
    $batchId = $REQUEST_DATA['batchId'];     
    if($batchId=='') {
      $batchId = 0;
    }    
        
    global $sessionHandler;    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
    // Findout branch Name
   $field      = " degreeId, degreeName, degreeCode ";
   $table      = " degree ";
   $condition  = " WHERE 
                        degreeId IN (SELECT DISTINCT degreeId FROM class WHERE batchId = $batchId AND instituteId = $instituteId) 
                  ORDER BY 
                        degreeName ASC";                  
                        
   $degreeList = $studentManager->getSingleField($table, $field, $condition);
   echo json_encode($degreeList);          
    
?>