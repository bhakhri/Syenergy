<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BusStopRouteMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusStopManager.inc.php");
    $busStopManager = BusStopManager::getInstance();

    
    $timeTablelabelId = trim($REQUEST_DATA['labelId']);
    $routeId = trim($REQUEST_DATA['routeId']);
    
    if($timeTablelabelId=='') {
      $timeTablelabelId=0;  
    }
    
    if($routeId=='') {
      $routeId=0;  
    }
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stopName';
    $orderBy = " $sortField $sortOrderBy";  

    
    $totalArray = $busStopManager->getBusRoutMappingCount($timeTablelabelId,$routeId);
    $busStopRecordArray = $busStopManager->getBusRoutMappingList($timeTablelabelId,$routeId,$orderBy,$limit);
    $cnt = count($busStopRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $busStopId = $busStopRecordArray[$i]['busStopId'];
        $checkall = '<input type="checkbox" name="chb[]"  value="'.$busStopId.'">';
        
        // add quotaId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                        'checkAll' => $checkall ),
                                        $busStopRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
