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
    define('MODULE','VehicleTypeMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleTypeManager.inc.php");
    $vehicleTypeManager = VehicleTypeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (vehicleType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR mainTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR spareTyres LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'vehicleType';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $vehicleTypeManager->getTotalVehicleType($filter);
    $vehicleTypeRecordArray = $vehicleTypeManager->getVehicleTypeList($filter,$limit,$orderBy);
    $cnt = count($vehicleTypeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $valueArray = array_merge(array('action' => $vehicleTypeRecordArray[$i]['vehicleTypeId'] , 'srNo' => ($records+$i+1) ),$vehicleTypeRecordArray[$i]);

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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:45p
//Created in $/Leap/Source/Library/VehicleType
//new ajax files for vehicle
//
//
?>
