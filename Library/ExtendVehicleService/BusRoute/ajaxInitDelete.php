<?php
//-------------------------------------------------------
// Purpose: To delete busstop detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRouteMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['busRouteId']) || trim($REQUEST_DATA['busRouteId']) == '') {
        $errorMessage = 'Invalid BusStop';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BusRouteManager.inc.php");
        $busRouteManager =  BusRouteManager::getInstance();
        //as busstop table is independen no integrity check in done
		$checkBusRoute = $busRouteManager -> checkBusRouteLink("WHERE bs.busRouteId =".$REQUEST_DATA['busRouteId']);
		$checkBusPassRoute = $busRouteManager -> checkBusPassRouteLink("WHERE bp.busRouteId =".$REQUEST_DATA['busRouteId']);
        	$checkBusAccident = $busRouteManager -> checkBusAccident("WHERE busRouteId =".$REQUEST_DATA['busRouteId']);
 
        	$checkBusFee = $busRouteManager -> checkBusFee("WHERE busRouteId =".$REQUEST_DATA['busRouteId']); 
		$checkBusRouteMapping = $busRouteManager -> checkBusRouteMapping("WHERE busRouteId =".$REQUEST_DATA['busRouteId']);
		if (($checkBusRoute[0]['totalRecords'] > 0 || $checkBusPassRoute[0]['totalRecords'] > 0) || (($checkBusAccident[0]['totalRecords'] > 0 || $checkBusFee[0]['cnt'] > 0) || $checkBusRouteMapping[0]['cnt'] > 0)){
			echo DEPENDENCY_CONSTRAINT;
			die;
		}
		else {
           //***********************************************STRAT TRANSCATION************************************************
           //****************************************************************************************************************
           if(SystemDatabaseManager::getInstance()->startTransaction()) {
               $id = $REQUEST_DATA['busRouteId'];
               if($id=='') {
                 $id=0;   
               }
               $returnStatus = BusRouteManager::getInstance()->deleteBusMapping($id);
               if($returnStatus === false) {
                  echo FAILURE; 
                  die;
               }
               $returnStatus = BusRouteManager::getInstance()->deleteBusRoute($id);
               if($returnStatus === false) {
                  echo FAILURE; 
                  die;
               }
               //*****************************COMMIT TRANSACTION************************* 
               if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 echo DELETE;  
               }
               else {
                 echo FAILURE;  
               } 
           }
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Library/BusRoute
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BusRoute
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:00p
//Updated in $/Leap/Source/Library/BusRoute
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:33p
//Updated in $/Leap/Source/Library/BusRoute
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:07p
//Updated in $/Leap/Source/Library/BusRoute
//Created BusRoute Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:32p
//Created in $/Leap/Source/Library/BusRoute
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:29p
//Updated in $/Leap/Source/Library/BusStop
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:01p
//Created in $/Leap/Source/Library/BusStop
//Initial Checkin
?>

