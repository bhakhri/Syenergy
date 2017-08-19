<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelRoomCourse');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelRoomManager.inc.php");
    $hostelRoomManager = HostelRoomManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (hr.roomName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hs.hostelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR hr.roomCapacity LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR hrt.roomType LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomName';
    
     $orderBy = "$sortField $sortOrderBy";

	 //echo $orderBy;

	 //die;

    ////////////
    
    $totalArray = $hostelRoomManager->getTotalHostelRoom($filter);
    $hostelRoomRecordArray = $hostelRoomManager->getHostelRoomList($filter,$orderBy,$limit);
    $cnt = count($hostelRoomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $hostelRoomRecordArray[$i]['hostelRoomId'] , 'srNo' => ($records+$i+1) ),$hostelRoomRecordArray[$i]);

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
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Library/HostelRoom
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/HostelRoom
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:46p
//Updated in $/Leap/Source/Library/HostelRoom
//add define access in module
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/25/08    Time: 5:48p
//Updated in $/Leap/Source/Library/HostelRoom
//fixed bug
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:09p
//Updated in $/Leap/Source/Library/HostelRoom
//modified in indentation
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/30/08    Time: 2:52p
//Created in $/Leap/Source/Library/HostelRoom
//List using ajax functions
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
?>
