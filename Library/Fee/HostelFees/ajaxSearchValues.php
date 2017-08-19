<?php
//--------------------------------------------------------  
//It contains the time table 
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Fee/HostelFeeManager.inc.php");   
    $hostelFeeManager = HostelFeeManager::getInstance();
    
    $classStatus = $REQUEST_DATA['classStatus'];
    $searchMode = $REQUEST_DATA['searchMode'];
    $degreeId = $REQUEST_DATA['degreeId']; 
    $branchId = $REQUEST_DATA['branchId']; 
    $batchId = $REQUEST_DATA['batchId']; 
    
    if($classStatus=='') {
      $classStatus='-1'; 
    }
    
    
    if($degreeId=='all') {
      $degreeId='';  
    }
    
    if($branchId=='all') {
      $branchId='';  
    }
    
    if($batchId=='all') {
      $batchId='';  
    }
    
    $condition = " AND c.isActive = '$classStatus' ";
    
    // Degree 
    $degreeArray = array();
    if($searchMode=='all') {
      $fieldName = " DISTINCT CONCAT(deg.degreeId,',',ii.instituteId) AS degreeId1, CONCAT(deg.degreeCode,' (',ii.instituteCode,')') AS degreeCode1 ";
      $degreeArray =  $hostelFeeManager->getAllSearchValue($fieldName,$condition);
    }
    
    // Branch
    $branchArray = array();
    if($searchMode=='all' || $searchMode=='Degree') {
      $condition1 = $condition;
      if($searchMode!='all' ) {
        if($degreeId!='') {
          $condition1 .= " AND CONCAT(c.degreeId,',',c.instituteId) = '$degreeId' ";   
        }  
        $searchMode = "Branch";
      }  
      else {
        $searchMode = "all";     
      }
      $fieldName = " DISTINCT  CONCAT(br.branchId,',',ii.instituteId) AS branchId1, CONCAT(br.branchCode,' (',ii.instituteCode,')') AS branchCode1 ";
      $branchArray =  $hostelFeeManager->getAllSearchValue($fieldName,$condition1);
      
    }
    
    
    // Batch
    $batchArray = Array();
    if($searchMode=='all' || $searchMode=='Branch') {
      $condition1 = $condition;
      if($searchMode!='all' ) {
        if($degreeId!='') {
          $condition1 .= " AND CONCAT(c.degreeId,',',c.instituteId) = '$degreeId' ";   
        }  
        if($branchId!='') {
          $condition1 .= " AND CONCAT(c.branchId,',',c.instituteId) = '$branchId' ";   
        }  
        $searchMode = "Batch";
      }  
      else {
        $searchMode = "all";     
      }    
      $fieldName = " DISTINCT CONCAT(bat.batchId,',',ii.instituteId) AS batchId1, bat.batchName ";
      $batchArray =  $hostelFeeManager->getAllSearchValue($fieldName,$condition1);
    }
    
    
    // Class
    $classArray = Array();
    if($searchMode=='all' || $searchMode=='Batch') {
      $condition1 = $condition;
      if($searchMode!='all' ) {
        if($degreeId!='') {
          $condition1 .= " AND CONCAT(c.degreeId,',',c.instituteId) = '$degreeId' ";   
        }  
        if($branchId!='') {
          $condition1 .= " AND CONCAT(c.branchId,',',c.instituteId) = '$branchId' ";   
        }  
        if($batchId!='') {
          $condition1 .= " AND CONCAT(c.batchId,',',c.instituteId) = '$batchId' ";   
        }  
      }      
      $fieldName = " DISTINCT c.classId, 
                                IF(isActive=1,CONCAT(className,' (Active)'),
                                    IF(isActive=2,CONCAT(className,' (Future)'),
                                      IF(isActive=3,CONCAT(className,' (Past)'),
                                         IF(isActive=4,CONCAT(className,' (Unused)'),'')))) AS className ";
      $classArray =  $hostelFeeManager->getAllSearchValue($fieldName,$condition1);
      $searchMode = "all";
    }
      
    echo json_encode($degreeArray).'!~!!~!'.json_encode($branchArray).'!~!!~!'.json_encode($batchArray).'!~!!~!'.json_encode($classArray);

?>