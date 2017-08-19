<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Nishu Bindal
// Created on : (19.04.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VehicleRouteMasterNew');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusRouteManagerNew.inc.php");
    $BusRouteManagerNew = BusRouteManagerNew::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (br.routeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          br.routeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  
                          b.busNo  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
    
    $totalArray = $BusRouteManagerNew->getTotalBusRoute($filter);
    $busRouteRecordArray = $BusRouteManagerNew->getBusRouteList($filter,$limit,$orderBy);
    $cnt = count($busRouteRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $busRouteRecordArray[$i]['busRouteId'] , 'srNo' => ($records+$i+1) ),$busRouteRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
