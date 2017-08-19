<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BusRouteMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusRouteManager.inc.php");
    $busRouteManager = BusRouteManager::getInstance();
    
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
    
    $totalArray = $busRouteManager->getTotalBusRoute($filter);
    $busRouteRecordArray = $busRouteManager->getBusRouteList($filter,$limit,$orderBy);
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
    
// for VSS
// $History: ajaxInitList.php $
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:00p
//Updated in $/Leap/Source/Library/BusRoute
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:58p
//Updated in $/Leap/Source/Library/BusRoute
//Added AjaxList Funtioality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:34p
//Created in $/Leap/Source/Library/BusRoute
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:33p
//Updated in $/Leap/Source/Library/BusStop
//Added AjaxListing Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:43p
//Created in $/Leap/Source/Library/BusStop
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:40p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxListing Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:13p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:58p
//Updated in $/Leap/Source/Library/Degree
//Added AjaxList Functinality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:25a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Library/City
//Added AjaxList Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/27/08    Time: 5:08p
//Created in $/Leap/Source/Library/City
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>
