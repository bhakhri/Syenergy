<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Nishu Bindal
// Created on : (22.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BusStopMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

   require_once(MODEL_PATH . "/BusStopManagerNew.inc.php");
    $busStopManager = BusStopManagerNew::getInstance();

    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter =' AND (bs.stopName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bs.stopAbbr LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"   OR bsc.cityName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cityName';
   
    //$orderBy = " $sortField $sortOrderBy";

	$sortField2 = $sortField;
	$orderBy = " $sortField2 $sortOrderBy";
	

    $totalArray = $busStopManager->getTotalBusStop($filter);
    $busStopRecordArray = $busStopManager->getBusStopList($filter,$limit,$orderBy);
    $cnt = count($busStopRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $busStopRecordArray[$i]['busStopId'] , 'srNo' => ($records+$i+1) ),$busStopRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
?>
