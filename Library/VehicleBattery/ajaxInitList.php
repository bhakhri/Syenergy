<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (25.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleBattery');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleBatteryManager.inc.php");
    $vehicleBatteryManager = VehicleBatteryManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (b.busNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bb.batteryNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bb.batteryMake LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $vehicleBatteryManager->getTotalVehicleBattery($filter);
    $vehicleBatteryRecordArray = $vehicleBatteryManager->getVehicleBatteryList($filter,$limit,$orderBy);
	
	$cnt = count($vehicleBatteryRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $vehicleBatteryRecordArray[$i]['warrantyDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['warrantyDate']);
		if($vehicleBatteryRecordArray[$i]['replacementDate'] != '---') {
			$vehicleBatteryRecordArray[$i]['replacementDate'] = UtilityManager::formatDate($vehicleBatteryRecordArray[$i]['replacementDate']);
		}
		
		$valueArray = array_merge(array('action' => $vehicleBatteryRecordArray[$i]['batteryId'] , 'srNo' => ($records+$i+1) ),$vehicleBatteryRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/VehicleBattery
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:22p
//Created in $/Leap/Source/Library/VehicleBattery
//new ajax files for vehicle battery
//
//
?>