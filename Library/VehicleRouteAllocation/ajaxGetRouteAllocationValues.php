<?php
//-------------------------------------------------------
// Purpose: To delete room detail
// Author : Nishu bindal
// Created on : (28.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleRouteAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
    
   
	if($REQUEST_DATA['busRouteStudentMappingId'] == ''){
		$errorMessage = "Required Parameter is Missing !!";
	}	
  
    if($errorMessage == ''){
		$isAllocation=$VehicleRouteAllocationManager->getIsAllocation(' WHERE busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId'] );
      
       if($isAllocation[0]['isAllocation']=='1'){
       //  echo "hhhhhhhhh";die;
        $ret=$VehicleRouteAllocationManager->checkStudentData(' WHERE busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId'] );
 		$foundArray=$VehicleRouteAllocationManager->getStudentVehicleRouteData(' AND brsm.busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);
      
       }
  else{
           $ret=$VehicleRouteAllocationManager->checkEmployeeData(' WHERE busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);
      $foundArray=$VehicleRouteAllocationManager->getEmployeeVehicleRouteData(' AND brsm.busRouteStudentMappingId='.$REQUEST_DATA['busRouteStudentMappingId']);		
    
     }
  
    if(is_array($foundArray) && count($foundArray)>0 ) {
			echo json_encode($foundArray[0]);
		}
        
		
	}	else{
		echo $errorMessage;
	}
   

?>
