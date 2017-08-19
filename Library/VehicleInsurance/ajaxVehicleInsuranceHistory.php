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
    define('MODULE','VehicleInsuranceReport');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleInsuranceManager.inc.php");
    $vehicleInsuranceManager = VehicleInsuranceManager::getInstance();

    /////////////////////////
    $busId = $REQUEST_DATA['vehicleNo'];
	$vehicleTypeId = $REQUEST_DATA['vehicleType'];
	
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
 /*   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (insuringCompanyName  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR insuringCompanyDetails LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }*/
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'insuringCompanyName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    //$totalArray = $vehicleInsuranceManager->getTotalVehicleInsurance($filter);
	$filter = " AND bi.busId =".$busId." AND bs.vehicleTypeId =".$vehicleTypeId;
    $vehicleInsuranceRecordArray = $vehicleInsuranceManager->getVehicleInsuranceHistory($filter,$limit,$orderBy);
    $cnt = count($vehicleInsuranceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $vehicleInsuranceRecordArray[$i]['lastInsuranceDate'] = utilityManager::formatDate($vehicleInsuranceRecordArray[$i]['lastInsuranceDate']);
		$vehicleInsuranceRecordArray[$i]['insuranceDueDate'] = utilityManager::formatDate($vehicleInsuranceRecordArray[$i]['insuranceDueDate']);
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$vehicleInsuranceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History:  $
//
//
?>