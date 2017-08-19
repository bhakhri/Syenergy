<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE Class List
// Author : Prashant
// Created on : (20.05.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------



global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
$employeeReportsManager = EmployeeReportsManager::getInstance(); 

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

	$condition=''; 
	$date=date('Y-m-d');
	$timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');      
    $foundTimeTableType = $employeeReportsManager->getTimeTableType($timeTableLabelId);
	
	if($foundTimeTableType[0]['timeTableType']==DAILY_TIMETABLE){
	  $condition=' AND t.fromDate <="'.$date.'"';
	}
   /* if(trim($timeTableLabelId)=='') {
      $timeTableLabelId = 0;  
    }    
    
    if(trim($employeeId)=='') {
      $employeeId = 0;  
    }*/
    
    $condition .= " AND tt.timeTableLabelId='".$timeTableLabelId."' AND tt.employeeId = '".$employeeId."'";
    $orderBy = "className";
    
    $foundArray = $employeeReportsManager->getTimeTableTypeClass($condition,$orderBy);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

?>