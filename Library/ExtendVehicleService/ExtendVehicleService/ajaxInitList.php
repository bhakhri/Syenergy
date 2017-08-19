<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ExtendVehicleServiceManager.inc.php");
    $extendVehicleServiceManager = ExtendVehicleServiceManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (b.busNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busId';
    
     $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $extendVehicleServiceManager->getTotalExtendedVehicleServices($filter);
	
    $extendedVehicleServiceRecordArray = $extendVehicleServiceManager->getExtendedVehicleServicesList($filter,$limit,$orderBy);
	
	//$condition = "AND bs.done = 1";
	//$vehicleServiceDoneRecordArray = $extendVehicleServiceManager->getDoneVehicleServicesList($condition,$orderBy);
	/*print_r($vehicleServiceDoneRecordArray);
	echo(count($vehicleServiceDoneRecordArray));
	die('line-->'.__LINE__);*/
    $cnt = count($extendedVehicleServiceRecordArray);
	
	
    for($i=0;$i<$cnt;$i++) {
		//$doneFreeServices = $vehicleServiceDoneRecordArray[$i]['doneFreeServices'];
        $valueArray = array_merge(array('action' => $extendedVehicleServiceRecordArray[$i]['serviceId'] , 'srNo' => ($records+$i+1)), $extendedVehicleServiceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalFreeServices'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:52p
//Created in $/Leap/Source/Library/ExtendVehicleService
//new files to add extend vehicle service
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/VehicleAccident
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:03p
//Created in $/Leap/Source/Library/VehicleAccident
//new ajax files for add, edit & delete for vehicle accident
//
//
?>