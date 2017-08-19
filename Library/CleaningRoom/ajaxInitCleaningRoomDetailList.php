<?php
//-------------------------------------------------------
// Purpose: To store the records of cleaning room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (01.05.09)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CleaningMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
    $cleaningRoomManager = CleaningRoomManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (hs.hostelName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR et.tempEmployeeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $cleaningRoomManager->getTotalCleaningRoomDetail($filter);
    $cleaningRoomRecordArray = $cleaningRoomManager->getCleaningRoomDetailList($filter,$limit,$orderBy);
    $cnt = count($cleaningRoomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface   
			$cleaningRoomRecordArray[$i]['Dated'] = UtilityManager::FormatDate($cleaningRoomRecordArray[$i]['Dated']);
        $valueArray = array_merge(array('action' => $cleaningRoomRecordArray[$i]['cleanId'] , 'srNo' => ($records+$i+1) ),$cleaningRoomRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitCleaningRoomDetailList.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Library/CleaningRoom
//fixed bugs
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:27p
//Created in $/LeapCC/Library/CleaningRoom
//all ajax files for cleaning room
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:55a
//Updated in $/LeapCC/Library/HostelRoomTypeDetail
//new ajax files uploaded for hostel room type detail add, delete, edit &
//list
//
//
?>