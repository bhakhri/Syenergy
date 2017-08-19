<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE subject and groups lists
// Author : Dipanjan Bhattacharjee
// Created on : (14.04.2009 )
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
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
   
    $busRouteId = $REQUEST_DATA['busRouteId'];
    
    if($busRouteId=='') {
      $busRouteId=0;  
    }
    
    $condition = " AND brm.busRouteId = $busRouteId";
    
    // Bus No.
    $busArray = $commonQueryManager->getRouteBusMapping($orderBy,$condition); 
    
    // Bus Stoppage
    $busStopArray = $commonQueryManager->getBusStop(' stopName',"WHERE busRouteId='".$busRouteId."'");
    
    //RouteWise Total Student
    $condition = " WHERE bp.busRouteId = $busRouteId";
    $routeWiseTotal = $commonQueryManager->getRouteBusTotal($condition);
    
    $cnt = $routeWiseTotal[0]['cnt'];
    if($cnt=='') {
      $cnt=0;  
    }
    
    echo json_encode($busArray).'!~!~!'.json_encode($busStopArray).'!~!~!'.$cnt;
die;    