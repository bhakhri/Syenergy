<?php
//-------------------------------------------------------
// Purpose: To delete Vehicle Route Allocation
// Author : Nishu Bindal
// Created on : (05.April.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteAllocation');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();

    if(!isset($REQUEST_DATA['busRouteStudentMappingId']) || trim($REQUEST_DATA['busRouteStudentMappingId']) == ''){
      $errorMessage = "Required Parameter is missing.";
      echo $errorMessage;
      die;   
    }

    $studentId = '0';
    $classId = '0';
    $busRouteStudentMappingId = $REQUEST_DATA['busRouteStudentMappingId'];

    if($busRouteStudentMappingId=='') {
      $busRouteStudentMappingId=0;  
    }
    
    // Fetch StudentId and ClassId
    $condition = " AND brsm.busRouteStudentMappingId = '".$busRouteStudentMappingId."'";
    $ret1=$VehicleRouteAllocationManager->getStudentClassData($condition);
    if(count($ret1) >0 ) {    
       $studentId = $ret1[0]['studentId'];
       $classId = $ret1[0]['classId'];
    }
    
    if($studentId=='') {
      $studentId=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }
    

    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        $condition = " AND studentId = '$studentId' AND classId = '$classId' "; 
		$ret1=$VehicleRouteAllocationManager->getTransportPayement($condition);
		if($ret1[0]['totalRecords'] > 0)  {    
		  echo DEPENDENCY_CONSTRAINT; 
		  die;
		}
        
        $result1 =$VehicleRouteAllocationManager->updateFeeReceiptMaster($studentId,$classId);
        if($result1===false){
          echo DEPENDENCY_CONSTRAINT;
          die;
        }
        
	    $result1 =$VehicleRouteAllocationManager->updateStudentTableData($studentId);
		if($result1===false){
		  echo DEPENDENCY_CONSTRAINT;
		  die;
		}
		
        $result = $VehicleRouteAllocationManager->deleteRootAllocation($busRouteStudentMappingId);
        if($result===false){
          echo DEPENDENCY_CONSTRAINT;
          die;
        }
        
		if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		  echo DELETE;    
		}
		else {
		  echo FAILURE;
		  die;    
		} 
   }
 
?>

