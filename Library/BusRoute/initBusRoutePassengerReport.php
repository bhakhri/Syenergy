<?php
//-------------------------------------------------------
// Purpose: To store the records of Bus Route in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (12.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','BusRoutePassengerReport');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusRouteManager.inc.php");
    $busRouteManager = BusRouteManager::getInstance();

    /////////////////////////
    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeCode';
    
    $orderBy = " $sortField $sortOrderBy";
	
	$busRouteId = trim($REQUEST_DATA['busRouteId']);


	if($busRouteId == 0) {
		$busRouteWithoutCountArray = $busRouteManager->getBusRouteList('','',$orderBy);
		$busRouteWithoutList = UtilityManager::makeCSList($busRouteWithoutCountArray,'busRouteId');
	
		$busRouteConditions = "WHERE bp.busRouteId IN ($busRouteWithoutList)";
		$busRouteArray = $busRouteManager->getBusRouteList($busRouteConditions,'',$orderBy);
		$busRouteCount = count($busRouteArray);
		$busRouteCountArray = $busRouteManager->getBusRouteList($busRouteConditions,$limit,$orderBy);
	}
	else {
		$busRouteConditions = "WHERE bp.busRouteId = ".$busRouteId;
		$busRouteCountArray = $busRouteManager->getBusRouteList($busRouteConditions,$limit,$orderBy);
		
		$conditions = "AND bp.busRouteId = ".$busRouteId;
		$studentCountArray = $busRouteManager->getStudentRouteCount($conditions);
		$studentCount = $studentCountArray[0]['studentCount'];

		$employeeConditions = "AND ebp.busRouteId = ".$busRouteId;
		$employeeCountArray = $busRouteManager->getEmployeeRouteCount($employeeConditions);
		$employeeCount =	$employeeCountArray[0]['employeeCount'];

		$passengerCount = $studentCount + $employeeCount;
	}
	
    $count = count($busRouteCountArray);

    for($i=0;$i<$count;$i++) {
        // add designationId in actionId to populate edit/delete icons in User Interface
		if($busRouteId == 0) {
			$busRouteWithoutId = $busRouteCountArray[$i]['busRouteId'];

			$conditions = "AND bp.busRouteId = ".$busRouteWithoutId;
			$studentCountArray = $busRouteManager->getStudentRouteCount($conditions);
			$studentCount = $studentCountArray[0]['studentCount'];

			$employeeConditions = "AND ebp.busRouteId = ".$busRouteWithoutId;
			$employeeCountArray = $busRouteManager->getEmployeeRouteCount($employeeConditions);
			$employeeCount =	$employeeCountArray[0]['employeeCount'];

			$passengerCount = $studentCount + $employeeCount;

			$actionStr = '<a href="#" title="Print"><img src="'.IMG_HTTP_PATH.'/print1.gif" border="0" width="15px" height="15px" alt="Print" onclick="printReport('.$busRouteWithoutId.');return false;"></a>
			<a href="#" title="Export to Excel"><img src="'.IMG_HTTP_PATH.'/excel1.jpg" border="0"  width="15px" height="15px" onClick="printCSV('.$busRouteWithoutId.');"/></a>';

			$valueArray = array_merge(array('passengerCount'=>$passengerCount,'actionPrint'=>$actionStr,'srNo' => ($records+$i+1) ),$busRouteCountArray[$i]);

			if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
			}
			else {
				$json_val .= ','.json_encode($valueArray);
			}
		}
		else {
			$actionStr = '<a href="#" title="Print"><img src="'.IMG_HTTP_PATH.'/print1.gif" border="0" width="15px" height="15px" alt="Print" onclick="printReport('.$busRouteId.');return false;"></a>
			<a href="#" title="Export to Excel"><img src="'.IMG_HTTP_PATH.'/excel1.jpg" border="0"  width="15px" height="15px" onClick="printCSV('.$busRouteId.');"/></a>';

			$valueArray = array_merge(array('passengerCount'=>$passengerCount,'actionPrint'=>$actionStr,'srNo' => ($records+$i+1) ),$busRouteCountArray[$i]);

			if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
			}
			else {
				$json_val .= ','.json_encode($valueArray);
			}
		}

    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$busRouteCount.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: $
//
?>